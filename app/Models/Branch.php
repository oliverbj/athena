<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * A branch belongs to a company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * A branch has many departments
     */
    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    /**
     * A branch has many users
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
