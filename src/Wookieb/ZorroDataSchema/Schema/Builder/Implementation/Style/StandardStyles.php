<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\Implementation\Style;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class StandardStyles extends Styles
{
    const CAMEL_CASE = 'camel-case';
    const CAMEL_CASE_WITHOUT_PREFIX = 'camel-case-without-prefix';
    const UNDERSCORE = 'underscore';
    const UNDERSCORE_WITHOUT_PREFIX = 'underscore-without-prefix';

    public function __construct()
    {
        $this->registerStyle(self::CAMEL_CASE, new CamelCaseStyle());
        $this->registerStyle(self::CAMEL_CASE_WITHOUT_PREFIX, new CamelCaseStyle(false));

        $this->registerStyle(self::UNDERSCORE, new UnderscoreStyle());
        $this->registerStyle(self::UNDERSCORE_WITHOUT_PREFIX, new UnderscoreStyle(false));
    }
}