<?php 
namespace Olm\Perseo\Contracts;

interface PropertyManager {
	public function get($name, $default = null, $useCache = true);
}