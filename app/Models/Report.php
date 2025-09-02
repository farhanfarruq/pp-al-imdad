<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Report extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tanggal',
        'bidang_id',
        'pengurus_id', // DIUBAH DARI 'pengurus_nama'
        'waktu',       // DIUBAH DARI 'bpk_tab'
        'user_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal' => 'date',
    ];

    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class);
    }

    public function pengurus(): BelongsTo
    {
        return $this->belongsTo(Pengurus::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(ReportTask::class);
    }

    public function uploads(): HasMany
    {
        return $this->hasMany(Upload::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}