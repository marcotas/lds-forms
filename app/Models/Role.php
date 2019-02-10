<?php

namespace App\Models;

use App\Traits\Models\HasRoleAndPermissions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasRoleAndPermissions;

    const ADMIN        = 'admin';
    const OWNER        = 'owner';
    const PROFESSIONAL = 'professional';
    const RECEPTIONIST = 'receptionist';
    const CASHIER      = 'cashier';
    const MANAGER      = 'manager';

    const ALL = [
        self::ADMIN,
        self::OWNER,
        self::PROFESSIONAL,
        self::RECEPTIONIST,
        self::CASHIER,
        self::MANAGER,
    ];

    protected $fillable = [
        'name',
        'description',
        'team_id',
    ];

    protected $appends = ['is_global', 'label'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function scopeNamed(Builder $builder, $name): Builder
    {
        return $builder->whereRaw('LOWER(name) = ?', strtolower($name));
    }

    public function scopeOfTeam(Builder $builder, $team): Builder
    {
        $teamId = is_object($team) ? $team->id : $team;

        return $builder->whereTeamId($teamId);
    }

    public function scopeGlobals(Builder $builder): Builder
    {
        return $builder->whereNull('team_id');
    }

    public function isGlobal()
    {
        return $this->team_id === null;
    }

    public function getIsGlobalAttribute()
    {
        return $this->isGlobal();
    }

    public function getLabelAttribute()
    {
        return __('permissions.roles.' . $this->attributes['name']);
    }
}
