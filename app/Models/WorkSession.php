<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkSession extends Model
{
    use SoftDeletes;

    protected $table = 'WRK_Work_Sessions';
    protected $primaryKey = 'WRK_ID';
    protected $dates = ['WRK_Dte_Heure_Deb','WRK_Dte_Heure_Fin','WRK_Cree_Dte','WRK_Modif_Dte','WRK_Suppr_Dte'];
    const DELETED_AT = 'WRK_Suppr_Dte';

    protected $fillable = [
        'WRK_UTI_ID',
        'WRK_Dte_Heure_Deb',
        'WRK_Dte_Heure_Fin',
        'WRK_Duree_Minutes',
        'WRK_Type_Cloture',
        'WRK_Note',
        'WRK_Est_Cloture_Auto',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'WRK_UTI_ID', 'UTI_ID');
    }
}
