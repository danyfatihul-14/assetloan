<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    protected $fillable = [
        'asset_id', 'peminjam', 'keperluan', 'tgl_pinjam',
        'tgl_rencana_kembali', 'tgl_kembali', 'status',
    ];

    protected function casts(): array
    {
        return [
            'tgl_pinjam' => 'datetime',
            'tgl_rencana_kembali' => 'date',
            'tgl_kembali' => 'datetime',
        ];
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function getTerlambatAttribute(): bool
    {
        return $this->status === 'dipinjam'
            && $this->tgl_rencana_kembali?->isBefore(now()->startOfDay());
    }
}
