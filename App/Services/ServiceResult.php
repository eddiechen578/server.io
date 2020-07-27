<?php

namespace App\Services;

class ServiceResult implements \Interfaces\Services\ServiceResultInterface, \JsonSerializable
{

    private $errors;

    public function addError($errorString)
    {
        if(!isset($this->errors)) {
            $this->errors = array();
        }
        $this->errors[] = $errorString;
    }

    private $inputErrors;

    public function addInputError($field, $errorString)
    {
        if(!isset($this->inputErrors)) {
            $this->inputErrors = array();
        }

        $this->inputErrors[$field] = $errorString;
    }

    private $data;

    public function getData()
    {
        return $this->data;
    }

    public function setData($dataObject)
    {
        $this->data = $dataObject;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getInputErrors()
    {
        return $this->inputErrors;
    }

    public function isSuccess()
    {
        return (!isset($this->inputErrors) || count($this->inputErrors) == 0) &&
            (!isset($this->errors) || count($this->errors) == 0);
    }

    public function serialize()
    {
        return json_encode($this);
    }

    public function jsonSerialize()
    {
        $arr = array();
        $arr["errors"] = array();
        if($this->getErrors()) {
            foreach($this->getErrors() as $error) {
                $arr["errors"][] = $error;
            }
        }
        $arr["inputErrors"] = array();
        if($this->getInputErrors()) {

            foreach($this->getInputErrors() as $key => $value) {
                $arr["inputErrors"][$key] = $value;
            }
        }

        $arr["isSuccess"] = $this->isSuccess();

        $arr["data"] = $this->data;
        return $arr;
    }
}
