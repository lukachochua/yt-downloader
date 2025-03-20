<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class YTDownloadController extends Controller
{
    public function download(Request $request)
    {
        set_time_limit(600); // 600 seconds = 10 minutes

        $request->validate([
            'url' => 'required|url'
        ]);

        // Sanitize the URL input to prevent injection
        $videoUrl = escapeshellarg($request->input('url'));

        // Get storage path (always uses correct path based on OS)
        $outputPath = storage_path('app/public/downloads');

        // Make sure the output directory exists
        if (!file_exists($outputPath)) {
            mkdir($outputPath, 0777, true);
        }

        // Define output filename format using Unix-style slashes for Windows compatibility
        $outputFile = $outputPath . '/%(title)s.%(ext)s';

        // Full path to yt-dlp and ffmpeg executables
        $ytDlpPath = 'C:\\Users\\lukac\\AppData\\Local\\Programs\\Python\\Python313\\Scripts\\yt-dlp.exe'; // Update with the full path
        $ffmpegPath = 'C:\\Users\\lukac\\AppData\\Local\\Microsoft\\WinGet\\Links\\ffmpeg.exe'; // If needed, update ffmpeg path

        // Construct yt-dlp command
        $command = "$ytDlpPath -f \"bv*[ext=mp4]+ba[ext=m4a]/b[ext=mp4]\" $videoUrl --merge-output-format mp4 -o \"$outputFile\"";

        $process = Process::fromShellCommandline($command);
        $process->setTimeout(600); 
        
        try {
            $process->mustRun();

            // Find the downloaded file
            $downloadedFile = glob($outputPath . '/*.mp4')[0] ?? null;
            if (!$downloadedFile) {
                return redirect()->back()->with('error', 'File not found after download.');
            }

            // Create a URL for the downloaded file
            $fileUrl = asset('storage/downloads/' . basename($downloadedFile));

            // Flash success message to session with the download link
            Session::flash('success', 'Your download is ready!');

            // Return back with the download link and success message
            return view('download')->with('fileUrl', $fileUrl);
        } catch (ProcessFailedException $exception) {
            return redirect()->back()->with('error', 'Download failed: ' . $exception->getMessage());
        }
    }
}
