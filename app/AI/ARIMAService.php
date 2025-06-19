<?php

namespace App\AI;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ARIMAService
{
    protected $pythonPath;
    protected $scriptPath;

    public function __construct()
    {
        $this->pythonPath = 'python';

        $venvPython = base_path('venv\Scripts\python.exe');
        if (file_exists($venvPython)) {
            $this->pythonPath = $venvPython;
        }

        $this->scriptPath = base_path('arima_model.py');

        if (!file_exists($this->scriptPath)) {
            throw new \RuntimeException("ARIMA script not found at: " . $this->scriptPath);
        }
    }

    public function predict(int $siklusId, int $steps = 14)
    {
        try {
            $command = [
                $this->pythonPath,
                $this->scriptPath,
                $siklusId,
                $steps
            ];

            $process = new Process($command);
            $process->setWorkingDirectory(base_path());
            $process->setTimeout(120);

            // Hapus PYTHONHOME dari environment variables
            $env = [
                'PATH' => getenv('PATH'),
                'SYSTEMROOT' => getenv('SYSTEMROOT'),
                'TEMP' => getenv('TEMP')
            ];

            // Hanya set PYTHONPATH jika menggunakan venv
            if ($this->pythonPath !== 'python') {
                $env['PYTHONPATH'] = base_path('venv\Lib\site-packages');
            }

            $process->setEnv($env);

            $process->run();

            if (!$process->isSuccessful()) {
                Log::error('ARIMA Prediction Failed', [
                    'command' => $process->getCommandLine(),
                    'exit_code' => $process->getExitCode(),
                    'error_output' => $process->getErrorOutput(),
                    'output' => $process->getOutput()
                ]);
                throw new ProcessFailedException($process);
            }

            $output = $process->getOutput();
            $result = json_decode($output, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON response: ' . $output);
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('ARIMA Service Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'siklus_id' => $siklusId,
                'steps' => $steps
            ]);

            return $this->getFallbackResponse($e->getMessage());
        }
    }

    protected function getFallbackResponse($errorMessage)
    {
        return [
            'error' => 'Prediction failed: ' . $errorMessage,
            'dates' => [],
            'future_dates' => [],
            'pengeluaran' => [
                'historical' => [],
                'predicted' => [],
                'metrics' => ['rmse' => 0, 'mape' => 0]
            ],
            'pemasukan' => [
                'historical' => [],
                'predicted' => [],
                'metrics' => ['rmse' => 0, 'mape' => 0]
            ],
            'profit' => [
                'historical' => [],
                'predicted' => [],
                'metrics' => ['rmse' => 0, 'mape' => 0]
            ]
        ];
    }
}
