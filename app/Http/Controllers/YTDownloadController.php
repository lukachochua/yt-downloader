<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Storage;

class YTDownloadController extends Controller
{
    public function download(Request $request)
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        $videoUrl = escapeshellarg($request->input('url')); // Prevents command injection
        $outputPath = storage_path('app/public/downloads');

        if (!file_exists($outputPath)) {
            mkdir($outputPath, 0777, true);
        }

        // Define output filename format
        $outputFile = $outputPath . '/%(title)s.%(ext)s';

        // Construct yt-dlp command
        $command = "yt-dlp -f \"bv*[ext=mp4]+ba[ext=m4a]/b[ext=mp4]\" $videoUrl --merge-output-format mp4 -o \"$outputFile\"";

        $process = Process::fromShellCommandline($command);
        $process->setTimeout(600); // Set timeout to 10 minutes

        try {
            $process->mustRun();

            // Find the downloaded file
            $downloadedFile = glob($outputPath . '/*.mp4')[0] ?? null;
            if (!$downloadedFile) {
                return response()->json(['error' => 'File not found after download'], 500);
            }

            return response()->download($downloadedFile)->deleteFileAfterSend();
        } catch (ProcessFailedException $exception) {
            return response()->json(['error' => 'Download failed: ' . $exception->getMessage()], 500);
        }
    }
}
