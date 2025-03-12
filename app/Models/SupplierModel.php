<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierModel extends Model
{
    use HasFactory;

    protected $table = 'm_supplier';
    protected $primaryKey = 'id';
    protected $fillable = ['nama_supplier', 'alamat_supplier', 'telepon_supplier'];
    public $timestamps = true;
}