<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengurus extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
    
    /**
     * Relasi untuk mendapatkan semua tugas laporan melalui model Report.
     */
    public function reportTasks()
    {
        return $this->hasManyThrough(ReportTask::class, Report::class);
    }
}
