import os
os.environ['USERPROFILE'] = 'C:\\Users\\pandu'

import numpy as np
import skfuzzy as fuzz
from skfuzzy import control as ctrl
import json
import sys
from sklearn.linear_model import LinearRegression

def main():
    try:
        # Ambil parameter dari Laravel
        siklus_id = int(sys.argv[1]) if len(sys.argv) > 1 else 0

        # Tangani nilai current_fcr (bisa berupa angka atau list)
        try:
            if len(sys.argv) > 2:
                if sys.argv[2].startswith("["):
                    current_fcr_list = json.loads(sys.argv[2])
                    current_fcr = float(current_fcr_list[-1]) if current_fcr_list else 0
                else:
                    current_fcr = float(sys.argv[2])
            else:
                current_fcr = 0
        except:
            current_fcr = 0

        # Tangani data historis FCR (pastikan berupa list float)
        try:
            historical_fcr = json.loads(sys.argv[3]) if len(sys.argv) > 3 else []
            if not isinstance(historical_fcr, list):
                historical_fcr = []
        except:
            historical_fcr = []

        # Rekomendasi untuk nilai FCR saat ini
        if current_fcr <= 0:
            current_recommendation = "Tidak ada data FCR saat ini. Monitor perkembangan secara manual."
            current_status = "warning"
        else:
            if current_fcr < 1.0:
                current_recommendation = "FCR saat ini sangat baik. Pertahankan pola pemberian pakan saat ini."
                current_status = "baik"
            elif current_fcr < 1.5:
                current_recommendation = "FCR saat ini normal. Perhatikan kualitas pakan dan kondisi ikan."
                current_status = "sedang"
            else:
                current_recommendation = "FCR saat ini buruk. Evaluasi segera pemberian pakan dan kondisi kolam."
                current_status = "buruk"

        # Jika tidak ada data FCR untuk prediksi
        if current_fcr <= 0:
            predicted_fcr = 1.2
            recommendation = "Tidak ada data historis yang cukup. Monitor perkembangan FCR secara manual."
            status = "warning"
            prediksi_values = np.linspace(1.2, 1.3, 120)  # Simulasi 120 hari untuk 3 periode
        else:
            # Sistem Fuzzy
            fcr_current = ctrl.Antecedent(np.arange(0, 3, 0.1), 'current_fcr')
            fcr_trend = ctrl.Consequent(np.arange(0, 3, 0.1), 'fcr_trend')

            # Fungsi keanggotaan yang telah ditingkatkan
            fcr_current['baik'] = fuzz.trimf(fcr_current.universe, [0, 0.8, 1.2])
            fcr_current['sedang'] = fuzz.trimf(fcr_current.universe, [1.0, 1.5, 2.0])
            fcr_current['buruk'] = fuzz.trimf(fcr_current.universe, [1.8, 2.5, 3.0])

            fcr_trend['menurun'] = fuzz.trimf(fcr_trend.universe, [0, 0.8, 1.2])
            fcr_trend['stabil'] = fuzz.trimf(fcr_trend.universe, [1.0, 1.5, 2.0])
            fcr_trend['naik'] = fuzz.trimf(fcr_trend.universe, [1.8, 2.5, 3.0])

            # Aturan fuzzy berdasarkan tren historis
            if historical_fcr:
                avg_hist = sum(historical_fcr) / len(historical_fcr)
                trend = (current_fcr - avg_hist) / avg_hist

                if trend < -0.1:  # Tren menurun
                    rule1 = ctrl.Rule(fcr_current['baik'], fcr_trend['menurun'])
                    rule2 = ctrl.Rule(fcr_current['sedang'], fcr_trend['menurun'])
                    rule3 = ctrl.Rule(fcr_current['buruk'], fcr_trend['stabil'])
                elif trend > 0.1:  # Tren meningkat
                    rule1 = ctrl.Rule(fcr_current['baik'], fcr_trend['stabil'])
                    rule2 = ctrl.Rule(fcr_current['sedang'], fcr_trend['naik'])
                    rule3 = ctrl.Rule(fcr_current['buruk'], fcr_trend['naik'])
                else:  # Tren stabil
                    rule1 = ctrl.Rule(fcr_current['baik'], fcr_trend['stabil'])
                    rule2 = ctrl.Rule(fcr_current['sedang'], fcr_trend['stabil'])
                    rule3 = ctrl.Rule(fcr_current['buruk'], fcr_trend['naik'])
            else:
                # Aturan default jika tidak ada data historis
                rule1 = ctrl.Rule(fcr_current['baik'], fcr_trend['stabil'])
                rule2 = ctrl.Rule(fcr_current['sedang'], fcr_trend['stabil'])
                rule3 = ctrl.Rule(fcr_current['buruk'], fcr_trend['naik'])

            # Simulasi sistem fuzzy
            fcr_ctrl = ctrl.ControlSystem([rule1, rule2, rule3])
            fcr_sim = ctrl.ControlSystemSimulation(fcr_ctrl)

            fcr_sim.input['current_fcr'] = current_fcr
            fcr_sim.compute()

            predicted_value = fcr_sim.output['fcr_trend']

            # Buat 3 periode prediksi (masing-masing 40 hari)
            periode1 = np.linspace(current_fcr, predicted_value, 40)

            # Gunakan regresi linear untuk periode 2 dan 3 jika data historis cukup
            if len(historical_fcr) >= 3:
                # Siapkan data untuk regresi
                X = np.array(range(len(historical_fcr))).reshape(-1, 1)
                y = np.array(historical_fcr)

                # Latih model regresi linear
                model = LinearRegression()
                model.fit(X, y)

                # Prediksi nilai masa depan
                next_days = np.array([len(historical_fcr), len(historical_fcr)+1, len(historical_fcr)+2]).reshape(-1, 1)
                future_pred = model.predict(next_days)

                # Buat periode berdasarkan hasil regresi
                periode2 = np.linspace(predicted_value, future_pred[0], 40)
                periode3 = np.linspace(future_pred[0], future_pred[1], 40)
            else:
                # Jika data tidak cukup, gunakan proyeksi sederhana
                if predicted_value < 1:  # Tren bagus
                    periode2 = np.linspace(predicted_value, predicted_value * 0.98, 40)
                    periode3 = np.linspace(predicted_value * 0.98, predicted_value * 0.96, 40)
                elif predicted_value < 1.5:  # Tren sedang
                    periode2 = np.linspace(predicted_value, predicted_value * 1.02, 40)
                    periode3 = np.linspace(predicted_value * 1.02, predicted_value * 1.04, 40)
                else:  # Tren buruk
                    periode2 = np.linspace(predicted_value, predicted_value * 1.05, 40)
                    periode3 = np.linspace(predicted_value * 1.05, predicted_value * 1.1, 40)

            # Gabungkan semua periode
            prediksi_values = np.concatenate([periode1, periode2, periode3])

            # Hitung rata-rata tiap periode
            avg_periode1 = np.mean(periode1)
            avg_periode2 = np.mean(periode2)
            avg_periode3 = np.mean(periode3)
            avg_total = np.mean(prediksi_values)

            # Rekomendasi berdasarkan rata-rata keseluruhan
            if avg_total < 1:
                recommendation = "FCR diprediksi sangat baik. Pertahankan pola pemberian pakan saat ini."
                status = "baik"
            elif avg_total < 1.5:
                recommendation = "FCR diprediksi normal. Perhatikan kualitas pakan dan kondisi ikan."
                status = "sedang"
            else:
                recommendation = "FCR diprediksi buruk. Segera evaluasi pemberian pakan dan kondisi kolam."
                status = "buruk"

        # Siapkan hasil akhir dengan 3 periode
        result = {
            "siklus_id": siklus_id,
            "current_fcr": current_fcr,
            "current_status": current_status,
            "current_recommendation": current_recommendation,
            "predicted_fcr": float(avg_total),
            "recommendation": recommendation,
            "status": status,
            "action": "monitoring",
            "chart_data": {
                "labels": ["Periode 1 (40 hari)", "Periode 2 (40 hari)", "Periode 3 (40 hari)"],
                "datasets": [
                    {
                        "label": "Prediksi FCR",
                        "data": [float(avg_periode1), float(avg_periode2), float(avg_periode3)]
                    }
                ]
            },
            "fuzzy_details": {
                "current_fcr_category": current_status,
                "predicted_fcr_category": status,
                "recommendation_score": 10 - min(9, int(avg_total * 3)),
                "historical_data_used": len(historical_fcr) > 0,
                "prediction_method": "linear_regression" if len(historical_fcr) >= 3 else "simple_projection"
            }
        }

        print(json.dumps(result))

    except Exception as e:
        # Tangani error jika terjadi
        error_result = {
            "error": str(e),
            "siklus_id": siklus_id if 'siklus_id' in locals() else 0,
            "current_fcr": current_fcr if 'current_fcr' in locals() else 0,
            "predicted_fcr": 0,
            "recommendation": "Error dalam memproses prediksi",
            "status": "error"
        }
        print(json.dumps(error_result))

if __name__ == "__main__":
    main()
