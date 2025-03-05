<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelModel extends Model
{
    use HasFactory;

    protected $table = 'm_level'; // Gantilah dengan nama tabel yang benar
    protected $primaryKey = 'level_id';
    public $timestamps = false; // Jika tabel tidak memiliki timestamps
}
