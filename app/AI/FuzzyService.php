<?php

namespace App\AI;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class FuzzyService
{
    protected $pythonPath;
    protected $scriptPath;

    public function __construct()
    {
        $this->initializePythonPath();
        $this->validateScriptPath();
        $this->logInitialization();
    }

    protected function initializePythonPath(): void
    {
        $venvPaths = [
            'windows' => base_path('venv/Scripts/python.exe'),
            'unix' => base_path('venv/bin/python')
        ];

        foreach ($venvPaths as $path) {
            if (file_exists($path)) {
                $this->pythonPath = $path;
                return;
            }
        }

        $this->pythonPath = 'python';
    }

    protected function validateScriptPath(): void
    {
        $this->scriptPath = base_path('fuzzy_model.py');

        if (!file_exists($this->scriptPath)) {
            throw new \RuntimeException("Script Python tidak ditemukan di: " . $this->scriptPath);
        }
    }

    protected function logInitialization(): void
    {
        Log::info('FuzzyService initialized', [
            'python_path' => $this->pythonPath,
            'script_path' => $this->scriptPath,
            'venv_active' => strpos($this->pythonPath, 'venv') !== false
        ]);
    }

    public function analyze(int $siklusId, ?float $currentFcr = null, array $historicalFcr = []): array
    {
        try {
            $command = $this->buildCommand($siklusId, $currentFcr, $historicalFcr);
            $process = $this->createProcess($command);

            $this->logProcessStart($siklusId, $currentFcr, $command);
            $process->run();

            return $this->handleProcessOutput($process, $siklusId);
        } catch (ProcessFailedException $e) {
            return $this->handleProcessFailure($e, $siklusId);
        } catch (\Exception $e) {
            return $this->handleGenericError($e, $siklusId, $currentFcr);
        }
    }

    protected function buildCommand(int $siklusId, ?float $currentFcr, array $historicalFcr = []): array
    {
        $command = [$this->pythonPath, $this->scriptPath, (string)$siklusId];

        if ($currentFcr !== null && $currentFcr > 0) {
            $command[] = $this->normalizeFcrValue($currentFcr);
        }

        // Tambahkan data historis jika ada
        if (!empty($historicalFcr)) {
            $command[] = escapeshellarg(json_encode($historicalFcr));
        }

        return $command;
    }

    protected function normalizeFcrValue(float $fcr): string
    {
        // Pastikan FCR dalam range yang wajar (0.5 - 3.0)
        return (string)max(0.01, min($fcr, 3.0));
    }

    protected function createProcess(array $command): Process
    {
        $process = new Process($command);
        $process->setWorkingDirectory(base_path());
        $process->setTimeout(120);
        $process->setEnv($this->getEnvironmentVariables());

        return $process;
    }

    protected function getEnvironmentVariables(): array
    {
        $env = [
            'PATH' => getenv('PATH') ?: '',
            'PYTHONUNBUFFERED' => '1',
            'PYTHONIOENCODING' => 'utf-8'
        ];

        if (PHP_OS_FAMILY === 'Windows') {
            $env['SYSTEMROOT'] = getenv('SYSTEMROOT') ?: '';
            $env['TEMP'] = getenv('TEMP') ?: sys_get_temp_dir();
            $env['TMP'] = getenv('TMP') ?: sys_get_temp_dir();
        }

        if (strpos($this->pythonPath, 'venv') !== false) {
            $venvPath = base_path('venv');
            $env['VIRTUAL_ENV'] = $venvPath;
            $env['PYTHONPATH'] = PHP_OS_FAMILY === 'Windows'
                ? $venvPath . '/lib/site-packages'
                : $venvPath . '/lib/python*/site-packages';
        }

        return $env;
    }

    protected function handleProcessOutput(Process $process, int $siklusId): array
    {
        $output = $process->getOutput();
        $errorOutput = $process->getErrorOutput();

        $this->logProcessOutput($process, $output, $errorOutput);

        if (!$process->isSuccessful() || empty($output)) {
            throw new \Exception($process->isSuccessful() ? 'Output kosong' : $errorOutput);
        }

        $result = $this->parseOutput($output, $siklusId);
        $this->logSuccessfulAnalysis($siklusId, $result);

        return $result;
    }

    protected function parseOutput(string $output, int $siklusId): array
    {
        $result = json_decode($output, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON: ' . json_last_error_msg());
        }

        if (isset($result['error'])) {
            throw new \Exception($result['error']);
        }

        return $this->formatResult($result, $siklusId);
    }

