<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'user_id',
        'nama_aset',
        'kategori',
        'nilai',
        'deskripsi',
        'tanggal_perolehan',
        'transaction_id',
    ];

    protected $casts = [
        'nilai' => 'decimal:2',
        'tanggal_perolehan' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}