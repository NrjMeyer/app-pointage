<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Rennokki\QueryCache\Traits\QueryCacheable;
// use Spatie\Activitylog\Traits\LogsActivity;

class Zvil extends Model
{
	protected $table		 = 'ZVIL_Ville';
	protected $primaryKey	 = 'ZVIL_ID';
	const _PRE				 = 'ZVIL';
	const CREATED_AT		 = 'ZVIL_Cree_Dte';
	const UPDATED_AT		 = 'ZVIL_Modif_Dte';
	const DELETED_AT		 = 'ZVIL_Suppr_Dte';
	public $timestamps = true;
	// use SoftDeletes;
	
	// use QueryCacheable;
	// public $cacheFor = 300; // 5 minutes
	// protected static $flushCacheOnUpdate = true;
	
	// use LogsActivity;
	// protected static $logName = 'models';
	// protected static $logFillable = true;
	// protected static $logOnlyDirty = true;
	
	protected $fillable = [
		self::_PRE.'_ZDPT_Code',
		self::_PRE.'_Code_Postal',
		self::_PRE.'_Nom',
		self::_PRE.'_Nom_Reel',
		self::_PRE.'_Nom_Simple',
		self::_PRE.'_Slug',
		self::_PRE.'_VIL_ID',
		self::_PRE.'_Notes',
	];
	protected $hidden = [
		
	];
	protected $casts = [
		self::_PRE.'_VIL_ID' => 'integer',
		
	];
	
	
	public static function boot() {
		static::creating(function ($model) {
			if (Auth()->user()) $model->attributes[self::_PRE.'_Cree_UID'] = Auth()->user()->getKey();
		});
		static::saving(function ($model) {
			if (Auth()->user()) $model->attributes[self::_PRE.'_Modif_UID'] = Auth()->user()->getKey();
			// if($model[self::_PRE.'_Ordre'] <= 0) {
				// $model[self::_PRE.'_Ordre'] =  self::with(self::_PRE.'_Ordre')->max(self::_PRE.'_Ordre') + 10;
			// }
		});
		parent::boot();
	}
	
	public function scopeBase($query) {
		return $query;
	}
	
	public function scopeOrdered($query) {
		return $query->orderBy('ZVIL_Nom', 'ASC');
	}
	public function scopeGetOrdered($query) {
		return $this->scopeOrdered($query)->get();
	}
	
	
	public function scopeParZdpt($query, $sCodeZdpt) {
		return $query->where('ZVIL_ZDPT_Code', $sCodeZdpt);
	}
	public function scopeParCp($query, $sCp) {
		return $query->where('ZVIL_Code_Postal', $sCp);
	}
	
	public function scopeParCpDebut($query, $sCp) {
		return $query->where('ZVIL_Code_Postal', 'LIKE', $sCp.'%');
	}
	
	
	
	
	
	
	
	
	
	
	
	/* ****************
		Scopes recherche
	**************** */
	
	// Integration auto de la recherche
	public function scopeAvecFiltresAuto($query, $arrFiltres) {
		foreach($arrFiltres as $findKey => $findVal) {
			if (method_exists($this, 'scopeFind'.$findKey) ) {
				$findFunc = 'find'.$findKey;
				$query->$findFunc($findVal);
			}
		}
		return $query;
	}
	
	// Scopes de recherche
	public function scopeFindNomVil($query, $sVal = '') {
		if (empty($sVal)) return $query;
		return $query->where('ZVIL_Nom', 'LIKE', '%'.$sVal.'%');
	}
	
	
	
	
	
	
	
	
	
	
	
	/* ****************
		Attributes
	**************** */
	
	public function getLibelleAttribute() {
		if (!isset($this->attributes[self::_PRE.'_Nom'])) return '';
		return $this->attributes[self::_PRE.'_Nom'];
	}
	public function setLibelleAttribute($value) {
		$this->attributes[self::_PRE.'_Nom'] = $value;
	}
	public static function getDropDownList() {
		return static::Ordered()->get()->pluck('ZVIL_Nom_Reel', self::_PRE.'_ID')->toArray();
	}
	
	
	
	
	
	
	/****************************************************************/
		public function get1Zdpt() {
			return $this->belongsTo('App\Models\Zdpt', 'ZVIL_ZDPT_Code', 'ZDPT_Code');
		}
			public function getTheZdpt() {
				$belongsData = $this->get1Zdpt();
				if(empty( $this->getAttribute($belongsData->getForeignKeyName()) ) || is_null( $this->getAttribute($belongsData->getForeignKeyName()) )) {
					$result = new \App\Models\Zdpt;
				} else {
					$result = $belongsData->getResults();
				}
				return $result;
			}
			public function getZdptLibAttribute() {
				return $this->getTheZdpt()->Libelle;
			}
	/****************************************************************/
	
	
	
	
	
	
	
	
	/* ****************
		Relations N
	**************** */
	
	
	/* ****************
		Relations N à N
	**************** */
	
	
	
	
	
	
	
	
	
	
}


