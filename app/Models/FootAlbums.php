<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FootAlbums extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
    ];

    public function CreatedBy(){
        return $this->belongsTo(User::class,'created_by','id');
    }
}
