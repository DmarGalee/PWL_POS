<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetailModel extends Model
{
    use HasFactory;

    protected $table = 't_penjualan_detail'; // Nama tabel detail penjualan
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id_penjualan',
        'id_barang',
        'jumlah_barang',
        'harga_satuan',
    ];

    // Relasi ke Model PenjualanModel
    public function penjualan()
    {
        return $this->belongsTo(PenjualanModel::class, 'id_penjualan', 'id');
    }

    // Relasi ke Model BarangModel (jika diperlukan)
    public function barang()
    {
        return $this->belongsTo(BarangModel::class, 'id_barang', 'id');
    }
}