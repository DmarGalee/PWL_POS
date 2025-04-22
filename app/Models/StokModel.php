<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BarangModel;

class StokModel extends Model
{
    use HasFactory;

    protected $table = 't_stok';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id_barang',
        'jumlah_stok',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function barang()
    {
        return $this->belongsTo(BarangModel::class, 'id_barang', 'id'); // Foreign keynya id_barang
    }

    public function scopeTersedia($query)
    {
        return $query->where('jumlah_stok', '>', 0); // Gunakan jumlah_stok
    }

    public function getStatusStokAttribute()
    {
        return $this->jumlah_stok > 0 ? 'Tersedia' : 'Habis'; // Gunakan jumlah_stok
    }
}
