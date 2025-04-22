<?php
 
 namespace App\Models;
 
 use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Model;
 use App\Models\StokModel;
 
 class BarangModel extends Model
 {
     use HasFactory;
 
     protected $table = 'm_barang';
     protected $primaryKey = 'id';
     protected $fillable = [
         'nama_barang',
         'deskripsi_barang',
         'harga_barang',
         'id_kategori',
         'id_supplier',
         'created_at'
     ];
     public $timestamps = false;
 
     public function kategori()
     {
         return $this->belongsTo(KategoriModel::class, 'id_kategori', 'id');
     }
     
     public function supplier()
     {
         return $this->belongsTo(SupplierModel::class, 'id_supplier', 'id');
     }
 }