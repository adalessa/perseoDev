<?php 
namespace Olm\Perseo\Implementation;

use Config;
use Olm\Perseo\Contracts\PropertyManager;

class ConfigPropertyManager implements PropertyManager
{
    public function get($name, $default = null, $useCache = true)
    {
        return Config::get('perseo.'.$name, $default);
    }
}
