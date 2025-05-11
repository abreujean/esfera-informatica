<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'hash',
        'title',
        'description',
        'status'
    ];

    /**
     * Relacionamento com User (N:N)
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'task_user');
    }
}
