<?php

class UsersettingsController extends Controller {

    /**
     * @return array action filters
     */
    //public $ajaxnew = "settings/attributes/0";
    public $dataTableId = "usersettings";
    public $ajaxformsave = "settings/usersettings/ajaxformsave/";
    public $ajaxdelete = "settings/usersettings/ajaxdelete/";
    public $ajaxform = "settings/usersettings/ajaxform/";
    public $ajaxformtitle = "settings/usersettings/ajaxformtitle/";
    public $sAjaxSource = "settings/usersettings/ajaxjson";
    public $returnaftersafe = "settings/usersettings/";

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
        
        
        if ($this->getAccess("settings", "usersettings", "usersettingsacess") != "admin") {
            $this->showDelete = false;
            $this->bAddnewpos = "''";
        }

        $this->clearColumns();
        $this->addColumn(array(
                    "label" => $this->translate(TITLE),
                    "type" => "text",
                        )
        ); 
        $this->addColumn(array(
                    "label" => $this->translate(KEY),
                    "type" => "text",
                        )
        );            
        $this->addColumn(array(
                    "label" => $this->translate(TYPE),
                    "type" => "text",
                        )
        );    
        $this->addColumn(array(
                    "label" => $this->translate(VALUE),
                    "type" => "text",
                        )
        );            
        
        $this->renderPartial('tab', array());
    }

    
    public function actionAjaxJson() {
        //$ls = Langtranslater::getSingleton();
        $datas = $this->getModelArray("Usersettings", "user=:user", array(":user"=>Yii::app()->user->id));
        $jsonArr = array();
        $this->enumArray();
        foreach ($datas as $data) {
            $data->load();
            $json = array();
            $multidata = explode(",",$data->multidata);
            $json[] = $data->label;
            $json[] = $data->key;
            $json[] = $data->type;
            $json[] = (count($multidata) > 0 AND $data->multidata != "") ? $multidata[(int)$data->value] : htmlspecialchars($data->value);
            $json["DT_RowId"] = 'usersettings_' . $data->id;
            $json["DT_RowClass"] = 'usersettings';
            $jsonArr[] = $json;
        }
        echo json_encode(array('aaData' => $jsonArr));
    }

    public function actionAjaxForm() {
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
            $sql = "Select * from user";
            $datas = Yii::app()->db->createCommand($sql)->queryAll();
            $model->validationRules["required"] = array("key","label");
            foreach($datas as $data) {
                $model = $this->loadModel(0);
                $model->attributes = $_POST;
                $model->attrs = $_POST["attrs"];
                $model->user_id = $data["id"];
                $model->save();
            } 
        } else {
            $model->save();
        } 
        if (count($model->itemError) > 0)
            echo json_encode($model->itemError) . "|||" . json_encode($model->tabError);
    }

    public function actionAjaxDelete() {
        $model = $this->loadModel($_POST["id"]);
        
        $sql = "Delete from usersettings where `key` = '".$model->key."'";
        Yii::app()->db->createCommand($sql)->execute();
        //$model->deleteModel();
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
        return $this->model("Usersettings", $id);
    }

}