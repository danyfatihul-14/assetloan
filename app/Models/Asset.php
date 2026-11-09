<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    protected $fillable = ['kode', 'nama', 'kategori', 'kondisi', 'status'];

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}
