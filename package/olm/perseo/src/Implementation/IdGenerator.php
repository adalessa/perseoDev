<?php 
namespace Olm\Perseo\Implementation;

use Config;
use Olm\Perseo\Contracts\PropertyManager;
use Olm\Perseo\Contracts\IdGenerator as IdGeneratorContract;

class IdGenerator implements IdGeneratorContract
{
	protected $propertyManager;

	public function __construct(PropertyManager $propertyManager)
	{
		$this->propertyManager = $propertyManager;
	}
    public function get()
    {
        return uniqid($this->propertyManager->get('guidprefix', 'perseo'), true);
    }
}
