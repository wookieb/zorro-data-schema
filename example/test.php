<?php
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Config\FileLocator;
use Wookieb\ZorroDataSchema\Loader\YamlLoader;
use Wookieb\ZorroDataSchema\Schema\Builder\SchemaBuilder;
use Wookieb\ZorroDataSchema\Schema\SchemaCache;

class User
{
    private $name;
    private $registrationDate;
    private $status;
    private $addresses;
}

class Address
{
    private $street;
    private $city;

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }
}


$cache = new SchemaCache(__DIR__.'/cache/schema');
if (!$cache->isFresh()) {
    $locator = new FileLocator(array(__DIR__));

    $builder = new SchemaBuilder();
    $builder->registerLoader(new YamlLoader($locator));

    $builder->loadSchema('schema.yml');
    $builder->loadImplementation('implementation.yml');

    $schema = $builder->build();

    $cache->write($schema, $builder->getResources());
} else {
    $schema = $cache->get();
}

$data = array(
    'name' => 'Łukasz',
    'registrationDate' => 1123818123,
    'status' => 'ACTIVE',
    'addresses' => array(
        array('street' => 'Sportowa', 'city' => 'Gdynia'),
        array('street' => 'Zamkowa', 'city' => 'Gdańsk')
    )
);

$type = $schema->getType('User');
$start = microtime(true);
for ($i=0; $i<1000; $i++) {
    $type->create($data);
}
echo microtime(true) - $start;
die();
echo 'Raw data'.PHP_EOL;
print_r($data);
echo PHP_EOL.'Transformed to "User" type:'.PHP_EOL;
$object = $schema->getType('User')->create($data);
print_r($object);
echo PHP_EOL.'Extracted data from object of "User" type:'.PHP_EOL;
print_r($schema->getType('User')->extract($object));

