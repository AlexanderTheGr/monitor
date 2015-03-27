<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Softone {

    var $authenticateClientID;
    var $loginClientID;
    var $appId = 156;
    var $username = 'fastweb';
    var $password = 'fastweb';
    var $requerstUrl = 'http://kovaios.oncloud.gr/s1services';

    function __construct() {
        session_start();
        $loginData = $this->login();
        $this->authenticate($loginData);
    }

    function login() {
        
        if ($_SESSION["logindata"]) {
            //print_r($_SESSION["logindata"]);
            $data = $_SESSION["logindata"];
            $this->loginClientID = $data->clientID;
            return $data;  
        }
        
 
        $params = array(
            "service" => "login",
            'username' => $this->username,
            'password' => $this->password,
            'appId' => $this->appId);
        $data = $this->doRequest($params);
        $this->loginClientID = $data->clientID;
        $_SESSION["logindata"] = $data;
        return $data;
    }

    function authenticate($data) {
        
        if ($_SESSION["authenticatedata"]) {
            //print_r($_SESSION["authenticatedata"]);
            $data = $_SESSION["authenticatedata"];
            $this->authenticateClientID = $data->clientID;
            return $data;
        }


        $this->loginClientID = $data->clientID;
        $params = array(
            "service" => "authenticate",
            "clientID" => $data->clientID,
            "COMPANY" => $data->objs[0]->COMPANY,
            "BRANCH" => $data->objs[0]->BRANCH,
            "MODULE" => $data->objs[0]->MODULE,
            "REFID" => $data->objs[0]->REFID,
                //"USERID": $data->objs->BRANCH,		
        );
        $data = $this->doRequest($params);
        $this->authenticateClientID = $data->clientID;
        $_SESSION["authenticatedata"] = $data;

        return $data;
    }

    function changePassword($new) {
        $params = array(
            "service" => "changePassword",
            "clientID" => $this->authenticateClientID,
            'appId' => $this->appId,
            'OLD' => $this->password,
            'NEW' => $new);
        $data = $this->doRequest($params);
        return $data;
    }

    function getObjects() {
        $params = array(
            "service" => "getObjects",
            "clientID" => $this->authenticateClientID,
            "appId" => $this->appId,
        );
        return $this->doRequest($params);
    }

    function getObjectTables($obj) {
        $params = array(
            "service" => "getObjectTables",
            "clientID" => $this->authenticateClientID,
            "appId" => $this->appId,
            "OBJECT" => $obj,
        );
        return $this->doRequest($params);
    }

    function getTableFields($obj, $tbl) {
        $params = array(
            "service" => "getTableFields",
            "clientID" => $this->authenticateClientID,
            "appId" => $this->appId,
            "OBJECT" => $obj,
            "TABLE" => $tbl,
        );
        return $this->doRequest($params);
    }

    function getDialog($obj, $list = "") {
        $params = array(
            "service" => "getDialog",
            "clientID" => $this->authenticateClientID,
            "appId" => $this->appId,
            "OBJECT" => $obj,
            "LIST" => $list,
        );
        return $this->doRequest($params);
    }

    function getFormDesign($form = "") {
        $params = array(
            "service" => "getFormDesign",
            "clientID" => $this->authenticateClientID,
            "appId" => $this->appId,
            "OBJECT" => $obj,
            "FORM" => $form,
        );
        return $this->doRequest($params);
    }

    function getBrowserInfo($obj, $list = "", $filters = "") {
        $params = array(
            "service" => "getBrowserInfo",
            "clientID" => $this->authenticateClientID,
            "appId" => $this->appId,
            "OBJECT" => $obj,
            "LIST" => $list,
            "FILTERS" => $filters,
        );
        //print_r($params);
        return $this->doRequest($params);
    }

    function getBrowserData($data) {
        $params = array(
            "service" => "getBrowserData",
            "clientID" => $this->authenticateClientID,
            "appId" => $this->appId,
            "reqID" => $data->reqID,
            "START" => 0,
            "LIMIT" => 10
        );
        $out = $this->doRequest($params);
        return $out;
    }

    function getBrowser($obj, $list = "", $filters = "") {
        $browserInfo = $this->getBrowserInfo($obj, $list, $filters);
        return $this->getBrowserData($browserInfo);
    }

    function getReportInfo($obj) {
        $params = array(
            "service" => "getReportInfo",
            "clientID" => $this->authenticateClientID,
            "appId" => $this->appId,
            "OBJECT" => $obj,
            "LIST" => "",
            "FILTERS" => "",
        );

        return $this->doRequest($params);
    }

    function getReportData($data, $pagenum = 1) {
        $params = array(
            "service" => "getReportData",
            "clientID" => $this->authenticateClientID,
            "appId" => $this->appId,
            "reqID" => $data->reqID,
            "PAGENUM" => $pagenum
        );
        return $this->doRequest($params);
    }

    function getReport($obj, $pagenum = 1) {
        $reportInfo = $this->getReportInfo($obj);
        return getReportData($reportInfo, $pagenum = 1);
    }

    function getData($obj, $key, $form = "", $locateinfo = "") {
        $params = array(
            "service" => "getData",
            "clientID" => $this->authenticateClientID,
            "appId" => $this->appId,
            "OBJECT" => $obj,
            "FORM" => $form,
            "KEY" => $key,
            "LOCATEINFO" => $locateinfo
        );
        return $this->doRequest($params);
    }

    function setData($data, $obj, $key = "") {
        $params = array(
            "service" => "setData",
            "clientID" => $this->authenticateClientID,
            "appId" => $this->appId,
            "OBJECT" => $obj,
            "KEY" => $key,
            "data" => $data
        );
        //echo json_encode($params);
        return $this->doRequest($params);
    }

    function calculate($data, $obj, $form = "", $key = "", $locateinfo = "ASSLINES:ASDCODE,DISC1PRC,LINENUM,LINEVAL,MTRL,MTRL_ASSET_CODE,MTRL_ASSET_NAME,NAME,PRICE,QTY1,VAT;ITELINES:DISC1PRC,LINENUM,LINEVAL,MTRL,MTRL_ITEM_CODE,MTRL_ITEM_CODE1,MTRL_ITEM_CODE2,MTRL_ITEM_NAME,MTRL_ITEM_NAME1,PRICE,QTY1;MTRDOC:QTY;SALDOC:BUSUNITS,CMPFINCODE,COMMENTS1,DISC1PRC,DISC1VAL,DISC2PRC,EXPN,FINDOC,FINDOC_SALMTRDOC_FINDOC,FINDOC_SALMTRDOC_WHOUSE,FINSTATES,NETAMNT,PAYMENT,PRJC,PRJC_PRJC_CODE,PRJC_PRJC_NAME,REMARKS,SALESMAN,SALESMAN_PRSNIN_CODE,SALESMAN_PRSNIN_NAME,SALESMAN_PRSNIN_NAME2,SERIES,SUMAMNT,TRDBRANCH,TRDBRANCH_TRDBRANCH_CODE,TRDBRANCH_TRDBRANCH_NAME,TRDR,TRDR_CUSTOMER_AFM,TRDR_CUSTOMER_CODE,TRDR_CUSTOMER_NAME,TRNDATE,VATAMNT;SRVLINES:DISC1PRC,LINENUM,LINEVAL,MTRL,MTRL_SERVICE_CODE,MTRL_SERVICE_NAME,MTRTYPE,PRICE,QTY1,VAT") {
        $params = array(
            "service" => "calculate",
            "clientID" => $this->authenticateClientID,
            "appId" => $this->appId,
            "OBJECT" => $obj,
            "FORM" => $form,
            "KEY" => $key,
            "LOCATEINFO" => $locateinfo,
            "data" => $data
        );
        return $this->doRequest($params);
    }

    function delData($obj, $key, $form = "") {
        $params = array(
            "service" => "delData",
            "clientID" => $this->authenticateClientID,
            "appId" => $this->appId,
            "OBJECT" => $obj,
            "FORM" => $form,
            "KEY" => $key,
        );
        return $this->doRequest($params);
    }

    function getSelectorData($editor = "", $value = "") {
        $params = array(
            "service" => "delData",
            "clientID" => $this->authenticateClientID,
            "appId" => $this->appId,
            "EDITOR" => $editor,
            "VALUE" => $value
        );
        return $this->doRequest($params);
    }

    function selectorFields($tbl, $keyname, $keyvalue, $fields) {
        $params = array(
            "service" => "delData",
            "clientID" => $this->authenticateClientID,
            "appId" => $this->appId,
            "TABLENAME" => $tbl,
            "KEYNAME" => $keyname,
            "KEYVALUE" => $keyvalue,
            "RESULTFIELDS" => $fields
        );
        return $this->doRequest($params);
    }

    function doRequest($data) {
        ini_set('memory_limit', '2048M');
        $data_string = json_encode($data);
        $result = file_get_contents($this->requerstUrl, null, stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' =>
                'Content-Type: application/json' . "\r\n"
                . 'Content-Length: ' . strlen($data_string) . "\r\n",
                'content' => $data_string,
            ),
        )));
        if (@$result1 = gzdecode($result)) {
            $result = iconv("ISO-8859-7", "UTF-8", $result1);
        } else {
            $result = iconv("ISO-8859-7", "UTF-8", $result);
        }
        $result = str_replace("	", "", $result);
        return json_decode($result);
    }

    function retrieveColumns($obj, $list = "") {
        $result = $this->getBrowserInfo($obj, $list);
        $out = array();
        foreach ($result->columns as $column) {
            //if ($i++ > 1) {
            $out[str_replace(".", "_", strtolower($column->dataIndex))] = $column->header;
            //}
        }
        return $out;
    }

    function retrieveFields($obj, $list = "", $filters = "") {
        $result = $this->getBrowserInfo($obj, $list, $filters);
        $out = array();
        foreach ($result->fields as $field) {
            //if ($i++ > 1) {
            $out[] = str_replace(".", "_", strtolower($field->name));
            //}
        }
        return $out;
    }

    function retrieveData($obj, $list = "", $filters = "") {
        $fields = $this->getBrowserInfo($obj, $list, $filters);

        foreach ($fields->fields as $key => $field) {
            $fieldRow[$key] = str_replace(".", "_", strtolower($field->name));
        }

        $datas = $this->getBrowser($obj, $list, $filters);

        $retrievedDataTable = array();
        foreach ((array) $datas->rows as $row) {
            $retrievedDataRow = array();
            foreach ($row as $key => $data_field) {
                $retrievedDataRow[$fieldRow[$key]] = $data_field;
            }
            $retrievedDataTable[] = $retrievedDataRow;
        }
        return $retrievedDataTable;
    }

}
