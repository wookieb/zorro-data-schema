Zorro Data Schema
=================
[![Build Status](https://travis-ci.org/wookieb/zorro-data-schema.png?branch=master)](https://travis-ci.org/wookieb/zorro-data-schema)
Tool to convert objects to arrays and vice versa according to data schema

Use cases
=========
* Retrieving and sending data to many environments
* REST API
* Serialization layer for json and other formats

Example
=======

Just run
```
php example/test.php
```

Example result
```
Raw data
Array
(
    [name] => Łukasz
    [registrationDate] => 1123818123
    [status] => ACTIVE
    [addresses] => Array
        (
            [0] => Array
                (
                    [street] => Sportowa
                    [city] => Gdynia
                )

            [1] => Array
                (
                    [street] => Zamkowa
                    [city] => Gdańsk
                )

        )

)

Transformed to "User" type:
User Object
(
    [name:User:private] => Łukasz
    [registrationDate:User:private] => DateTime Object
        (
            [date] => 2005-08-12 03:42:03
            [timezone_type] => 1
            [timezone] => +00:00
        )

    [status:User:private] => 1
    [addresses:User:private] => Array
        (
            [0] => Address Object
                (
                    [street:Address:private] => Sportowa
                    [city:Address:private] => Gdynia
                )

            [1] => Address Object
                (
                    [street:Address:private] => Zamkowa
                    [city:Address:private] => Gdańsk
                )

        )

)

Extracted data from object of "User" type:
Array
(
    [name] => Łukasz
    [registrationDate] => 2005-08-12T03:42:03+0000
    [status] => ACTIVE
    [addresses] => Array
        (
            [0] => Array
                (
                    [street] => Sportowa
                    [city] => Gdynia
                )

            [1] => Array
                (
                    [street] => Zamkowa
                    [city] => Gdańsk
                )

        )

)
```

Documentation
=============
[see documentation](docs)

Status
======
The API is experimental. Not tested on production yet.


TODO
====
* generator of classes
* "map" type
