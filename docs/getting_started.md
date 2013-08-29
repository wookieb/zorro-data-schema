# Getting started with Zorro Data Schema

[Creating schema](#creating-schema)
[Creating implementation details](#creating-implementation-details)
[Building schema object](#building-schema-object)
[Caching schema](#caching-schema)

<a name="emphasis"/>
## Creating schema

Schema is a simple structure that describes list of properties and their types.
Each property may have a default value which is used when value for that property will be null.
If it's ok to property to be null then mark it as "nullable" (see below) otherwise null will be converted to specified type.

Currently the only way to define schema in easy way is to use YAML files.
Each section (import, enum, classes) in schema is optional.

```yaml
import:
    - another_schema_file.yml

enums:
    EnumName:
        OPTION_1: 1
        OPTION_2: 2
        OPTION_3: 3
        # and so on

classes:
    User:
        properties:
            name: string # the easiest way to define property (no default value, not nullable)
            surname: # longer way
                type: string
            login:
                type: string
                default: "none" # instead of null, default value will be used
            email:
                type: string
                nullable: true # null is now allowed
            # "default" and "nullable" options are mutually exclusive
            # you cannot define both for property

    SuperUser:
        extend: User # this type will inherit all properties from "User" type
        properties:
            email: # but you can override them
                type: string
            position: string # or add new one
            ip: string
```

### List of types

List of available types and their representation in PHP

| type name        | representation in php          |
| ---------------- | ------------------------------ |
| byte             | integer (max value 127)        |
| binary           | string                         |
| bool, boolean    | boolean                        |
| collection<type> | array of elements of type      |
| date             | DateTime                       |
| double           | float                          |
| enum             | string, integer                |
| int16, integer16 | integer (max value 32767)      |
| int32, integer32 | integer (max value 2147483647) |
| int64, integer64 | integer (no max value)         |
| string           | string                         |


<a name="creating-implementation-details"/>
## Creating implementation details

As you see above, the schema is extremely simple and does not provide enought information about such things like:
* what is the name of class for user defined type?
* how can i get access to a property of object? accessors (setters/getters), reflection, ordinary property
* what is the name of each accessor?

This is the responsibility of "implementation details".
Currently the only way to define implementation details is a
Each section (import, global, classes) in implementation details file is optional.

```yaml
import:
    - another_implementation_file.yml

global:
    accessors:
        style: camel-case # enable accessors and their naming convention is "camel-case"

    namespaces:
        # list of names of namespaces where looking for classes that the name of class is same as type name
        - Name\Of\Namespace
        - Namespace\Of\Entities

# actually to this moment "implementation details" file is complete
# in the following section you can define very specific details for particular types
# each part (class, accessors, properties) of type details is optional
classes:
    User:
        class: Full\Class\Name
        accessors: disabled # generator of names of accessors is disabled (you need to set them manually)

        properties:
            name: userName # real location of value for property "name" is "userName" property
            # expanded version of mapping
            # name:
            #    as: userName

            # use setter "setLogin" to set value for property "login"
            # but do not use getter (fallback to reflection, ordinary property)
            login:
                setter: setLogin

            # do not use setter (fallback to reflection, ordinary property)
            # use getter "setSurname" to retrieve value for property "surname"
            surname:
                getter: getSurname

            # use setter (setEmail) and getter (getEmail) to handle property "email"
            email:
                getter: getEmail
                setter: setEmail

    SuperUser:
        # it's possible to set naming convention only for particular type
        accessors:
            style: underscore # now each accessor name will be generated automatically using "underscore" name convention
        properties:
            # use setter (set_position_name) and getter (get_position_name) to handle "position" property
            position: positionName

            # use setter (setIpNumber) and getter (get_ip_number)
            ip:
                setter: setIpNumber

            # you can even disable one of accessors
            # do not use setter but use getter (get_ip)
            # ip:
            #   setter:

            # properties that are not specified here will use default options of type
            # "email" property will have the following settings:
            # use setter (set_email) and getter (get_email)
```

<a name="building-schema-object"/>
## Building schema object

It's time to build up our schema object

```php
use Symfony\Component\Config\FileLocator;
use Wookieb\ZorroDataSchema\Loader\YamlLoader;
use Wookieb\ZorroDataSchema\Schema\Builder\SchemaBuilder;

$builder = new SchemaBuilder();
$locator = new FileLocator(__DIR__);
$builder->registerLoader(new YamlLoader($locator));

// load schema file
$builder->loadSchema('schema.yml');

// load implementation file
$builder->loadImplementation('implementation.yml');

// link them together
$schema = $builder->build();

// USAGE

// create instance of object Full\Class\Name from array
$object = $schema->getType('User')->create(array( ... ))

// convert object to array
$array = $schema->getType('User')->extract($object);
```

<a name="caching-schema"/>
## Caching schema

Once you have a schema object you can easily cache it in the file.
The class SchemaCache will be your friend in such situation.

```php
$cache = new SchemaCache(__DIR__.'/cache/schema');
// checks whether all the resources (schema and implementation files) were modified
if (!$cache->isFresh()) {
    // build schema
    $cache->write($schema, $builder->getResources());
} else {
    $schema = $cache->get();
}
```
