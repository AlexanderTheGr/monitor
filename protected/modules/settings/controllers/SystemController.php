<?php

class SystemController extends Controller {

    /**
     * @return array action filters
     */
    //public $ajaxnew = "settings/attributes/0";
    public $dataTableId = "settings";
    public $ajaxformsave = "settings/system/ajaxformsave/";
    public $ajaxdelete = "settings/system/ajaxdelete/";
    public $ajaxform = "settings/system/ajaxform/";
    public $ajaxformtitle = "settings/system/ajaxformtitle/";
    public $sAjaxSource = "settings/system/ajaxjson";
    public $returnaftersafe = "settings/system/";

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

    public function actionIndex($id) {
        $model = $this->loadModel($id);
    }

    public function enumArray() {
        $this->enumArray["validation"] = array('none' => 'None', 'email' => 'Email', 'number' => 'Number', 'phone' => 'Phone', 'date' => 'Date');
        $this->enumArray["type"] = array('text' => 'Text', 'boolen' => 'Boolen', 'password' => 'Password', 'textarea' => 'Textarea', 'select' => 'Select', 'date' => 'Date', 'checkbox' => 'Checkbox', 'Checkboxlist' => 'checkboxlist', 'richtext' => 'Rich Text');
    }

    public function actionTab() {

        
        $this->clearColumns();
        $this->addColumn(array(
                   "label"=>$this->translate("Title"),
                   "type"=>"text",
                   ) 
                );        
        $this->addColumn(array(
                   "label"=>$this->translate("Key"),
                   "type"=>"text",
                   ) 
                );    
        $this->addColumn(array(
                   "label"=>$this->translate("Type"),
                   "type"=>"text",
                   ) 
                );    
        $this->addColumn(array(
                   "label"=>$this->translate("Value"),
                   "type"=>"text",
                   ) 
                );    
        $this->sfields = true;
        $this->bFilter = true;
        $this->renderPartial('tab', array());
    }

    public function actionAjaxJson() {
        //$ls = Langtranslater::getSingleton();
        $datas = $this->getModelArray("Settings", "", array());
        $jsonArr = array();
        $this->enumArray();
        foreach ($datas as $data) {
            $data->load();
            $json = array();
            
            $multidata = explode(",",$data->multidata);
            $json[] = $data->label;
            $json[] = $data->key;
            $json[] = $data->type;
            $json[] = (count($multidata) > 0 AND $data->multidata != "") ? $multidata[$data->value] : htmlspecialchars($data->value);
            $json["DT_RowId"] = 'settings_' . $data->id;
            $json["DT_RowClass"] = 'settings';
            $jsonArr[] = $json;
        }
        echo json_encode(array('aaData' => $jsonArr));
    }

    public function actionAjaxForm($id=0) {
        echo $_GET["id"];
        $model = $this->loadModel($_POST["id"]);

        if ($model->id == 0) {
            $this->addFormField("text", $this->translate(KEY), "key");
            $this->addFormField("text", $this->translate(TITLE), "label");
            $this->addFormField("select", $this->translate(TYPE), "type", $this->enumArray["type"]);
            $this->addFormField("text", $this->translate(DATA), "multidata");
        } else {
            
            $this->addFormField($model->type, $this->translate(VALUE), "value",explode(",",$model->multidata));
        }
        $this->renderPartial('ajaxform', array(
            'model' => $model
        ));
    }

    public function actionAjaxFormSave() {
        $model = $this->loadModel($_POST["id"]);
        $model->attributes = $_POST;
        $model->attrs = $_POST["attrs"];
        
        if ($model->id == 0) {
            $model->validationRules["required"] = array("key","label");
        }
        $model->save();
        if (count($model->itemError) > 0)
            echo json_encode($model->itemError) . "|||" . json_encode($model->tabError);
    }

    public function actionAjaxDelete() {
        $model = $this->loadModel($_POST["id"]);
        $model->deleteModel();
    }

    public function actionAjaxFormTitle() {
        //$ls = Langtranslater::getSingleton();
        $model = $this->loadModel($_POST["id"]);
        if ($model->id > 0) {
            echo $this->translate("Edit: ").$model->label;
        } else {
            echo $this->translate("Create new KEY");
        }
    }

    private function getAttributes() {
        return $this->getModelArray("Attributes", "", array());
    }

    private function save($model) {
        //print_r(Yii::app()->request->getPost("user",array()));
        $model->attributes = Yii::app()->request->getPost("attributes", array());
        if ($model->save()) {

            $this->redirect($model->id);
        }
    }

    private function delete($model) {
        $model->delete();
    }

    public function loadModel($id) {
        return $this->model("Settings", $id);
    }

}