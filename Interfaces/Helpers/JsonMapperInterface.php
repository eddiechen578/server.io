<?php

namespace Interfaces\Helpers;


interface JsonMapperInterface
{

    function map($json);

    public function mapArray($json, $array, $class = null);
}
