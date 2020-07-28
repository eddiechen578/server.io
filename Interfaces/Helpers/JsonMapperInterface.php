<?php

namespace Interfaces\Helpers;


interface JsonMapperInterface
{

    function map($json, $object);

    public function mapArray($json, $array, $class = null);
}
