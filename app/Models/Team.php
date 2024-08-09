<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Filament\Models\Contracts\HasCurrentTenantLabel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model implements HasCurrentTenantLabel
{
    use HasFactory;

    protected $guarded = [];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function getCurrentTenantLabel(): string
    {
        return 'Current Company';
    }

    /**
     * A team can have many branches
     */
    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }
}
