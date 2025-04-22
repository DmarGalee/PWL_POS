<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanModel extends Model
{
    use HasFactory;

    protected $table = 't_penjualan';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'no_penjualan',
        'tanggal_penjualan',
        'id_user'
    ];
    protected $dates = ['tanggal_penjualan'];
    protected $casts = [
        'tanggal_penjualan' => 'date:Y-m-d'
    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'id_user', 'user_id');
    }

    public function PenjualanDetail()
    {
        return $this->hasMany(PenjualanDetailModel::class, 'id_penjualan', 'id');
    }

    // Relasi ke PenjualanDetailModel
    public function details()
    {
        return $this->hasMany(PenjualanDetailModel::class, 'id_penjualan', 'id');
    }

    public function scopePeriode($query, $start, $end)
    {
        return $query->whereBetween('tanggal_penjualan', [$start, $end]);
    }

    public function getNomorPenjualanAttribute()
    {
        return 'PJ' . substr($this->no_penjualan, 2);
    }

     // Fungsi untuk menghitung total item dan total harga
    public function getTotalItemAttribute()
    {
        return $this->details->sum('jumlah_barang'); // Asumsi kolom jumlah_barang
    }

    public function getTotalHargaAttribute()
    {
        return $this->details->sum(function ($detail) {
            return $detail->jumlah_barang * $detail->harga_satuan;  // Corrected
        });
    }
}