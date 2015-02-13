<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Monitor extends Softone {

    function __construct() {
        parent::__construct();
    }

    function retrieveProductFields() {
        $result = $this->getBrowserInfo("ITEM");
        $out = array();
        foreach ($result->fields as $field) {
            if ($i++ > 1) {
                $out[] = str_replace(".", "_", strtolower($field->name));
            }
        }
        return $out;
        //return $result->fields;
    }

    function retrieveProducts() {
        $result = $this->getBrowser("ITEM");
        return $result->rows;
    }

    function retrieveCustomerFields() {
        $result = $this->getBrowserInfo("CUSTOMER");
        $out = array();
        foreach ($result->fields as $field) {
            if ($i++ > 1) {
                $out[] = str_replace(".", "_", strtolower($field->name));
            }
        }
        return $out;
    }

    function retrieveCustomers() {
        $result = $this->getBrowser("CUSTOMER");
        return $result->rows;
    }

}