    protected function formatResult(array $result, int $siklusId): array
    {
        $defaultChartData = [
            'labels' => [],
            'datasets' => []
        ];

        return [
            'siklus_id' => $siklusId,
            'current_fcr' => $result['current_fcr'] ?? 0,
            'current_recommendation' => $result['current_recommendation'] ?? 'Tidak tersedia', // ✅ Tambahkan ini
            'predicted_fcr' => $result['predicted_fcr'] ?? 0,
            'recommendation' => $result['recommendation'] ?? 'Tidak dapat menghasilkan rekomendasi',
            'status' => $result['status'] ?? 'unknown',
            'action' => $result['action'] ?? 'manual_check',
            'processed_at' => now()->format('Y-m-d H:i:s'),
            'chart_data' => $result['chart_data'] ?? $defaultChartData,
            'fuzzy_details' => [
                'current_fcr_category' => $this->categorizeFcr($result['current_fcr'] ?? 0),
                'predicted_fcr_category' => $this->categorizeFcr($result['predicted_fcr'] ?? 0),
                'recommendation_score' => $result['recommendation_value'] ?? 0,
                'historical_data_used' => $result['historical_data_used'] ?? false,
                'prediction_method' => $result['prediction_method'] ?? null
            ],
            'service_version' => '2.1.0'
        ];
    }
    protected function categorizeFcr(float $fcr): string
    {
        if ($fcr <= 0) return 'invalid';
        if ($fcr < 1) return 'baik';
        if ($fcr <= 1.5) return 'sedang';
        return 'buruk';
    }

    protected function logProcessStart(int $siklusId, ?float $fcr, array $command): void
    {
        Log::info('Starting FCR analysis', [
            'siklus_id' => $siklusId,
            'current_fcr' => $fcr,
            'command' => implode(' ', $command)
        ]);
    }

    protected function logProcessOutput(Process $process, string $output, string $errorOutput): void
    {
        Log::debug('Python script output', [
            'exit_code' => $process->getExitCode(),
            'stdout' => $output,
            'stderr' => $errorOutput
        ]);
    }

    protected function logSuccessfulAnalysis(int $siklusId, array $result): void
    {
        Log::info('FCR analysis completed', [
            'siklus_id' => $siklusId,
            'current_fcr' => $result['current_fcr'],
            'predicted_fcr' => $result['predicted_fcr'],
            'status' => $result['status'],
            'recommendation' => $result['recommendation'],
            'historical_data_used' => $result['fuzzy_details']['historical_data_used'] ?? false
        ]);
    }

    protected function handleProcessFailure(ProcessFailedException $e, int $siklusId): array
    {
        Log::error('Fuzzy analysis process failed', [
            'siklus_id' => $siklusId,
            'error' => $e->getMessage(),
            'command' => $e->getProcess()->getCommandLine(),
            'exit_code' => $e->getProcess()->getExitCode(),
            'output' => $e->getProcess()->getOutput(),
            'error_output' => $e->getProcess()->getErrorOutput()
        ]);

        return $this->getFallbackResponse($siklusId, 'Process failed: ' . $e->getMessage());
    }

    protected function handleGenericError(\Exception $e, int $siklusId, ?float $fcr): array
    {
        Log::error('Fuzzy analysis error', [
            'siklus_id' => $siklusId,
            'current_fcr' => $fcr,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return $this->getFallbackResponse($siklusId, $e->getMessage());
    }

    protected function getFallbackResponse(int $siklusId, string $errorMessage): array
    {
        return [
            'siklus_id' => $siklusId,
            'error' => $errorMessage,
            'current_fcr' => 0,
            'predicted_fcr' => 0,
            'recommendation' => 'Tidak dapat menghasilkan rekomendasi saat ini',
            'status' => 'error',
            'action' => 'manual_check',
            'processed_at' => now()->format('Y-m-d H:i:s'),
            'chart_data' => [
                'labels' => [],
                'datasets' => []
            ],
            'fuzzy_details' => [
                'current_fcr_category' => 'unknown',
                'predicted_fcr_category' => 'unknown',
                'recommendation_score' => 0,
                'historical_data_used' => false
            ],
            'service_version' => '2.1.0'
        ];
    }

    public function testConnection(): array
    {
        try {
            $process = new Process([$this->pythonPath, '-V']);
            $process->setTimeout(10);
            $process->run();

            return [
                'success' => $process->isSuccessful(),
                'python_path' => $this->pythonPath,
                'python_version' => trim($process->getOutput()),
                'script_exists' => file_exists($this->scriptPath),
                'venv_active' => strpos($this->pythonPath, 'venv') !== false
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'python_path' => $this->pythonPath
            ];
        }
    }

    public function analyzeBatch(array $siklusData): array
    {
        return array_map(function ($data) {
            try {
                return $this->analyze(
                    $data['siklus_id'],
                    $data['current_fcr'] ?? null,
                    $data['historical_fcr'] ?? []
                );
            } catch (\Exception $e) {
                return $this->getFallbackResponse($data['siklus_id'], $e->getMessage());
            }
        }, $siklusData);
    }
}
