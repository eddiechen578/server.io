<?php

namespace App\Helpers;


class JsonMapper implements \Interfaces\Helpers\JsonMapperInterface
{
    private $arInspectedClasses = array();

    public function map($json, $object)
    {
        $strClassName = get_class($object);
        $rc = new \ReflectionClass($object);

        $strNs = $rc->getNamespaceName();

        foreach ($json as $key => $jvalue) {
            if (!isset($this->arInspectedClasses[$strClassName][$key])) {
                $this->arInspectedClasses[$strClassName][$key] = $this->inspectProperty($rc, $key);
            }

            list($hasProperty, $isSettable, $type, $setter) = $this->arInspectedClasses[$strClassName][$key];

            if (!$hasProperty || !$isSettable) {
                //Ignore json property
                continue;
            }

            if ($type === null) {
                $this->setProperty($object, $key, $jvalue, $setter);
                continue;
            }else if ($this->isSimpleType($type)) {
                $this->setProperty($object, $key, $jvalue, $setter);
                continue;
            }
        }

        return $object;
    }

    public function mapArray($json, $array, $class = null)
    {

    }

    protected function isSimpleType($type)
    {
        return $type == 'string'
            || $type == 'boolean' || $type == 'bool'
            || $type == 'integer' || $type == 'int'
            || $type == 'float' || $type == 'array' || $type == 'object';
    }

    protected function inspectProperty(\ReflectionClass $rc, $name)
    {
       $setter = 'set' . ucfirst($name);

       if(!$rc->hasMethod($setter)){
           if($rc->hasProperty($name)){
               //no setter, private property
               return array(true, false, null, null);
           }
           //no setter, no property
           return array(false, false, null, null);
       }

        $rmeth = $rc->getMethod($setter);
        if (!$rmeth->isPublic()) {
            return array(true, false, null, null);
        }

        $rparams = $rmeth->getParameters();

        if (count($rparams) > 0) {
            $pclass = $rparams[0]->getClass();
            if ($pclass !== null) {
                return array(true, true, $pclass->getName(), $rmeth);
            }
        }

        $docblock    = $rmeth->getDocComment();
        $annotations = $this->parseAnnotations($docblock);

        if (!isset($annotations['param'][0])) {
            return array(true, true, null, $rmeth);
        }

        list($type) = explode(' ', trim($annotations['param'][0]));

        return array(true, true, $type, $rmeth);
    }


    protected function setProperty($object, $name, $value, $setter)
    {

        $rc = new \ReflectionClass($object);
        if ($setter === null && $rc->getProperty($name)->isPublic()) {
            $object->$name = $value;
        } elseif ($setter && $setter->isPublic()) {
            $object->{$setter->getName()}($value);
        }else{
            \App\Models\Log\log::setException(\lib\exception::LEVEL_ERROR, 'Property {class}::{property} cannot be set from outside');
        }
//        else {
//
//            $this->log(
//                'error',
//                'Property {class}::{property} cannot be set from outside',
//                array('property' => $name, 'class' => get_class($object))
//            );
//        }
    }

    protected static function parseAnnotations($docblock)
    {
        $annotations = array();

        $docblock = substr($docblock, 3, -2);

        $re = '/@(?P<name>[A-Za-z_-]+)(?:[ \t]+(?P<value>.*?))?[ \t]*\r?$/m';
        if (preg_match_all($re, $docblock, $matches)) {
            $numMatches = count($matches[0]);

            for ($i = 0; $i < $numMatches; ++$i) {
                $annotations[$matches['name'][$i]][] = $matches['value'][$i];
            }
        }

        return $annotations;
    }
}
