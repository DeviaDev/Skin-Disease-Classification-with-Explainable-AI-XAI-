<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SkinDetectorController extends Controller
{
    /**
     * Show the main SkinVision AI page.
     */
    public function home()
    {
        return view('home');
    }

    public function detection()
    {
        return view('detection');
    }

      public function diseases()
    {
        return view('diseases');
    }

        public function template()
    {
        return view('template');
    }
    /**
     * Receive an uploaded image, run the Python model, return prediction + Grad-CAM.
     */
    public function predict(Request $request)
    {
        Log::info("MASUK PREDICT");
        $request->validate([
            'image' => 'required|image|max:10240', // 10MB
        ]);

        $file = $request->file('image');

        // Store temporarily so the python script (running outside Laravel's
        // storage abstraction) can read it directly from disk.
        $tmpDir = storage_path('app/tmp');
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0755, true);
        }

        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $tmpPath = $tmpDir . DIRECTORY_SEPARATOR . $filename;
        $file->move($tmpDir, $filename);

        // $pythonBin = env('PYTHON_BIN', 'python3');
        // $scriptPath = base_path('python/predict.py');
        // $modelPath = env('SKIN_MODEL_PATH', base_path('python/EXP01_model_fold_4'));

        try {
            // $result = Process::timeout(120)->run([
            //     $pythonBin,
            //     $scriptPath,
            //     $tmpPath,
            //     $modelPath,
            // ]);

            // if ($result->failed()) {
            //     return response()->json([
            //         'error' => 'Inference process failed.',
            //         'details' => $result->errorOutput() ?: $result->output(),
            //     ], 500);
            // }

            // $output = trim($result->output());
            // $data = json_decode($output, true);

            // if (json_last_error() !== JSON_ERROR_NONE || !$data) {
            //     return response()->json([
            //         'error' => 'Could not parse model output.',
            //         'raw' => $output,
            //     ], 500);
            // }

            // if (isset($data['error'])) {
            //     return response()->json($data, 500);
            // }

        $response = Http::attach(
            'file',
            fopen($tmpPath, 'r'),
            basename($tmpPath)
        )->timeout(120)->post('http://127.0.0.1:8001/predict');

        if (!$response->successful()) {
            return response()->json([
                'error' => 'FastAPI tidak dapat dihubungi.',
                'details' => $response->body(),
            ], 500);
        }

        return response()->json($response->json());


        } finally {
            // Clean up temp upload regardless of success/failure
            if (file_exists($tmpPath)) {
                @unlink($tmpPath);
            }
        }
    }
}
