<?php
require_once '../vendor/autoload.php';

use Symfony\Component\Config\FileLocator;
use Wookieb\ZorroDataSchema\Loader\YamlLoader;
use Wookieb\ZorroDataSchema\Schema\Builder\SchemaBuilder;
use Wookieb\ZorroDataSchema\Schema\SchemaCache;

class User
{
    private $name;
    private $lastname;
    private $address;

    public function setMyName($name)
    {
        $this->name = 'My name is '.$name;
    }

    public function getMyName()
    {
        return substr($this->name, 0, 11);
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }
}

class Address
{
    private $street;
    private $city;
}

class SuperUser extends User
{
    private $username;
}


$cache = new SchemaCache('cache/schema');
if (!$cache->isFresh()) {
    $locator = new FileLocator(array('.'));

    $builder = new \Wookieb\ZorroDataSchema\Schema\Builder\SchemaBuilder();
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
    'lastname' => 'Kużyński',
    'address' => array(
        'street' => 'Sportowa',
        'city' => 'Gdynia'
    ),
    'registrationDate' => 1123818123
);

echo 'Raw data'.PHP_EOL;
print_r($data);
echo PHP_EOL.'Transformed to "User" type:'.PHP_EOL;
$object = $schema->getType('User')->create($data);
print_r($object);
echo PHP_EOL.'Extracted data from object of "User" type:'.PHP_EOL;
print_r($schema->getType('User')->extract($object));

