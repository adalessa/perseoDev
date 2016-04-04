<?php 
namespace Olm\Perseo\Implementation;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Olm\Perseo\Contracts\PropertyManager;
use Olm\Perseo\Contracts\EloquentPropertyModel;

class EloquentPropertyManager implements PropertyManager
{
	const PROPETY_CACHE_PREFFIX = 'perseo_property';

    protected $eloquentModel;

    public function __construct(EloquentPropertyModel $eloquentModel)
    {
    	if (! $eloquentModel instanceof Model) {
    		throw new \Exception("Invalid model bind to EloquentPropertyModel", 1);
    	}
        $this->eloquentModel = $eloquentModel;
    }
    public function get($name, $default = null, $useCache = true)
    {
    	$model = $this->eloquentModel;
    	if (!$useCache) {
    		Cache::forget(self::PROPETY_CACHE_PREFFIX.$name);
    	}

        $property = Cache::rememberForever(self::PROPETY_CACHE_PREFFIX.$name, function () use ($default, $name, $model) {
        	$prop = $model->where('name', '=', $name)->first();
            $value = $prop === null ? $default : $prop->value;
            return $value;
        });

        return $property;
    }
}
