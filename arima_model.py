import os
import sys

# Fix Python initialization on Windows
if sys.platform == 'win32':
    # Disable hash randomization
    os.environ['PYTHONHASHSEED'] = '0'

    # Set critical environment variables if missing
    if 'SYSTEMROOT' not in os.environ:
        os.environ['SYSTEMROOT'] = r'C:\Windows'
    if 'TEMP' not in os.environ:
        os.environ['TEMP'] = r'C:\Windows\Temp'

import atexit
import pandas as pd
import numpy as np
import json
import warnings
from statsmodels.tsa.arima.model import ARIMA
from sklearn.metrics import mean_squared_error, mean_absolute_percentage_error
from math import sqrt
from datetime import datetime, timedelta
import logging

# Setup logging
logging.basicConfig(
    filename=os.path.join(os.path.dirname(__file__), 'arima.log'),
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s'
)

# Fix Python initialization error on Windows
if sys.platform == 'win32':
    os.environ['PYTHONHASHSEED'] = '0'
    try:
        import win32api
    except ImportError:
        logging.warning("win32api module not available, continuing without it")

# Add virtual environment site-packages to path
venv_path = os.path.join(os.path.dirname(__file__), '..', 'venv')
if os.path.exists(venv_path):
    site_packages = os.path.join(venv_path,  'Lib', 'site-packages')
    if os.path.exists(site_packages):
        sys.path.insert(0, site_packages)

# Database connector setup
try:
    import mysql.connector
    USE_PYMYSQL = False
    logging.info("Using mysql-connector-python")
except ImportError:
    try:
        import pymysql as mysql
        USE_PYMYSQL = True
        logging.info("Using pymysql as fallback")
    except ImportError:
        logging.error("Neither mysql-connector-python nor pymysql is installed")
        raise ImportError("Neither mysql-connector-python nor pymysql is installed. Please install one of them.")

# Suppress warnings
warnings.filterwarnings('ignore')

