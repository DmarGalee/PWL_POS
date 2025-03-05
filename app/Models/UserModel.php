<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\BelongsToManyRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserModel extends Model
{
    use HasFactory;

    protected $table = 'm_user'; // Ganti 'm_user' dengan 'users'
    protected $primaryKey = 'user_id';
    protected $fillable = ['level_id', 'username', 'nama_lengkap', 'password']; // Tambahkan 'password'
    public $timestamps = true; // Tambahkan ini untuk timestamps otomatis

    public function level(): BelongsTo{
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    } 

    
}