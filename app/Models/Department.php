<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * A department belongs to a branch
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * A department has many users
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