class ARIMAModel:
    def __init__(self, db_config=None):
        self.db_config = db_config or {
            'host': 'localhost',
            'user': 'root',
            'password': '',
            'database': 'lelef10'
        }
        logging.info("ARIMAModel initialized with database config: %s", self.db_config)

    def get_db_connection(self):
        """Membuat koneksi database dengan fallback ke pymysql jika mysql.connector tidak tersedia"""
        try:
            if USE_PYMYSQL:
                conn = mysql.connect(
                    host=self.db_config['host'],
                    user=self.db_config['user'],
                    password=self.db_config['password'],
                    database=self.db_config['database']
                )
            else:
                conn = mysql.connector.connect(
                    host=self.db_config['host'],
                    user=self.db_config['user'],
                    password=self.db_config['password'],
                    database=self.db_config['database']
                )
            logging.info("Database connection established successfully")
            return conn
        except Exception as e:
            logging.error("Database connection error: %s", str(e))
            return None

    def fetch_siklus_data(self, siklus_id):
        """Mengambil data harian dalam satu siklus"""
        query = f"""
        SELECT
            tanggal,
            COALESCE((
                SELECT SUM(b.kuantitas *
                    CASE b.type
                        WHEN 'Bibit Standar' THEN 43000
                        WHEN 'Bibit Premium' THEN 65000
                    END)
                FROM bibits b
                WHERE b.siklus_id = {siklus_id} AND b.tanggal <= t.tanggal
            ), 0) as akumulasi_bibit,

            COALESCE((
                SELECT SUM(p.kuantitas *
                    CASE p.tipe
                        WHEN 'Pakan Standar' THEN 15000
                        WHEN 'Pakan Premium' THEN 18000
                    END)
                FROM pakans p
                WHERE p.siklus_id = {siklus_id} AND p.tanggal <= t.tanggal
            ), 0) as akumulasi_pakan,

            COALESCE((
                SELECT SUM(pan.kuantitas * COALESCE(pan.harga_jual, 0))
                FROM panens pan
                WHERE pan.siklus_id = {siklus_id} AND pan.tanggal <= t.tanggal
            ), 0) as akumulasi_panen
        FROM (
            SELECT DISTINCT tanggal FROM bibits WHERE siklus_id = {siklus_id}
            UNION
            SELECT DISTINCT tanggal FROM pakans WHERE siklus_id = {siklus_id}
            UNION
            SELECT DISTINCT tanggal FROM panens WHERE siklus_id = {siklus_id}
        ) t
        ORDER BY tanggal
        """

        try:
            logging.info("Fetching data for siklus_id: %d", siklus_id)
            conn = self.get_db_connection()
            if conn is None:
                logging.error("No database connection available")
                return None

            # Gunakan pandas untuk membaca data dengan parse_dates
            df = pd.read_sql(query, conn, parse_dates=['tanggal'])
            conn.close()

            if len(df) < 1:
                logging.warning("No data found for siklus_id: %d", siklus_id)
                return None

            # Pastikan kolom tanggal dalam format datetime
            if not pd.api.types.is_datetime64_any_dtype(df['tanggal']):
                df['tanggal'] = pd.to_datetime(df['tanggal'], errors='coerce')
                df = df.dropna(subset=['tanggal'])

            # Hitung total pengeluaran dan profit
            df['total_pengeluaran'] = df['akumulasi_bibit'] + df['akumulasi_pakan']
            df['profit'] = df['akumulasi_panen'] - df['total_pengeluaran']

            logging.info("Successfully fetched %d records for siklus_id: %d", len(df), siklus_id)
            return df[['tanggal', 'total_pengeluaran', 'akumulasi_panen', 'profit']]

        except Exception as e:
            logging.error("Error fetching data: %s", str(e))
            return None

    def prepare_future_dates(self, last_date, steps):
        """Mempersiapkan tanggal prediksi"""
        try:
            if isinstance(last_date, str):
                last_date = datetime.strptime(last_date, '%Y-%m-%d')
            elif hasattr(last_date, 'strftime'):  # Jika sudah datetime
                pass
            else:
                last_date = datetime.now()

            future_dates = [(last_date + timedelta(days=i+1)).strftime('%d/%m/%Y') for i in range(steps)]
            logging.info("Prepared %d future dates starting from %s", steps, last_date)
            return future_dates
        except Exception as e:
            logging.error("Error preparing future dates: %s", str(e))
            # Fallback: buat tanggal dari hari ini
            last_date = datetime.now()
            return [(last_date + timedelta(days=i+1)).strftime('%d/%m/%Y') for i in range(steps)]

    def train_and_predict(self, series, steps=7):
        """Melatih model dan membuat prediksi"""
        try:
            logging.info("Training model with %d data points", len(series))

            # Jika data kosong
            if len(series) == 0:
                logging.warning("Empty series provided")
                return {
                    'values': [],
                    'predictions': [0] * steps,
                    'metrics': {'rmse': 0, 'mape': 0}
                }

            # Jika hanya ada 1 data point, kembalikan prediksi flat
            if len(series) == 1:
                logging.warning("Only 1 data point, returning flat predictions")
                return {
                    'values': series.tolist(),
                    'predictions': [series.iloc[0]] * steps,
                    'metrics': {'rmse': 0, 'mape': 0}
                }

            # Jika hanya ada 2 data point, gunakan model sederhana
            if len(series) == 2:
                logging.warning("Only 2 data points, using simple growth rate model")
                growth_rate = (series.iloc[1] - series.iloc[0]) / series.iloc[0] if series.iloc[0] != 0 else 0
                predictions = [series.iloc[1] * (1 + growth_rate)**(i+1) for i in range(steps)]
                return {
                    'values': series.tolist(),
                    'predictions': predictions,
                    'metrics': {'rmse': 0, 'mape': 0}
                }

            # Model ARIMA dengan auto-tuning sederhana
            model = ARIMA(series, order=(1,1,1))
            model_fit = model.fit()

            # Prediksi
            forecast = model_fit.forecast(steps=steps)

            # Evaluasi model
            predictions = model_fit.predict(start=0, end=len(series)-1)
            rmse = sqrt(mean_squared_error(series, predictions))

            try:
                mape = mean_absolute_percentage_error(series, predictions)
            except:
                mape = 0

            logging.info("Model trained successfully. RMSE: %.2f, MAPE: %.2f%%", rmse, mape*100)

            return {
                'values': series.tolist(),
                'predictions': forecast.tolist(),
                'metrics': {'rmse': rmse, 'mape': mape}
            }

        except Exception as e:
            logging.error("Model training error: %s", str(e))
            # Jika model gagal, kembalikan prediksi flat berdasarkan nilai terakhir
            last_value = series.iloc[-1] if len(series) > 0 else 0
            return {
                'values': series.tolist(),
                'predictions': [last_value] * steps,
                'metrics': {'rmse': 0, 'mape': 0}
            }

    def predict_for_siklus(self, siklus_id, steps=7):
        """Membuat prediksi untuk siklus tertentu"""
        try:
            logging.info("Starting prediction for siklus_id: %d, steps: %d", siklus_id, steps)
            df = self.fetch_siklus_data(siklus_id)
            if df is None or len(df) == 0:
                error_msg = 'Tidak ada data transaksi untuk siklus ini'
                logging.error(error_msg)
                return {'error': error_msg}

            # Format tanggal
            dates = df['tanggal'].dt.strftime('%d/%m/%Y').tolist()
            future_dates = self.prepare_future_dates(df['tanggal'].iloc[-1], steps)

            # Prediksi untuk setiap metrik
            results = {
                'dates': dates,
                'future_dates': future_dates
            }

            metrics = ['total_pengeluaran', 'akumulasi_panen', 'profit']
            output_keys = ['pengeluaran', 'pemasukan', 'profit']

            for metric, key in zip(metrics, output_keys):
                series = df[metric]
                prediction = self.train_and_predict(series, steps)

                if prediction:
                    results[key] = {
                        'historical': prediction['values'],
                        'predicted': prediction['predictions'],
                        'metrics': prediction['metrics']
                    }

            logging.info("Prediction completed successfully for siklus_id: %d", siklus_id)
            return results

        except Exception as e:
            error_msg = f'Gagal membuat prediksi: {str(e)}'
            logging.error(error_msg)
            return {'error': error_msg}

def main():
    try:
        if len(sys.argv) < 2:
            error_msg = 'Siklus ID is required'
            print(json.dumps({'error': error_msg}))
            logging.error(error_msg)
            return

        siklus_id = int(sys.argv[1])
        steps = int(sys.argv[2]) if len(sys.argv) > 2 else 7

        logging.info("Running ARIMA model with siklus_id: %d, steps: %d", siklus_id, steps)
        model = ARIMAModel()
        result = model.predict_for_siklus(siklus_id, steps)
        print(json.dumps(result, indent=2))

    except Exception as e:
        error_msg = f"Main function error: {str(e)}"
        logging.error(error_msg)
        print(json.dumps({'error': error_msg}))

if __name__ == "__main__":
    # Cleanup function when script exits
    def cleanup():
        logging.info("Script execution completed")

    atexit.register(cleanup)
    main()
