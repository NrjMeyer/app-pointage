<?php

namespace App\Models;

use App\Models\Traits\Blameable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tra extends Model
{
	use Blameable, HasFactory;

	// ========================================
	// PROPRIÉTÉS
	// ========================================
	protected $table = 'TRA_Transactions';
	protected $primaryKey = 'TRA_ID';
	const _PRE = 'TRA';
	const CREATED_AT = 'TRA_Cree_Dte';
	const UPDATED_AT = 'TRA_Modif_Dte';
	public $timestamps = true;
	protected static $logName = 'models';
	protected static $logFillable = true;
	protected static $logOnlyDirty = true;

	protected $fillable = [
		'TRA_Fichier',
		'TRA_Dte',
		'TRA_Operation',
		'TRA_Mnt_Brut',
		'TRA_Mnt_Debit',
		'TRA_Mnt_Credit',
		
		'TRA_Est_Debit',
		'TRA_Est_Credit',
		'TRA_Notes',
		
		'TRA_Est_Rapproche',
		'TRA_Dte_Rapproche',
		
		'TRA_Detail',
		
	];



	protected $casts = [
		'TRA_Fichier' => 'string',
		'TRA_Dte' => 'date',
		'TRA_Operation' => 'string',
		'TRA_Mnt_Brut' => 'double',
		'TRA_Mnt_Debit' => 'double',
		'TRA_Mnt_Credit' => 'double',
		'TRA_Est_Debit' => 'boolean',
		'TRA_Est_Credit' => 'boolean',
		'TRA_Notes' => 'string',
		'TRA_Est_Rapproche' => 'boolean',
		'TRA_Dte_Rapproche' => 'datetime',
		'TRA_Detail' => 'string',
		
	];

	// Forcer les champs à être traités comme des dates (objet Carbon) par Laravel.
	protected $dates = [
		// À compléter
	];

	// Laravel chargera automatiquement des relations avec le modèle, sans avoir besoin d'appeler with() dans la query.
	// Utiliser $with alourdit un peu les requêtes
	protected $with = [
		// À compléter

	];

	// Ajouter automatiquement des attributs calculés (accessors) dans ton modèle JSON retourné.
	// $Lan->toArray() ou $lan->toJson() inclura automatiquement libelle dans les résultats.
	protected $appends = [

	];


	// ========================================
	// SCOPES
	// ========================================

	/**
	 * Scope : Base (aucune modification)
	 */
	public function scopeBase(Builder $query)
	{
		return $query;
	}

	/**
	 * Scope : Ordonné par ordre puis libellé
	 */
	public function scopeOrdered(Builder $query)
	{
		return $query
			->orderBy('TRA_Dte', 'DESC'); //            ->orderBy('ECI_Libelle', 'ASC')
	}

	/**
	 * Scope : Récupération directe ordonnée
	 */
	public function scopeGetOrdered(Builder $query)
	{
		return $this->scopeOrdered($query)->get();
	}

	/* ****************
		Scopes ordre
	**************** */

	public function scopeAvecOrdresAuto($query, $arrGet)
	{
		// recup param ordre
		$ordrePrm = $arrGet['ordre'] ?? 'defaut';
		// si pas précisé
		if ($ordrePrm == 'defaut') {
			return $query->Ordered();
		}
		if (method_exists($this, 'scopeOrderBy'.$ordrePrm)) {
			$orderFunc = 'orderBy'.$ordrePrm;
			$query->$orderFunc();
		}

		return $query;
	}

	/* ****************
		Scopes recherche
	**************** */

	// Integration auto de la recherche
	public function scopeAvecFiltresAuto($query, $arrFiltres, $bFilterRequired = false)
	{
		$bHasFilter = false;
		foreach ($arrFiltres as $findKey => $findVal) {
			if (method_exists($this, 'scopeFind'.$findKey)) {
				if ($bFilterRequired && ! empty($findVal)) {
					$bHasFilter = true;
				}
				$findFunc = 'find'.$findKey;
				$query->$findFunc($findVal);
			}
		}

		return $query;
	}




	// ========================================
	// ATTRIBUTS (ACCESSORS)
	// ========================================


	public function getLibelleAttribute()
	{
		return $this->TRA_Operation;
	}

	public function getRemarqueAttribute()
	{
		return $this->TRA_Detail;
	}

	// ========================================
	// STATIC LISTES
	// ========================================

	/**
	 * Liste déroulante : ID => Libelle
	 */
	public static function getDropDownList()
	{
		return static::ordered()
			->get()
			->pluck('Operation', self::_PRE.'_ID')
			->toArray();
	}


	/* ****************
		Actions
	**************** */



	// ========================================
	// RELATIONS 1→0 (belongsTo)
	// ========================================



	// ========================================
	// RELATIONS 1→N (hasMany)
	// ========================================


	// ========================================
	// RELATIONS N→N (belongsToMany)
	// ========================================

	// (Aucune relation N→N définie pour l'instant)

	
	/*************************************************/
	/*****				Morphs					 *****/
	/*************************************************/
	
	
	public function getNDoc() {
		return $this->morphMany('App\Models\Doc', 'get1Owner', 'DOC_OBJ_Model', 'DOC_OBJ_ID');
	}
	public function getNImg() {
		return $this->getNDoc()->estImage();
	}
	public function getNPdf() {
		return $this->getNDoc()->estPdf();
	}
	public function getNPasImg() {
		return $this->getNDoc()->estImage(false);
	}
	
	
}
