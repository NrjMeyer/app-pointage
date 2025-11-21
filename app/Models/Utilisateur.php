<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use Notifiable,SoftDeletes;

    protected $table = 'UTI_Utilisateur';
    protected $primaryKey = 'UTI_ID';
    protected $dates = ['UTI_Cree_Dte','UTI_Modif_Dte','UTI_Suppr_Dte'];
    const DELETED_AT = 'UTI_Suppr_Dte';
    protected $fillable = [
        'UTI_Nom',
        'UTI_Email',
        'UTI_Password',
        'UTI_Role',
        'UTI_Actif',
        'UTI_Login_Token',
    ];
    protected $hidden = ['UTI_Password'];

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
