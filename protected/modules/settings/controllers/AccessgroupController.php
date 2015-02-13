<?php

class AccessgroupController extends Controller {

    //public $ajaxnew = "settings/accessgroup/0";
    public $dataTableId = "accessgroup";
    public $ajaxformsave = "settings/accessgroup/ajaxformsave/";
    public $ajaxdelete = "settings/accessgroup/ajaxdelete/";
    public $ajaxform = "settings/accessgroup/ajaxform/";
    public $ajaxformtitle = "settings/accessgroup/ajaxformtitle/";
    public $sAjaxSource = "settings/accessgroup/ajaxjson";
    public $returnaftersafe = "settings/accessgroup/";

    public function filters() {

        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules() {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function beforeAction($action) {
        $this->addJs("date.format.js");
        parent::beforeAction();
        $this->breadcrumbs[$this->translate("SETTINGS")] = Yii::app()->params['mainurl'] . "settings";
        $this->breadcrumbs[$this->translate("GROUPS")] = Yii::app()->params['mainurl'] . "settings";
        return true;
    }

    public function actionIndex($id) {
        $model = $this->loadModel($id);
    }



    public function actionTab() {
        $this->clearColumns();
        $this->addColumn(array(
                   "label"=>$this->translate("ID"),
                   "type"=>"text",
                   ) 
                );
        
        $this->addColumn(array(
                   "label"=>$this->translate("Title"),
                   "type"=>"text",
                   ) 
                );
        $this->renderPartial('tab', array());
    }

    public function actionAjaxJson() {
        $datas = $this->getModelArray("Accessgroup", "", array());
        $jsonArr = array();
        foreach ((array) $datas as $data) {
            $data->load();
            $json = array();
            $json[] = $data->id;
            $json[] = $data->title;
            $json["DT_RowId"] = 'accessgrouppage_' . $data->id;
            $json["DT_RowClass"] = 'accessgroup';
            $jsonArr[] = $json;
        }
        echo json_encode(array('aaData' => $jsonArr));
    }

    public function actionView($id) {


        $model = $this->loadModel($id);

        $this->breadcrumbs[] = $model->group;

        $this->render('index', array(
            'model' => $model,
            'tabs' => $tabs
        ));
    }

    public function actionAjaxFormTitle() {
        $model = $this->loadModel($_POST["id"]);

        if ($model->id > 0) {
            echo $this->translate("Edit Access Group") . ": " . $model->getFirstname() . " " . $model->getLastname();
        } else {
            echo $this->translate("Create new Access Group");
        }
    }

    public function actionAjaxForm() {
        $model = $this->loadModel($_POST["id"]);

        $this->addFormField("text", $this->translate("Title"), "title");
        $this->addFormField("textarea", $this->translate("Notes"), "notes", "", "width:300px; height:100px;");

        $this->renderPartial('ajaxform', array(
            'model' => $model,
            'tabs' => $this->createTabs($model),
        ));
    }

    function createTabs($model) {
        $tabs = array();
        if ($model->id > 0) {
            if ($this->functionAccess("tabusergroups") != "block")
                $tabs[] = array("title" => $this->translate(USER_GROUPS), "reg" => "settings/accessgroup/tabusergroups?id=" . $model->id);
            if ($this->functionAccess("tabaccessentities") != "block")
                $tabs[] = array("title" => $this->translate(ACCCESS_ENTITIES), "reg" => "settings/accessgroup/tabaccessentities?id=" . $model->id);

            return $tabs;
        }
    }

    function actiontabusergroups() {
        $model = $this->loadModel($_GET["id"]);
        $this->renderPartial('accessform', array(
            'model' => $model
        ));
    }

    function actiontabAccessentities() {
        $model = $this->loadModel($_GET["id"]);


        $datas["Accessmodule"] = $this->getModelArray("Accessmodule", "", array(), "module");
        $datas["Accessmodulecontroller"] = $this->getModelArray("Accessmodulecontroller", "", array(), "module, controller");
        $datas["Accessmodulecontrolleraction"] = $this->getModelArray("Accessmodulecontrolleraction", "", array(), "module, controller, action");
        $datas["Accessmodel"] = $this->getModelArray("Accessmodel", "", array(), "model");
        $datas["Accessmodelfield"] = $this->getModelArray("Accessmodelfield", "", array(), "model, field");

        $datas["AttributeItems"] = $this->getModelArray("AttributeItems", "", array(), "eav_model, title");
        $datas["AttributeGroups"] = $this->getModelArray("AttributeGroups", "", array());
        
        $this->renderPartial('accessentities', array(
            'model' => $model,
            'datas' => $datas,
        ));
    }

    function actionAccessformsave() {

        $model = $this->loadModel($_POST["id"]);

        $access = unserialize($model->access);

        $access[$_POST["group"]] = $_POST["access"];

        $model->access = serialize($access);

        $model->save();
    }

    public function actionAccessssave() {
        $model = $this->loadModel($_POST["id"]);
        $data = unserialize($model->data);
        $data[$_POST["entity"]][$_POST["ref"]] = $_POST["check"];
        $model->data = serialize($data);
        $model->save();
    }

    public function actionAjaxFormSave() {


        $model = $this->loadModel($_POST["id"]);
        $model->attributes = $_POST;
        $model->attrs = $_POST["attrs"];
        $model->save();

        $accesses = unserialize($model->access);
        $datas = unserialize($model->data);

        foreach ((array)$accesses as $group => $access) {
            foreach ((array)$datas as $entity => $arr) {
                foreach ((array)$arr as $ids => $value) {
                    if ($value == 1) {
                        $model = $this->model($entity, $ids);
                        $accesses[$group] = $access;
                        $model->access = serialize($accesses);
                        $model->save();
                    }
                }
            }
        }


        if (count($model->itemError) > 0)
            echo json_encode($model->itemError) . "|||" . json_encode($model->tabError);
    }

    public function loadModel($id) {
        return $this->model("Accessgroup", $id);
    }

}