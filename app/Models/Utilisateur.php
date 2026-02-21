<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $table = 'UTI_Utilisateur';
    protected $primaryKey = 'UTI_ID';
    protected $dates = ['UTI_Cree_Dte', 'UTI_Modif_Dte', 'UTI_Suppr_Dte'];
    const CREATED_AT = 'UTI_Cree_Dte';
    const UPDATED_AT = 'UTI_Modif_Dte';
    const DELETED_AT = 'UTI_Suppr_Dte';
    protected $fillable = [
        'UTI_Nom',
        'UTI_Email',
        'UTI_Password',
        'UTI_Role',
        'UTI_Actif',
        'UTI_Login_Token',
        'UTI_IP_Restriction',
    ];

    protected $casts = [
        'UTI_IP_Restriction' => 'boolean',
    ];
    protected $hidden = ['UTI_Password'];

    public static function boot()
    {
        static::creating(function ($model) {
            if (Auth()->user()) $model->attributes['UTI_Cree_UID'] = Auth()->user()->getKey();
        });
        static::saving(function ($model) {
            if (Auth()->user()) $model->attributes['UTI_Modif_UID'] = Auth()->user()->getKey();
        });
        parent::boot();
    }

    public function getAuthPassword()
    {
        return $this->UTI_Password;
    }

    public function sessions()
    {
        return $this->hasMany(WorkSession::class, 'WRK_UTI_ID', 'UTI_ID');
    }

    public function activeSession()
    {
        return $this->hasOne(WorkSession::class, 'WRK_UTI_ID', 'UTI_ID')
            ->whereNull('WRK_Dte_Heure_Fin');
    }

    public function isAdmin(): bool
    {
        return $this->UTI_Role === 'admin';
    }
}
