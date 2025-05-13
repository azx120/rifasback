<?php
namespace App\Traits;

use Illuminate\Support\Str;

trait UuidModel {
	/**
	 * Agrega el uuid al id del modelo
	 *
	 * @return void
	 **/
	static public function bootUuidModel() 
	{
		static::creating(function ($modelo) {
			# Esto es en caso de que se corrar seeds
			if (is_null($modelo->id)) {
				$modelo->id = (string) Str::orderedUuid();
			}
		});
	}

	public function initializeUuidModel() 
	{
		$this->setKeyType('string');
		$this->setIncrementing(false);
	}
}