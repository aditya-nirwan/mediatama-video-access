<?php

namespace Database\Seeders;

use App\Enums\RequestStatus;
use App\Models\AccessRequest;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoAccess;
use Carbon\CarbonInterface;
use Illuminate\Database\Seeder;

class VideoAccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@mediatama.test')->firstOrFail();

        $excel = Video::where('slug', 'pengenalan-microsoft-excel')->first();
        $word = Video::where('slug', 'dasar-microsoft-word')->first();
        $powerpoint = Video::where('slug', 'teknik-presentasi-powerpoint')->first();

        if (! $excel || ! $word || ! $powerpoint) {
            $this->command->warn('Video sampel belum lengkap. VideoAccessSeeder dilewati.');

            return;
        }

        $adit = User::where('email', 'adit@customer.test')->firstOrFail();
        $sri = User::where('email', 'sri@customer.test')->firstOrFail();
        $irfan = User::where('email', 'irfan@customer.test')->firstOrFail();
        $baskoro = User::where('email', 'baskoro@customer.test')->firstOrFail();

        // 1. Akses aktif hasil persetujuan request.
        $this->grantFromRequest(
            $adit, $excel, $admin,
            now()->subDay(), now()->addDays(2),
            'Saya perlu mempelajari rumus dasar untuk pekerjaan.',
            'Disetujui untuk 3 hari.'
        );

        // 2. Akses langsung dari admin, tanpa request lebih dulu.
        VideoAccess::create([
            'user_id' => $adit->id,
            'video_id' => $powerpoint->id,
            'access_request_id' => null,
            'granted_by' => $admin->id,
            'starts_at' => now()->subHours(2),
            'expires_at' => now()->addDays(5),
        ]);

        // 3. Request yang masih menunggu keputusan admin.
        AccessRequest::create([
            'user_id' => $sri->id,
            'video_id' => $word->id,
            'reason' => 'Mohon akses untuk persiapan pelatihan minggu depan.',
            'status' => RequestStatus::Pending,
        ]);

        // 4. Akses yang akan habis sebentar lagi, untuk mendemokan hitung mundur.
        VideoAccess::create([
            'user_id' => $sri->id,
            'video_id' => $powerpoint->id,
            'access_request_id' => null,
            'granted_by' => $admin->id,
            'starts_at' => now()->subMinutes(50),
            'expires_at' => now()->addMinutes(10),
        ]);

        // 5. Akses yang sudah kedaluwarsa, siap untuk diminta ulang.
        $this->grantFromRequest(
            $irfan, $excel, $admin,
            now()->subDays(5), now()->subDay(),
            'Ingin mengulang materi Excel.',
            'Disetujui untuk 4 hari.'
        );

        // 6. Request yang ditolak.
        AccessRequest::create([
            'user_id' => $baskoro->id,
            'video_id' => $powerpoint->id,
            'reason' => 'Penasaran isi videonya.',
            'status' => RequestStatus::Rejected,
            'responded_by' => $admin->id,
            'responded_at' => now()->subHours(6),
            'admin_note' => 'Materi ini hanya untuk peserta yang sudah terdaftar.',
        ]);
    }

    private function grantFromRequest(
        User $customer,
        Video $video,
        User $admin,
        CarbonInterface $startsAt,
        CarbonInterface $expiresAt,
        string $reason,
        string $note
    ): void {
        $request = AccessRequest::create([
            'user_id' => $customer->id,
            'video_id' => $video->id,
            'reason' => $reason,
            'status' => RequestStatus::Approved,
            'responded_by' => $admin->id,
            'responded_at' => $startsAt,
            'admin_note' => $note,
        ]);

        VideoAccess::create([
            'user_id' => $customer->id,
            'video_id' => $video->id,
            'access_request_id' => $request->id,
            'granted_by' => $admin->id,
            'starts_at' => $startsAt,
            'expires_at' => $expiresAt,
        ]);
    }
}
