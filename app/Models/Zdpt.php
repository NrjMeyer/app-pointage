<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Rennokki\QueryCache\Traits\QueryCacheable;
// use Spatie\Activitylog\Traits\LogsActivity;

class Zdpt extends Model
{
	protected $table		 = 'ZDPT_Departement';
	protected $primaryKey	 = 'ZDPT_ID';
	const _PRE				 = 'ZDPT';
	const CREATED_AT		 = 'ZDPT_Cree_Dte';
	const UPDATED_AT		 = 'ZDPT_Modif_Dte';
	const DELETED_AT		 = 'ZDPT_Suppr_Dte';
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
		'ZDPT_ZREG_Code',
		self::_PRE.'_Code',
		self::_PRE.'_Nom',
		self::_PRE.'_Slug',
		self::_PRE.'_Notes',
	];
	protected $hidden = [
		
	];
	protected $casts = [
		
	];
	
	
	public static function boot() {
		static::creating(function ($model) {
			if (Auth()->user()) $model->attributes[self::_PRE.'_Cree_UID'] = Auth()->user()->getKey();
		});
		static::saving(function ($model) {
			if (Auth()->user()) $model->attributes[self::_PRE.'_Modif_UID'] = Auth()->user()->getKey();
			if($model[self::_PRE.'_Ordre'] <= 0) {
				$model[self::_PRE.'_Ordre'] =  self::with(self::_PRE.'_Ordre')->max(self::_PRE.'_Ordre') + 10;
			}
		});
		parent::boot();
	}
	
	public function scopeBase($query) {
		return $query;
	}
	
	public function scopeOrdered($query) {
		return $query->orderBy(self::_PRE.'_Nom', 'ASC');
	}
	public function scopeGetOrdered($query) {
		return $this->scopeOrdered($query)->get();
	}
	
	
	
	
	public function scopeEstMetropole($query, $bVal = true) {
		$query->whereHas('get1Zreg', function($query) use ($bVal) {
			$query->where('ZREG_Est_Metropole', $bVal);
		});
	}
	
	
	
	
	
	public function getLibelleAttribute() {
		if (!isset($this->attributes[self::_PRE.'_Nom'])) return '';
		return $this->attributes[self::_PRE.'_Nom'];
	}
	
	public function getNumLibelleAttribute() {
		if (!isset($this->attributes[self::_PRE.'_Nom'])) return '';
		
		return $this->attributes[self::_PRE.'_Code'] .' - ' . $this->attributes[self::_PRE.'_Nom'];
		
	}
	
	public function setLibelleAttribute($value) {
		$this->attributes[self::_PRE.'_Nom'] = $value;
	}
	public static function getDropDownList() {
		return static::base()->orderBy('ZDPT_Code')->orderBy('ZDPT_Nom')->get()->pluck('NumLibelle', self::_PRE.'_ID')->toArray();
	}
	
	
	public static function getCodeDropDownList() {
		return static::base()->orderBy('ZDPT_Code')->orderBy('ZDPT_Nom')->get()->pluck('NumLibelle', 'ZDPT_Code')->toArray();
	}
	
	
	
	
	public static function getValueLabelList() {
		$aRes = [];
		$dataLst = static::Ordered()->get();
		foreach ( $dataLst as $data ) {
			$aRes[$data->ZDPT_Code] = [
				'value' => $data->ZDPT_Code,
				'label' => $data->libelle,
			];
		}
		return $aRes;
	}
	
	
	
	
	/****************************************************************/
		public function get1Zreg() {
			return $this->belongsTo('App\Models\Zreg', 'ZDPT_ZREG_Code', 'ZREG_Code');
		}
			public function getTheZreg() {
				$belongsData = $this->get1Zreg();
				if(empty( $this->getAttribute($belongsData->getForeignKeyName()) ) || is_null( $this->getAttribute($belongsData->getForeignKeyName()) )) {
					$result = new \App\Models\Zreg;
				} else {
					$result = $belongsData->getResults();
				}
				return $result;
			}
			public function getZregLibAttribute() {
				return $this->getTheZreg()->Libelle;
			}
	/****************************************************************/
	
	
	
	
	
	
	
	
	/* ****************
		Relations N
	**************** */
	
	public function getNLptdt() {
		return $this->hasMany('App\Models\Lptdt', 'LPTDT_ZDPT_Code', 'ZDPT_Code');
	}
	
	/* ****************
		Relations N à N
	**************** */
	
	
	public function getNNPtr() {
		return $this->hasManyThrough(
			'App\Models\Ptr',		// T cible N
			'App\Models\Lptdt',		// T liaison
			'LPTDT_ZDPT_Code',		// Id T locale sur liaison
			'PTR_ID',				// Id T cible
			'ZDPT_Code',				// Id T locale
			'LPTDT_PTR_ID'			// Id T cible sur liaison
		);
	}
	
	
	
	
	
	
	
	
}


