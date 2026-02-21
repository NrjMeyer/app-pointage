<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkSession extends Model
{
	use SoftDeletes;

	protected $table = 'WRK_Work_Sessions';
	protected $primaryKey = 'WRK_ID';
	const CREATED_AT = 'WRK_Cree_Dte';
	const UPDATED_AT = 'WRK_Modif_Dte';
	const DELETED_AT = 'WRK_Suppr_Dte';

	protected $fillable = [
		'WRK_UTI_ID',
		'WRK_Dte_Heure_Deb',
		'WRK_Dte_Heure_Fin',
		'WRK_Duree_Minutes',
		'WRK_Type_Cloture',
		'WRK_Note',
		'WRK_Est_Cloture_Auto',
		'WRK_IP_Debut',
		'WRK_IP_Fin',
	];


	protected $cast = [
		
		'WRK_UTI_ID' => 'integer',
		'WRK_Dte_Heure_Deb' => 'datetime',
		'WRK_Dte_Heure_Fin' => 'datetime',
		'WRK_Duree_Minutes' => 'integer',
		'WRK_Type_Cloture' => 'string',
		'WRK_Note' => 'string',
		'WRK_Est_Cloture_Auto' => 'boolean',
		'WRK_IP_Debut' => 'string',
		'WRK_IP_Fin' => 'string',
		
	];

	public static function boot() {
		static::creating(function ($model) {
			if (Auth()->user()) $model->attributes['WRK_Cree_UID'] = Auth()->user()->getKey();
		});
		static::saving(function ($model) {
			if (Auth()->user()) $model->attributes['WRK_Modif_UID'] = Auth()->user()->getKey();
		});
		static::deleting(function ($model) {
			if (Auth()->user()) $model->attributes['WRK_Suppr_UID'] = Auth()->user()->getKey();
		});
		
		
		parent::boot();
	}
	
	public function utilisateur()
	{
		return $this->belongsTo(Utilisateur::class, 'WRK_UTI_ID', 'UTI_ID');
	}
}
