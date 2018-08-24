<?php

abstract class Persistents_Core {
    
    protected $isValidate;
    protected $errors;
    protected $error_fields;
    
    function __construct() {
        $this->isValidate = true;
        $this->errors = array();
        $this->error_fields = array();
    }
    
    function isValidate() {
        return $this->isValidate;
    }
    
    function setValidate($validate) {
        $this->isValidate = $validate;
    }
    
    function getErrors() {
        return $this->errors;
    }
    
    function setErrors($error) {
        $this->errors[] = $error;
    }
    
    function getErrorFields() {
        return $this->error_fields;
    }
    
    function setErrorFields($error_fields) {
        $this->error_fields = $error_fields;
    }
    
    abstract function getClassName();
}