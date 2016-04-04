<?php 

namespace Olm\Perseo\Facades;

use Illuminate\Support\Facades\Facade;

class Operation extends Facade
{
	protected static function getFacadeAccessor() { 
		return 'operation'; 
	}
} 