<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@mediatama.test')->firstOrFail();

        $items = [
            ['Pengenalan Microsoft Excel', 'sample-1.mp4', 30, 'Mengenal antarmuka Excel dan rumus dasar.'],
            ['Dasar Microsoft Word', 'sample-2.mp4', 45, 'Pengaturan dokumen, format teks, dan pencetakan.'],
            ['Teknik Presentasi PowerPoint', 'sample-3.mp4', 60, 'Menyusun slide yang ringkas dan mudah dibaca.'],
        ];

        foreach ($items as [$title, $fileName, $duration, $description]) {
            $path = 'videos/'.$fileName;

            if (! Storage::disk('local')->exists($path)) {
                $this->command->warn("File {$path} tidak ditemukan. Video '{$title}' dilewati.");

                continue;
            }

            Video::create([
                'title' => $title,
                'slug' => Str::slug($title),
                'description' => $description,
                'file_path' => $path,
                'original_name' => $fileName,
                'mime_type' => 'video/mp4',
                'size_bytes' => Storage::disk('local')->size($path),
                'duration_seconds' => $duration,
                'uploaded_by' => $admin->id,
                'is_published' => true,
            ]);
        }
    }
}
