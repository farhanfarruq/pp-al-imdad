<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReportPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Report $report): bool
    {
        // Admin utama bisa melihat semua laporan
        if ($user->hasRole('admin_utama')) {
            return true;
        }

        // Admin bidang hanya bisa melihat laporan dari bidangnya
        if ($user->hasRole('admin_bidang')) {
            return $user->bidang_id === $report->bidang_id;
        }
        
        // Pengguna biasa hanya bisa melihat laporannya sendiri
        return $user->id === $report->user_id;
    }

}