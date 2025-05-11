<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['profile', 'hash'];

    /**
     * Relacionamento com User
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
    
}