<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Albums extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
    ];

    public function CreatedBy(){
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function FootAlbums(){
        return $this->hasMany(FootAlbums::class,'album_id','id');
    }
}
