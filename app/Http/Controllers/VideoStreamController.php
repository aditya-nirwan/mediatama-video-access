<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VideoStreamController extends Controller
{
    private const CHUNK_SIZE = 524288;

    public function __invoke(Request $request, Video $video): StreamedResponse
    {
        $this->authorize('stream', $video);

        $disk = Storage::disk('local');

        abort_unless($disk->exists($video->file_path), 404);

        $absolutePath = $disk->path($video->file_path);
        $fileSize = $disk->size($video->file_path);

        [$start, $end] = $this->resolveRange($request->header('Range'), $fileSize);

        $length = $end - $start + 1;
        $isPartial = $request->headers->has('Range');

        $headers = [
            'Content-Type' => $video->mime_type,
            'Content-Length' => (string) $length,
            'Content-Disposition' => 'inline; filename="'.$video->slug.'.mp4"',
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Content-Type-Options' => 'nosniff',
        ];

        if ($isPartial) {
            $headers['Content-Range'] = "bytes {$start}-{$end}/{$fileSize}";
        }

        return response()->stream(
            $this->fileStreamer($absolutePath, $start, $length),
            $isPartial ? 206 : 200,
            $headers
        );
    }

    private function fileStreamer(string $path, int $start, int $length): callable
    {
        return function () use ($path, $start, $length): void {
            $handle = fopen($path, 'rb');

            if ($handle === false) {
                return;
            }

            fseek($handle, $start);

            $remaining = $length;

            while ($remaining > 0 && ! feof($handle) && ! connection_aborted()) {
                $readSize = (int) min(self::CHUNK_SIZE, $remaining);
                $buffer = fread($handle, $readSize);

                if ($buffer === false) {
                    break;
                }

                echo $buffer;
                flush();

                $remaining -= strlen($buffer);
            }

            fclose($handle);
        };
    }

    private function resolveRange(?string $header, int $fileSize): array
    {
        if ($header === null || ! preg_match('/^bytes=(\d*)-(\d*)$/', trim($header), $matches)) {
            return [0, $fileSize - 1];
        }

        $rawStart = $matches[1];
        $rawEnd = $matches[2];

        if ($rawStart === '' && $rawEnd === '') {
            return [0, $fileSize - 1];
        }

        if ($rawStart === '') {
            $suffixLength = (int) $rawEnd;
            $start = max(0, $fileSize - $suffixLength);
            $end = $fileSize - 1;
        } else {
            $start = (int) $rawStart;
            $end = $rawEnd === '' ? $fileSize - 1 : (int) $rawEnd;
        }

        $end = min($end, $fileSize - 1);

        if ($start > $end || $start >= $fileSize) {
            abort(416, 'Rentang yang diminta tidak valid.', [
                'Content-Range' => "bytes */{$fileSize}",
            ]);
        }

        return [$start, $end];
    }
}
