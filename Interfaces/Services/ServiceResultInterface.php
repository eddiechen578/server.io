<?php

namespace Interfaces\Services;

interface ServiceResultInterface
{
    function getData();

    function setData($dataObject);

    function isSuccess();

    function getErrors();

    function getInputErrors();

    function addError($errorString);

    function addInputError($field, $errorString);

    function serialize();
}
