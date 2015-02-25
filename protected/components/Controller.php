<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    //public $layout = '//layouts/column1';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();
    public $enumArray = array();
    public $boolenArray = array(0 => "No", 1 => "Yes");
    public $columns = array();
    public $formFields = array();
    public $ajaxnew = "";
    public $dataTableId = "";
    public $ajaxformsave = "";
    public $ajaxdelete = "";
    public $ajaxform = "";
    public $ajaxformtitle = "";
    public $sAjaxSource = "";
    public $ajaxcallback = "";
    public $ajaxpage;
    public $returnaftersafe = "";
    public $returntomain = "";
    public $cssstyle = array();
    public $useServerSide = false;
    public $lang = "el";
    public $categoryBreadCrumbs = array();
    public $sfields = false;
    public $bPaginate = 'true';
    public $bFilter = 'true';
    public $bInfo = 'true';
    public $fnInitComplete = 'function(){loadUi()}';
    public $showSave = true;
    public $showDelete = true;
    public $showCancel = true;
    public $showExport = false;
    public $bAddnewpos = "'top-right'";
    public $aoColumns = "";
    public $ls;
    public $css = array();
    public $js = array();
    public $chartOffset = array();
    public $pagename = "";
    public $userrole = "";
    public $tableName;
    public $aaSorting = "[[0,'desc']]";
    public $callback = "function() {alert('Data Saved')}";
    public $btnTitles = array("add_new" => "Νέο", "save" => "Αποθήκευη", "delete" => "Διαγραφή", "cancel" => "Άκυρο");
    public $settings = array();

    function getClient() {
        //return Clients::model()->findByPk(1);
    }

    function beforeAction() {
        date_default_timezone_set('Europe/Athens');


        $this->settings["language"] = 1;
        $this->settings["webservice"] = 11632;


        $this->clearColumns();

        Yii::app()->controller->module->id;
        Yii::app()->controller->action->id;
        Yii::app()->controller->id;



        $this->css[] = "jquery-ui.min.css";
        //$this->css[] = "jquery-ui.css";
        $this->css[] = "bootstrap.css";

        $this->css[] = "font-awesome.css";
        $this->css[] = "fullcalendar.css";
        $this->css[] = "prettyPhoto.css";
        $this->css[] = "rateit.css";
        $this->css[] = "bootstrap-datetimepicker.min.css";
        $this->css[] = "jquery.cleditor.css";
        $this->css[] = "uniform.default.css";
        $this->css[] = "bootstrap-toggle-buttons.css";
        $this->css[] = "style.css";
        $this->css[] = "widgets.css";
        $this->css[] = "chosen.min.css";
        
        $this->css[] = "bootstrap-responsive.css";
        $this->css[] = "jquery.dataTables.css";
        $this->css[] = "monitor.css";




        $this->js[] = "jquery.js";
        $this->js[] = "bootstrap.js";
        $this->js[] = "jquery-ui-1.9.2.custom.min.js";
        $this->js[] = "fullcalendar.min.js";
        $this->js[] = "jquery.rateit.min.js";
        $this->js[] = "jquery.prettyPhoto.js";
        $this->js[] = "excanvas.min.js";
        $this->js[] = "jquery.flot.js";
        $this->js[] = "jquery.flot.resize.js";
        $this->js[] = "jquery.flot.pie.js";
        $this->js[] = "jquery.flot.stack.js";
        $this->js[] = "jquery.noty.js";
        $this->js[] = "themes/default.js";
        $this->js[] = "layouts/bottom.js";
        $this->js[] = "layouts/topRight.js";
        $this->js[] = "layouts/top.js";
        $this->js[] = "sparklines.js";
        $this->js[] = "jquery.cleditor.min.js";
        $this->js[] = "bootstrap-datetimepicker.min.js";
        $this->js[] = "jquery.uniform.min.js";
        $this->js[] = "jquery.toggle.buttons.js";
        $this->js[] = "filter.js";
        $this->js[] = "custom.js";
        $this->js[] = "charts.js";
        $this->js[] = "main.js";
        $this->js[] = "init.js";
        $this->js[] = "jquery-ui.min.js";
        $this->js[] = "jquery.dataTables.min.js";
        $this->js[] = "jquery.ui.timepicker.js";
        $this->js[] = "chosen.jquery.min.js";
        $this->js[] = "chosen.proto.min.js";


        $user = $this->model("User", Yii::app()->user->id);
        $this->userrole = $user->role;

        $action = Yii::app()->controller->action->id;
        $controller = Yii::app()->controller->id;
        $module = Yii::app()->controller->module->id;




        $this->createModuleRecord($module);
        $this->createModuleControllerRecord($module, $controller);
        $this->createModuleControllerActionRecord($module, $controller, $action);


        if ($this->getAccess($module, $controller, $action) == "block" AND Yii::app()->controller->module->id != "" AND Yii::app()->user->id > 0) {
            if (Yii::app()->request->isAjaxRequest)
                echo "Access Denied";
            else
                $this->render('error', array(
                    'message' => "Access Denied",
                ));
            exit;
        }


        if ($this->getAccess($module, $controller, $action) == "view" AND Yii::app()->controller->module->id != "") {
            $this->showSave = false;
            $this->showDelete = false;
        } else {
            $this->showSave = true;
            $this->showDelete = true;
        }
        return true;
    }

    function money($number) {
        return number_format($number, 2, '.', '');
    }

    function functionAccess($function) {
        $action = Yii::app()->controller->action->id;
        $controller = Yii::app()->controller->id;
        $module = Yii::app()->controller->module->id;
        $this->createModuleControllerActionRecord($module, $controller, $function);
        return $this->getAccess($module, $controller, $function);
    }

    function accessFunction($function) {

        if ($this->functionAccess($function) == "block") {
            return false;
        } elseif ($this->functionAccess($function) == "view") {
            $this->showSave = false;
            $this->showDelete = false;
            $this->bAddnewpos = "''";
            return true;
        } elseif ($this->functionAccess($function) == "admin") {
            $this->showSave = true;
            $this->showDelete = true;
            $this->bAddnewpos = "'bottom-right'";
            return true;
        } else {
            return false;
        }
    }

    function getAccess($module, $controller, $action) {

        //return 'admin';
        $moduleAccess = $this->getModuleAccess($module);
        $moduleControlerAccess = $this->getModuleControlerAccess($module, $controller);
        $moduleControlerActionAccess = $this->getModuleControlerActionAccess($module, $controller, $action);


        switch ($moduleAccess) {
            case "block":
                return block;
                break;

            case "view":
                switch ($moduleControlerAccess) {
                    case "block":
                        return block;
                        break;
                    case "view":
                        switch ($moduleControlerActionAccess) {
                            case "block":
                                return block;
                                break;
                            case "view":
                                return view;
                                break;
                            case "admin":
                                return view;
                                break;
                            default:
                                return block;
                                break;
                        }
                        break;
                    case "admin":
                        switch ($moduleControlerActionAccess) {
                            case "block":
                                return block;
                                break;
                            case "view":
                                return view;
                                break;
                            case "admin":
                                return view;
                                break;
                            default:
                                return block;
                                break;
                        }
                        break;
                    default:
                        return block;
                        break;
                }
                break;
            case "admin":


                switch ($moduleControlerAccess) {
                    case "block":
                        return block;
                        break;
                    case "view":
                        switch ($moduleControlerActionAccess) {
                            case "block":
                                return block;
                                break;
                            case "view":
                                return view;
                                break;
                            case "admin":
                                return view;
                                break;
                            default:
                                return block;
                                break;
                        }
                        break;
                    case "admin":

                        switch ($moduleControlerActionAccess) {
                            case "block":
                                return block;
                                break;
                            case "view":
                                return view;
                                break;
                            case "admin":
                                return admin;
                                break;
                            default:
                                return block;
                                break;
                        }
                        break;
                    default:
                        return block;
                        break;
                }
                break;
            default:
                return block;
                break;
        }
    }

    function getModuleAccess($module) {
        $sql = "Select * from accessmodule where module = '" . $module . "'";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        $access = unserialize($data["access"]);
        return $access[$this->userrole];
    }

    function getModuleControlerAccess($module, $controller) {
        $sql = "Select * from  accessmodulecontroller where controller = '" . $controller . "' AND module = '" . $module . "'";
        $data = Yii::app()->db->createCommand($sql)->queryRow();

        $access = unserialize($data["access"]);
        return $access[$this->userrole];
    }

    function getModuleControlerActionAccess($module, $controller, $action) {
        $sql = "Select * from  accessmodulecontrolleraction where action = '" . $action . "' AND controller = '" . $controller . "' AND module = '" . $module . "'";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        $access = unserialize($data["access"]);
        return $access[$this->userrole];
    }

    function createModuleRecord($module) {
        $sql = "Select * from accessmodule where module = '" . $module . "'";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        if (!$data) {
            $sql = "Insert accessmodule set id='" . $this->getSysId() . "', access='" . $this->defaultAccess() . "', module = '" . $module . "'";
            Yii::app()->db->createCommand($sql)->execute();
        }
    }

    function createModuleControllerRecord($module, $controller) {

        $sql = "Select * from  accessmodulecontroller where controller = '" . $controller . "' AND module = '" . $module . "'";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        if (!$data) {
            $sql = "Insert  accessmodulecontroller set id='" . $this->getSysId() . "', access='" . $this->defaultAccess() . "', controller = '" . $controller . "', module = '" . $module . "'";
            Yii::app()->db->createCommand($sql)->execute();
        }
    }

    function createModuleControllerActionRecord($module, $controller, $action) {
        $sql = "Select * from  accessmodulecontrolleraction where action = '" . $action . "' AND controller = '" . $controller . "' AND module = '" . $module . "'";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        if (!$data) {
            $sql = "Insert  accessmodulecontrolleraction set id='" . $this->getSysId() . "', access='" . $this->defaultAccess() . "', action = '" . $action . "', controller = '" . $controller . "', module = '" . $module . "'";
            Yii::app()->db->createCommand($sql)->execute();
        }
    }

    function getSysId() {
        $sql = "Select id from sys";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        $data["id"] ++;
        $sql = "update sys set id = '" . $data["id"] . "'";
        Yii::app()->db->createCommand($sql)->execute();
        return $data["id"];
    }

    function defaultAccess() {
        return serialize(array("admin" => "admin"));
    }

    function getLoggedName() {
        $model = $this->model("User", Yii::app()->user->id);
        return $model->getFirstName() . " " . $model->getLastName();
    }

    function getUserName() {
        $model = $this->model("User", Yii::app()->user->id);
        return $model->getEmail();
    }

    public function addCss($css) {
        $this->css[] = $css;
    }

    public function addJs($js) {
        $this->js[] = $js;
    }

    function getPageKey() {
        if (isset($this->module)) {
            $module = $this->module->getName() . "-";
        }
        //return $module.$this->ID ."-". $this->action->id;
        return $module . $this->ID;
    }

    function saveSoftone($params) {

        $params["softone_object"];
        $params["eav_model"];
        $model = $params["model"];

        $object = $params["softone_object"];
        $softone = new Softone();
        $fields = $softone->retrieveFields($object, $params["list"]);

        //print_r($fields);    

        if ($model->reference) {
            $data = $softone->getData($object, $model->reference);
            $objectArr = $data->data->$object;
            $objectArr2 = (array) $objectArr[0];
            foreach ($fields as $field) {
                $attribute = Attributes::model()->findByAttributes(array('identifier' => $field));
                if ($attribute->id) {
                    $attributeitem = AttributeItems::model()->findByAttributes(array('attribute_id' => $attribute->id, "eav_model" => $params["eav_model"]));
                    $field1 = strtoupper(str_replace(strtolower($object) . "_", "", $field));
                    //$fld[$field] = $attributeitem->id;
                    //if ($_POST["attrs"][$attributeitem->id])
                    //$objectArr2[$field] = $_POST["attrs"][$attributeitem->id];
                    $objectArr2[$field1] = $_POST[$field];
                }
            }
            $objectArr[0] = $objectArr2;
            $dataOut[$object] = (array) $objectArr;
            //print_r($dataOut);
            $out = $softone->setData((array) $dataOut, $object, $model->reference);
            print_r($out);
        } else {
            $objectArr = array();
            foreach ($fields as $field) {
                $attribute = Attributes::model()->findByAttributes(array('identifier' => $field));
                if ($attribute->id) {
                    $attributeitem = AttributeItems::model()->findByAttributes(array('attribute_id' => $attribute->id, "eav_model" => $params["eav_model"]));
                    $field1 = strtoupper(str_replace(strtolower($object) . "_", "", $field));
                    //if ($_POST["attrs"][$attributeitem->id])
                    //    $objectArr[0][$field] = $_POST["attrs"][$attributeitem->id];
                    //echo $field."  ".$field1.",";
                    $objectArr2[$field1] = $_POST[$field];
                }
            }
            $objectArr[0] = $objectArr2;
            $dataOut[$object] = (array) $objectArr;
            //print_r($dataOut);
            $out = $softone->setData((array) $dataOut, $object, (int) $model->reference);

            if ($out->id > 0) {
                $model->reference = $out->id;
                $model->save();
            }
            //print_r($out);
        }
    }

    function retrieveSoftoneData($params = array()) {

        $params["softone_object"];
        $params["list"];
        $params["eav_model"];
        $params["model"];


        $softone = new Softone();
        $datas = $softone->retrieveData($params["softone_object"], $params["list"], $params["filters"]);

        $fields = $softone->retrieveFields($params["softone_object"], $params["list"], $params["filters"]);
        foreach ($fields as $field) {
            $attribute = Attributes::model()->findByAttributes(array('identifier' => $field));
            if ($attribute->id) {
                $attributeitem = AttributeItems::model()->findByAttributes(array('attribute_id' => $attribute->id, "eav_model" => $params["eav_model"]));
                $fld[$field] = $attributeitem->id;
            }
        }
        foreach ($datas as $data) {
            $zoominfo = $data["zoominfo"];
            $info = explode(";", $zoominfo);
            $model = $params["model"]::model()->findByAttributes(array('reference' => $info[1]));
            $model = $this->model($params["model"], $model->id);
            //$model->attributes = $params["attributes"];    

            foreach ((array) $params["attributes"] as $attribute => $value) {
                $model->$attribute = $value;
            }

            unset($data["zoominfo"]);
            unset($data["fld-1"]);
            $model->reference = $info[1];
            foreach ($data as $identifier => $dt) {
                $imporetedData[$fld[$identifier]] = addslashes($dt);
            }
            $model->attrs = $imporetedData;
            $model->save();
            //$model->setFlat();
            //if ($i++ > 100)
            //     break;
        }
    }

    function getModelArray($model, $cont1 = "", $cont2 = array(), $order = 'id', $page = false) {
        $obj = new $model;
        if (count($cont2) > 0) {
            $datas = Yii::app()->db->createCommand()
                    ->select('id')
                    ->from($obj->tableName())
                    ->where($cont1, $cont2)
                    ->order($order)
                    ->queryAll();
        } else {
            $datas = Yii::app()->db->createCommand()
                    ->select('id')
                    ->from($obj->tableName())
                    ->order($order)
                    ->queryAll();
        }
        if ($limit) {
            //$datas->limit($page . ",20");
        }
        $objs = array();
        foreach ($datas as $data) {
            $objs[] = $obj->model()->findByPk($data["id"]);
        }
        return $objs;
    }

    function setCssStyles() {
        
    }

    function accessFields($model, $item_id, $fieldname, $field) {
        
        

        $action = Yii::app()->controller->action->id;
        $controller = Yii::app()->controller->id;
        $module = Yii::app()->controller->module->id;
        $access = unserialize($model->modelItem[$item_id]->access);
        $groupaccess = unserialize($model->modelItem[$item_id]->group()->access);



        
        switch ($access[$this->userrole]) {
            case "block":
                return "";
                break;
            case "view":
                return $model->items[$item_id] . CHtml::hiddenField($fieldname . '[' . $item_id . ']', $model->items[$item_id], array('style' => $model->modelItem[$item_id]->css, 'disabled' => $disabled, 'class' => 'arrtformfield_' . $model->tableName, 'ref' => $item_id));
                break;
                if ($groupaccess[$this->userrole] == "block" OR $this->getAccess($module, $controller, $action) == "block" OR $model->getModelAccess($this->userrole) == "block") {
                    return "";
                } elseif ($groupaccess[$this->userrole] == "view" OR $this->getAccess($module, $controller, $action) == "view" OR $model->getModelAccess($this->userrole) == "view") {
                    return $model->items[$item_id] . CHtml::hiddenField($fieldname . '[' . $item_id . ']', $model->items[$item_id], array('style' => $model->modelItem[$item_id]->css, 'disabled' => $disabled, 'class' => 'arrtformfield_' . $model->tableName, 'ref' => $item_id));
                } elseif ($this->getAccess($module, $controller, $action) == "admin") {
                    return $model->items[$item_id] . CHtml::hiddenField($fieldname . '[' . $item_id . ']', $model->items[$item_id], array('style' => $model->modelItem[$item_id]->css, 'disabled' => $disabled, 'class' => 'arrtformfield_' . $model->tableName, 'ref' => $item_id));
                }
            case "admin":
                if ($groupaccess[$this->userrole] == "block" OR $this->getAccess($module, $controller, $action) == "block" OR $model->getModelAccess($this->userrole) == "block") {
                    return "";
                } elseif ($groupaccess[$this->userrole] == "view" OR $this->getAccess($module, $controller, $action) == "view" OR $model->getModelAccess($this->userrole) == "view") {
                    return $model->items[$item_id] . CHtml::hiddenField($fieldname . '[' . $item_id . ']', $model->items[$item_id], array('style' => $model->modelItem[$item_id]->css, 'disabled' => $disabled, 'class' => 'arrtformfield_' . $model->tableName, 'ref' => $item_id));
                } elseif ($this->getAccess($module, $controller, $action) == "admin") {
                    return $field;
                }
                break;
            default:
                return "";
        }
    }

    function attributeField($model, $item_id, $fieldname) {
        //echo $item_id;
        $disabled = ($model->modelItem[$item_id]->attribute()->locked AND $model->id > 0 AND $model->items[$item_id] != "") ? true : false;
        switch ($model->modelItem[$item_id]->attribute()->type) {
            case 'none':
                return $this->accessFields($model, $item_id, $fieldname, $model->items[$item_id]);
                break;
            case "text":
                return $this->accessFields($model, $item_id, $fieldname, CHtml::textField($fieldname . '[' . $item_id . ']', $model->items[$item_id], array('identifier' => $model->modelItem[$item_id]->attribute()->identifier, 'style' => $model->modelItem[$item_id]->css, 'disabled' => $disabled, 'class' => 'arrtformfield_' . $model->tableName, 'ref' => $item_id)));
                break;
            case "date":
                return $this->accessFields($model, $item_id, $fieldname, CHtml::textField($fieldname . '[' . $item_id . ']', $model->items[$item_id], array('identifier' => $model->modelItem[$item_id]->attribute()->identifier, 'style' => $model->modelItem[$item_id]->css, 'disabled' => $disabled, 'class' => 'datepicker arrtformfield_' . $model->tableName, 'ref' => $item_id)));
                break;
            case "datetime":
                return $this->accessFields($model, $item_id, $fieldname, CHtml::textField($fieldname . '[' . $item_id . ']', $model->items[$item_id], array('identifier' => $model->modelItem[$item_id]->attribute()->identifier, 'style' => $model->modelItem[$item_id]->css, 'disabled' => $disabled, 'class' => 'datetimepicker arrtformfield_' . $model->tableName, 'ref' => $item_id)));
                break;
            case "password":
                $p = $this->translate(NEW_PASS . ": ") . $this->accessFields($model, $item_id, $fieldname, CHtml::passwordField($fieldname . '[' . $item_id . ']', $model->items[$item_id], array('identifier' => $model->modelItem[$item_id]->attribute()->identifier, 'style' => $model->modelItem[$item_id]->css, 'disabled' => $disabled, 'class' => 'arrtformfield_' . $model->tableName, "as" => "new", 'ref' => $item_id)));
                $v = $this->translate(VERIFY_PASS . ": ") . $this->accessFields($model, $item_id, $fieldname, CHtml::passwordField($fieldname . '[' . $item_id . ']', $model->items[$item_id], array('identifier' => $model->modelItem[$item_id]->attribute()->identifier, 'style' => $model->modelItem[$item_id]->css, 'disabled' => $disabled, 'class' => 'arrtformfield_' . $model->tableName, "as" => "ver", 'ref' => $item_id)));
                return $p . "<BR>" . $v;
                break;
            case "select":
                $formdata = $model->modelItem[$item_id]->select_data != "" ? unserialize($model->modelItem[$item_id]->select_data) : unserialize($model->modelItem[$item_id]->attribute->select_data);
                if ($model->modelItem[$item_id]->attribute->source != "") {
                    $e = explode(",", $model->modelItem[$item_id]->attribute->source);
                    $m = $e[0];
                    $l = $e[1];
                    $k = $e[2];
                    $formdata1 = CHtml::listData($m::model()->findAll(), $l, $k);
                    if ($formdata1)
                        $formdata = $formdata1;
                }
                return $this->accessFields($model, $item_id, $fieldname, CHtml::dropDownList($fieldname . '[' . $item_id . ']', $model->items[$item_id], $formdata, array('identifier' => $model->modelItem[$item_id]->attribute()->identifier, 'style' => $model->modelItem[$item_id]->css, 'disabled' => $disabled, 'class' => 'arrtformfield_' . $model->tableName, 'ref' => $item_id)));
                break;
            case "selectrich":
                $formdata = $model->modelItem[$item_id]->select_data != "" ? unserialize($model->modelItem[$item_id]->select_data) : unserialize($model->modelItem[$item_id]->attribute->select_data);


                return $this->accessFields($model, $item_id, $fieldname, CHtml::dropDownList($fieldname . '[' . $item_id . ']', $model->items[$item_id], $formdata, array('identifier' => $model->modelItem[$item_id]->attribute()->identifier, 'style' => $model->modelItem[$item_id]->css, 'disabled' => $disabled, 'class' => 'selectrich arrtformfield_' . $model->tableName, 'ref' => $item_id)));
                break;
            case "selectmultirich":
                $formdata = $model->modelItem[$item_id]->select_data != "" ? unserialize($model->modelItem[$item_id]->select_data) : unserialize($model->modelItem[$item_id]->attribute->select_data);
                return $this->accessFields($model, $item_id, $fieldname, CHtml::dropDownList($fieldname . '[' . $item_id . ']', $model->items[$item_id], $formdata, array('identifier' => $model->modelItem[$item_id]->attribute()->identifier, 'style' => $model->modelItem[$item_id]->css, 'disabled' => $disabled, 'class' => 'selectrich arrtformfield_' . $model->tableName, 'ref' => $item_id, 'multiple' => 'multiple')));
                break;
            case "checkboxlist":
                $formdata = $model->modelItem[$item_id]->select_data != "" ? unserialize($model->modelItem[$item_id]->select_data) : unserialize($model->modelItem[$item_id]->attribute->select_data);
                return $this->accessFields($model, $item_id, $fieldname, CHtml::checkBoxList($fieldname . '[' . $item_id . ']', $model->items[$item_id], $formdata, array('identifier' => $model->modelItem[$item_id]->attribute()->identifier, 'style' => $model->modelItem[$item_id]->css, 'disabled' => $disabled, 'class' => 'arrtformfield_' . $model->tableName, 'ref' => $item_id)));
                break;
            case "textarea":
                return $this->accessFields($model, $item_id, $fieldname, CHtml::textArea($fieldname . '[' . $item_id . ']', $model->items[$item_id], array('identifier' => $model->modelItem[$item_id]->attribute()->identifier, 'style' => $model->modelItem[$item_id]->css, 'disabled' => $disabled, 'class' => 'arrtformfield_' . $model->tableName, 'ref' => $item_id)));
                break;
            case "richtext":
                return $this->accessFields($model, $item_id, $fieldname, CHtml::textArea($fieldname . '[' . $item_id . ']', $model->items[$item_id], array('identifier' => $model->modelItem[$item_id]->attribute()->identifier, 'style' => $model->modelItem[$item_id]->css, 'disabled' => $disabled, 'class' => 'richtext arrtformfield_' . $model->tableName, 'ref' => $item_id)));
                break;
            case "boolen":
                return $this->accessFields($model, $item_id, $fieldname, CHtml::dropDownList($fieldname . '[' . $item_id . ']', $model->items[$item_id], $this->boolenArray, array('identifier' => $model->modelItem[$item_id]->attribute()->identifier, 'style' => $model->modelItem[$item_id]->css, 'disabled' => $disabled, 'class' => 'arrtformfield_' . $model->tableName, 'ref' => $item_id)));
                break;
            case "checkbox":
                return $this->accessFields($model, $item_id, $fieldname, CHtml::checkBox($fieldname . '[' . $item_id . ']', $model->items[$item_id] == 1, $this->boolenArray, array('identifier' => $model->modelItem[$item_id]->attribute()->identifier, 'style' => $model->modelItem[$item_id]->css, 'disabled' => $disabled, 'class' => 'arrtformfield_' . $model->tableName, 'ref' => $item_id)));
                break;
        }
    }

    function attributeList($model, $fieldname, $group, $i = 1) {
        //$model->load();
        //$html = '<table>';


        $i--;
        //$html .= '<tr ' . $hid . '>';
        foreach ((array) $model->groupitems[$group] as $item_id => $item) {

            $access = unserialize($model->modelItem[$item_id]->access);
            $hid = ($model->modelItem[$item_id]->visible == 0 OR $access[$this->userrole] == "block" OR $access[$this->userrole] == "") ? " style='display:none'" : "";

            if ($i % $model->columns[$group] == 0 AND $i > 0)
                $html .= '<tr>';
            if ($model->modelItem[$item_id]->list_style == "vertical") {
                $html .= ' <td ' . $hid . ' valign=top colspan=2 class="td2" >
                    ' . $this->translate($model->modelItem[$item_id]->title) . $this->getRequired($model->modelItem[$item_id]->required) . '<BR>
                    ' . $this->attributeField($model, $item_id, $fieldname . '[' . (int) $model->id . ']') . '
                    </td>
                    <td ' . $hid . ' align=left class="itemerror" id="itemerror_' . $model->tableName . '_' . $item_id . '">' . $model->itemError[$item_id] . '</td>';
            } else {

                $html .= '<td ' . $hid . ' valign=top  class="td1"  width=1 style="text-align:right; white-space:nowrap">' . $this->translate($model->modelItem[$item_id]->title) . $this->getRequired($model->modelItem[$item_id]->required) . '</td>
                    <td ' . $hid . ' valign=top class="td2" >
                    ' . $this->attributeField($model, $item_id, $fieldname . '[' . (int) $model->id . ']') . '
                    </td>
                    <td ' . $hid . ' align=left class="itemerror" id="itemerror_' . $model->tableName . '_' . $item_id . '">' . $model->itemError[$item_id] . '</td>';
            }


            $i++;
            if ($i % $model->columns[$group] == 0)
                $html .= '</tr>';
        }
        //$html .= '</tr>';
        //$html .= '</table>';

        return $html;
    }

    function translate($str) {
        return $str;
    }

    function getRequired($r = 0) {

        if ($r == 1) {
            return "<span style='color:red'>*</span>";
        }
    }

    function accessModelFields($model, $data, $field) {


        $action = Yii::app()->controller->action->id;
        $controller = Yii::app()->controller->id;
        $module = Yii::app()->controller->module->id;
        $access = $model->getAccess($this->userrole, $data["attribute"]);

        


        switch ($access) {
            case "block":
                return "";
            case "view":
                if ($this->getAccess($module, $controller, $action) == "block") {
                    return "";
                } elseif ($this->getAccess($module, $controller, $action) == "view") {
                    return $model->$data["attribute"] . CHtml::hiddenField($model->tableName . "[" . $data["attribute"] . "]", $model->$data["attribute"], array('style' => $this->cssstyle[$data["attribute"]], 'disabled' => $disabled, "style" => $data["cssstyle"], "class" => "formfield_" . $model->tableName, "ref" => $data["attribute"]));
                } elseif ($this->getAccess($module, $controller, $action) == "admin") {
                    return $model->$data["attribute"] . CHtml::hiddenField($model->tableName . "[" . $data["attribute"] . "]", $model->$data["attribute"], array('style' => $this->cssstyle[$data["attribute"]], 'disabled' => $disabled, "style" => $data["cssstyle"], "class" => "formfield_" . $model->tableName, "ref" => $data["attribute"]));
                } else {
                    return "";
                }
                break;
            case "admin":
                if ($this->getAccess($module, $controller, $action) == "block") {
                    return "";
                } elseif ($this->getAccess($module, $controller, $action) == "view") {
                    return $model->$data["attribute"] . CHtml::hiddenField($model->tableName . "[" . $data["attribute"] . "]", $model->$data["attribute"], array('style' => $this->cssstyle[$data["attribute"]], 'disabled' => $disabled, "style" => $data["cssstyle"], "class" => "formfield_" . $model->tableName, "ref" => $data["attribute"]));
                } elseif ($this->getAccess($module, $controller, $action) == "admin") {
                    return $field;
                } else {
                    return "";
                }
            default :
                return "";
        }
    }

    function modelAttributeFormRow($model, $data, $i = 1) {

        //$row = '<tr><td class="td1" valign=top style="text-align:right;" align=right>' . $this->translate($data["title"]);

        $model->columns[1] = !$model->columns[1] ? 1 : $model->columns[1];
        if ($i % @$model->columns[1] == 0 AND $i > 0)
            $html .= '<tr>';
        if ($data["liststyle"] == "horizontal") {
            $row = '<td class="td1" valign=top style="text-align:right;" align=right>' . $this->translate($data["title"]);
            $row .= '</td><td valign=top>';
        } else {
            $row = '<td class="td1" colspan=2 valign=top>' . $this->translate($data["title"]);
            $row .= "<BR>";
        }

        $disabled = (in_array($data["attribute"], (array) $model->validationRules["locked"]) AND $model->id > 0 AND $model->$data["attribute"] != "") ? true : false;

        switch ($data["type"]) {
            case 'none':
                $row .= "<span ref='" . $data["attribute"] . "' class='formfield_" . $model->tableName . "'>" . $model->$data["attribute"] . "</span>";
                break;
            case "text":
                $field = CHtml::textField($model->tableName . "[" . $data["attribute"] . "]", $model->$data["attribute"], array('style' => $this->cssstyle[$data["attribute"]], 'disabled' => $disabled, "style" => $data["cssstyle"], "class" => "formfield_" . $model->tableName, "ref" => $data["attribute"]));
                $row .= $this->accessModelFields($model, $data, $field);
                break;
            case "password":
                $field1 = $this->translate(NEW_PASS . ": ") . CHtml::passwordField($model->tableName . "[" . $data["attribute"] . "]", "", array('style' => $this->cssstyle[$data["attribute"]], 'disabled' => $disabled, "style" => $data["cssstyle"], "class" => "formfield_" . $model->tableName, "as" => "new", "ref" => $data["attribute"]));
                $field2 = $this->translate(VERIFY_PASS . ":") . CHtml::passwordField($model->tableName . "[" . $data["attribute"] . "]", "", array('style' => $this->cssstyle[$data["attribute"]], 'disabled' => $disabled, "style" => $data["cssstyle"], "class" => "formfield_" . $model->tableName, "as" => "ver", "ref" => $data["attribute"]));

                $row .= $this->accessModelFields($model, $data, $field1) . "<BR>";
                $row .= $this->accessModelFields($model, $data, $field2);
                break;
            case "date":
                $field = CHtml::textField($model->tableName . "[" . $data["attribute"] . "]", $model->$data["attribute"], array('style' => $this->cssstyle[$data["attribute"]], 'disabled' => $disabled, "style" => $data["cssstyle"], "class" => "datepicker formfield_" . $model->tableName, "ref" => $data["attribute"]));
                $row .= $this->accessModelFields($model, $data, $field);
                break;
            case "datetime":
                $field = CHtml::textField($model->tableName . "[" . $data["attribute"] . "]", $model->$data["attribute"], array('style' => $this->cssstyle[$data["attribute"]], 'disabled' => $disabled, "style" => $data["cssstyle"], "class" => "datetimepicker formfield_" . $model->tableName, "ref" => $data["attribute"]));
                $row .= $this->accessModelFields($model, $data, $field);
                break;
            case "textarea":
                $field = CHtml::textArea($model->tableName . "[" . $data["attribute"] . "]", $model->$data["attribute"], array('style' => $this->cssstyle[$data["attribute"]], 'disabled' => $disabled, "style" => $data["cssstyle"], "class" => "formfield_" . $model->tableName, "ref" => $data["attribute"]));
                $row .= $this->accessModelFields($model, $data, $field);
                break;
            case "boolen":
                $field = CHtml::dropDownList($model->tableName . "[" . $data["attribute"] . "]", $model->$data["attribute"], $this->boolenArray, array('style' => $this->cssstyle[$data["attribute"]], 'disabled' => $disabled, "style" => $data["cssstyle"], "class" => "formfield_" . $model->tableName, "ref" => $data["attribute"]));
                $row .= $this->accessModelFields($model, $data, $field);
                break;
            case "checkbox":
                $field = CHtml::checkBox($model->tableName . "[" . $data["attribute"] . "]", $model->$data["attribute"], $this->boolenArray, array('style' => $this->cssstyle[$data["attribute"]], 'disabled' => $disabled, "style" => $data["cssstyle"], "class" => "formfield_" . $model->tableName, "ref" => $data["attribute"]));
                $row .= $this->accessModelFields($model, $data, $field);
                break;
            case "select":
                $field = CHtml::dropDownList($model->tableName . "[" . $data["attribute"] . "]", $model->$data["attribute"], $data["select_data"], array('style' => $this->cssstyle[$data["attribute"]], 'disabled' => $disabled, "style" => $data["cssstyle"], "class" => "formfield_" . $model->tableName, "ref" => $data["attribute"]));
                $row .= $this->accessModelFields($model, $data, $field);
                break;
            case "selectrich":
                $field = CHtml::dropDownList($model->tableName . "[" . $data["attribute"] . "]", $model->$data["attribute"], $data["select_data"], array('style' => $this->cssstyle[$data["attribute"]], 'disabled' => $disabled, "style" => $data["cssstyle"], "class" => "selectrich formfield_" . $model->tableName, "ref" => $data["attribute"]));
                $row .= $this->accessModelFields($model, $data, $field);
                break;
            case "selectmultirich":
                $field = CHtml::dropDownList($model->tableName . "[" . $data["attribute"] . "]", $model->$data["attribute"], $data["select_data"], array('style' => $this->cssstyle[$data["attribute"]], 'disabled' => $disabled, "style" => $data["cssstyle"], "class" => "selectrich formfield_" . $model->tableName, "ref" => $data["attribute"], "multiple" => "multiple"));
                $row .= $this->accessModelFields($model, $data, $field);
                break;
            case "hidden":
                $field = CHtml::hiddenField($model->tableName . "[" . $data["attribute"] . "]", $model->$data["attribute"], array('style' => $this->cssstyle[$data["attribute"]], 'disabled' => $disabled, "style" => $data["cssstyle"], "class" => "formfield_" . $model->tableName, "ref" => $data["attribute"]));
                $row .= $this->accessModelFields($model, $data, $field);
                break;
            case "checkboxlist":
                $field = CHtml::checkBoxList($model->tableName . "[" . $data["attribute"] . "]", explode(",", $model->$data["attribute"]), $data["select_data"], array('style' => $this->cssstyle[$data["attribute"]], 'disabled' => $disabled, "class" => "formfieldmulti", "ref" => $data["attribute"]));
                $row .= $this->accessModelFields($model, $data, $field);
                $field = CHtml::hiddenField($model->tableName . "[" . $data["attribute"] . "]", $model->$data["attribute"], array('style' => $this->cssstyle[$data["attribute"]], 'disabled' => $disabled, "style" => $data["cssstyle"], "class" => "formfield formfield_" . $model->tableName, "ref" => $data["attribute"]));
                $row .= $this->accessModelFields($model, $data, $field);
                break;
            default:
                //$this->itemError[$item->id] = false;
                break;
        }
        $row .= '</td><td style="text-align:left;"  class="td2 itemerror" id="itemerror_' . $model->tableName . '_' . $data["attribute"] . '"></td>';

        if ($i % @$model->columns[1] == 0 AND $i > 0)
            $row .= '</tr>';
        return $row;
    }

    public function addFormField($type, $title, $attribute, $select_data = array(), $cssstyle = '', $liststyle = 'horizontal') {
        $this->formFields[] = array("type" => $type, "title" => $title, "attribute" => $attribute, "select_data" => $select_data, 'cssstyle' => $cssstyle, 'liststyle' => $liststyle);
    }

    public function clearColumns() {
        $this->columns = array();
        $this->aoColumns = array();
    }

    public function addColumn($options = array("label" => "", "type" => "text", "data" => array(), "aoColumns" => array())) {

        $this->columns[] = $options;
        $this->aoColumns[] = $options["aoColumns"];
    }

    function curlIt($theURL, $postdata = "") {
        //echo("In curl:<br>".$theURL." POST DATA:".$postdata."<br>");
        $ch = curl_init($theURL);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        //        decho("OUT curl".$result);
        return $result;
    }

    function getShopData() {
        $datas = Yii::app()->db->createCommand()
                ->select('*')
                ->from('shop')
                ->queryAll();
        $out = array();
        $out[0] = "Select";
        foreach ($datas as $data) {
            $out[$data["id"]] = $data["shop"];
        }
        return $out;
    }

    function getPartnerFormData() {
        $datas = Yii::app()->db->createCommand()
                ->select('*')
                ->from('partner')
                ->queryAll();
        $out = array();
        foreach ($datas as $data) {
            $partner = Partner::model()->findByPk($data["id"]);
            $partner->load();
            $out[$data["id"]] = $partner->getCompany();
        }
        return $out;
    }

    function getUserFormData() {
        $datas = Yii::app()->db->createCommand()
                ->select('*')
                ->from('user')
                ->queryAll();
        $out = array();
        foreach ($datas as $data) {
            $user = User::model()->findByPk($data["id"]);
            $user->load();
            $out[$data["id"]] = $user->getFirstname() . " " . $user->getLastname();
        }
        return $out;
    }

    function getUserGroupFormData() {
        $datas = Yii::app()->db->createCommand()
                ->select('*')
                ->from('usergroup')
                ->queryAll();
        $out = array();
        $out[$data["group"]] = $this->translate(SELECT);
        foreach ($datas as $data) {
            $out[$data["group"]] = $data["group"];
        }
        return $out;
    }

    function getWebsitesFormData() {
        $datas = Yii::app()->db->createCommand()
                ->select('*')
                ->from('website')
                ->queryAll();
        $out = array();
        foreach ($datas as $data) {
            $website = Website::model()->findByPk($data["id"]);
            $website->load();
            $out[$data["id"]] = $website->getUrl();
        }
        return $out;
    }

    function fixdate($date) {
        return date("d/m/Y", strtotime($date));
    }

    function fixdateRes($str) {
        $arr = explode("/", $str);
        return $arr[2] . "-" . $arr[1] . "-" . $arr[0];
    }

    function createCategoryBreadCrumbs($obj) {
        if ((int) $obj->id == 0)
            return;
        $this->categoryBreadCrumbs[] = $obj->title;
        $objParent = $obj->parent();
        if ($objParent) {
            if ($objParent->id != $obj->id) {
                $this->createCategoryBreadCrumbs($objParent);
            }
        }
    }

    function createCategoryBreadCrumbsForm() {
        $cats = CHtml::listData(Category::model()->findAll(), 'id', 'title');
        $form = array();
        foreach ($cats as $key => $cat) {
            $obj = Category::model()->findByPk($key);
            $this->categoryBreadCrumbs = array();
            $this->createCategoryBreadCrumbs($obj);
            $form[$key] = implode("->", $this->array_reverse_order($this->categoryBreadCrumbs));
        }
        return $form;
    }

    function array_reverse_order($array) {
        $array_key = array_keys($array);
        $array_value = array_values($array);

        $array_return = array();
        for ($i = 1, $size_of_array = sizeof($array_key); $i <= $size_of_array; $i++) {
            $array_return[$array_key[$size_of_array - $i]] = $array_value[$size_of_array - $i];
        }
        return $array_return;
    }

    public function model($modelname, $id = 0) {
        $model = new $modelname();
        $model = $model->model()->findByPk($id);
        if ((int) $model->id == 0) {
            $model = new $modelname();
        }
        if ($model->iseav)
            $model->load();
        $this->enumArray();
        return $model;
    }

    function enumArray() {
        
    }

    public $categorydrpdwn = array();

    function getCategoryDrpDwn($obj = false) {
        if (!$obj) {
            $sql = "Select * from expensecategory where parent = 0";
            $data = Yii::app()->db->createCommand($sql)->queryRow();
            $obj = $this->model("Expensecategory", $data["id"]);
            $this->categorydrpdwn[0] = $this->translate(SELECT_CATEGORY);
        }
        $datas = $obj->children();
        foreach ($datas as $data) {
            $obj = $this->model("Expensecategory", $data["id"]);
            $this->categorydrpdwn[$obj->id] = $obj->getIndent();
            if ($obj->children())
                $this->getCategoryDrpDwn($obj);
        }
        return $this->categorydrpdwn;
    }

    function greeklish($name) {
        $greek = array('α', 'ά', 'Ά', 'Α', 'β', 'Β', 'γ', 'Γ', 'δ', 'Δ', 'ε', 'έ', 'Ε', 'Έ', 'ζ', 'Ζ', 'η', 'ή', 'Η', 'θ', 'Θ', 'ι', 'ί', 'ϊ', 'ΐ', 'Ι', 'Ί', 'κ', 'Κ', 'λ', 'Λ', 'μ', 'Μ', 'ν', 'Ν', 'ξ', 'Ξ', 'ο', 'ό', 'Ο', 'Ό', 'π', 'Π', 'ρ', 'Ρ', 'σ', 'ς', 'Σ', 'τ', 'Τ', 'υ', 'ύ', 'Υ', 'Ύ', 'φ', 'Φ', 'χ', 'Χ', 'ψ', 'Ψ', 'ω', 'ώ', 'Ω', 'Ώ', ' ', "'", "'", ',');
        $english = array('a', 'a', 'A', 'A', 'b', 'B', 'g', 'G', 'd', 'D', 'e', 'e', 'E', 'E', 'z', 'Z', 'i', 'i', 'I', 'th', 'Th', 'i', 'i', 'i', 'i', 'I', 'I', 'k', 'K', 'l', 'L', 'm', 'M', 'n', 'N', 'x', 'X', 'o', 'o', 'O', 'O', 'p', 'P', 'r', 'R', 's', 's', 'S', 't', 'T', 'u', 'u', 'Y', 'Y', 'f', 'F', 'ch', 'Ch', 'ps', 'Ps', 'o', 'o', 'O', 'O', '-', '-', '-', '-');
        $string = str_replace($greek, $english, $name);
        return $string;
    }

    public function availableDoctors($start = false, $end = false) {
        if ($start) {
            $start = date("Y-m-d H:i:s", strtotime($start));
            if ($end) {
                $end = date("Y-m-d H:i:s", strtotime($end));
            } else {
                $end = date("Y-m-d H:i:s", strtotime($start) + 3600);
            }
            if ($start)
                $sqlDoctorDays = "id in (Select user_id from doctorday where start <= '" . $start . "' AND end >= '" . $end . "')";
        }
        $sql1str[] = $sqlDoctorDays ? $sqlDoctorDays : "1=1";


        if (System::settings("ActivateDoctorDay") == 1) {
            $sql = "Select * from user where role = 'doctor' AND (" . implode(" AND ", $sql1str) . ")";
        } else {
            $sql = "Select * from user where role = 'doctor'";
        }
        //echo $sql;


        $datas = Yii::app()->db->createCommand($sql)->queryAll();

        $out = array();
        $out[0] = $this->translate(SELECT);
        foreach ((array) $datas as $data) {
            $model = $this->model("User", $data["id"]);
            $out[$data["id"]] = $model->getFirstname() . " " . $model->getLastname();
        }
        return $out;
    }

    function timeDiff($firstTime, $lastTime) {
        //return $firstTime." ".$lastTime;
        $firstTime = strtotime($firstTime);
        $lastTime = strtotime($lastTime);
        $timeDiff = $firstTime - $lastTime;
        $diff = (int) floor($timeDiff / 60);
        return $diff;
    }

    function indent($lvl) {
        for ($i = 0; $i <= $lvl; $i++) {
            $out .= "-";
        }
        return $out;
    }

    public function sortData($data, $options = array("col" => 0, "sort" => "asc"), $columns = array()) {

        $data2 = $data;

        for ($i = 0; $i <= $_POST["iColumns"]; $i++) {
            if ($_POST["sSearch_" . $i] != "") {
                foreach ($data2 as $key => $val) {
                    if (stripos($val[$i], $_POST["sSearch_" . $i]) === false) {
                        $data[$key] = false;
                    } else {
                        
                    }
                }
            }
        }

        //print_r($data);      
        $sortArray = array();
        foreach ($data as $val) {
            $sortArray[] = $val[$options["iSortCol_0"]];
        }
        //echo $options["sort"];

        $as["asc"] = SORT_ASC;
        $as["desc"] = SORT_DESC;

        array_multisort($sortArray, $as[$options["sSortDir_0"]], $data);
        //return $data;
        $out = array();
        for ($i = $options["iDisplayStart"]; $i <= $options["iDisplayStart"] + $options["iDisplayLength"]; $i++) {
            if (!$data[$i])
                break;
            $out[] = $data[$i];
        }
        if (count($sum) >= 1)
            $out[] = $sum;
        return $out;
    }

    function calculateIncome($date) {
        $sql = "Select sum(price) as income from pistosi where date_of_payment like '%" . $date . "%' group by substring(date_of_payment,1,10)";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        return number_format($data["income"], 2, ".", "");
    }

    function calculateExpense($date) {
        $sql = "Select sum(value) as expense from expense where datetime like '%" . $date . "%' group by substring(datetime,1,10)";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        return number_format($data["expense"], 2, ".", "");
    }

    public function jsonClinicDaysEvent($id) {
        $modelClinicday = $this->model("Clinicday", $id);
        $event["title"] = '';
        $event["allDay"] = false;
        $event["start"] = $modelClinicday->start;

        $event["end"] = $modelClinicday->end;
        $event["editable"] = false;

        return $event;
    }
    public function articleAttributes($model) {
        $url = "http://service.fastwebltd.com/";
        $fields = array(
            'action' => 'articleAttributes',
            'tecdoc_article_id' => $model->_webserviceProducts_[11632]->article_id
        );

        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        rtrim($fields_string, '&');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $data = curl_exec($ch);

        return $data;
    }
    
    public function getOriginals($model) {
        $url = "http://service.fastwebltd.com/";
        $fields = array(
            'action' => 'getOriginals',
            'tecdoc_article_id' => $model->_webserviceProducts_[11632]->article_id
        );

        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        rtrim($fields_string, '&');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $data = curl_exec($ch);

        return $data;
    }    
}
