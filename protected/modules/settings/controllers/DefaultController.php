<?php

class DefaultController extends Controller {

    public $error = array();
    private $client = "";
    /**
     * @return array action filters
     */
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
        $this->breadcrumbs[] = $this->translate("SETTINGS");
        return true;
    }    
    
    public function actionIndex() {  
        
        if ($this->getAccess("settings", "system", "tab") != "block")
        $tabs["System"] = "settings/system/tab";

        //if ($this->getAccess("settings", "usersettings", "tab") != "block")
        $tabs["User Settings"] = "settings/usersettings/tab";        
        
        if ($this->getAccess("settings", "attributes", "tab") != "block")
        $tabs["Attibutes"] = "settings/attributes/tab";
        
        if ($this->getAccess("settings", "eavmodel", "tab") != "block")
        $tabs['Eav Model'] = 'settings/eavmodel/tab';
        
        if ($this->getAccess("settings", "attributegroups", "tab") != "block")
        $tabs['Attibutes Group'] = 'settings/attributegroups/tab';
        
        if ($this->getAccess("settings", "usergroups", "tab") != "block")
        $tabs['User Groups'] = 'settings/usergroups/tab';
        
        if ($this->getAccess("settings", "accessgroup", "tab") != "block")
        $tabs['Access Groups'] = 'settings/accessgroup/tab';
        
        
        $this->render('index', array(
            'tabs' => $tabs,
            'ls'=>$ls
        ));
        
        
    }
    public function enumArray() {
        
    }
    
    function actionGeneraltab() {
        $this->renderPartial('tab', array());
    }
    
    function actionAccessForm() {
        $model = AttributeItems::model()->findByPk($_POST["id"]);
        $this->renderPartial('accessform', array("model"=>$model));
    }
    
    function actionAccessFormSave() {
        $model = AttributeItems::model()->findByPk($_POST["id"]);
        $model->access = $_POST["access"];
        $model->save();
    }


    private function getAttributes() {
        return $this->getModelArray("Attributes", "", array());
    }

    function getAttributesFormData($attribute_ids = array(0)) {
        $datas = Yii::app()->db->createCommand()
                ->select('id,title')
                ->from('attributes')
                ->where(array('not in', 'id', $attribute_ids))
                ->queryAll();
        $out = array();
        foreach ($datas as $data) {
            $out[$data["id"]] = $data["title"];
        }
        return $out;
    }
   
    
    private function tables() {
        return $this->getModelArray("Tables", "", array());
    }

    private function settings() {
        $error = false;
        if ($_POST["savesettings"]) {

            foreach ((array) $_POST['setting'] as $id => $value) {
                $model = Settings::model()->findByPk($id);
                $model->value = $value;
                if (!$this->validateform($model)) {
                    $error = true;
                    break;
                }
            }
            if (!$error) {
                foreach ((array) $_POST['setting'] as $id => $value) {
                    $model = Settings::model()->findByPk($id);
                    $model->value = $value;
                    $model->save();
                }
            }
        }
        return $this->getModelArray("Settings", "client_id=:client_id", array(':client_id' => $this->client->id));
    }

    function validateform($model) {

        $this->error[$model->id] = false;
        if ($model->required == 1 AND $model->value == "") {
            $this->error[$model->id] = "Υποχρεωτικό";
        } else {
            if ($model->validation == "email") {
                $validator = new CEmailValidator;
                if ($validator->validateValue($model->value)) {

                    $this->error[$model->id] = false;
                } else {
                    $this->error[$model->id] = "Μη έγκυρο Email";
                }
            }
            if ($model->validation == "date") {
                if (strtotime($model->value)) {
                    $this->error[$model->id] = false;
                } else {
                    $this->error[$model->id] = "Μη έγκυρη Ημερομηνία";
                }
            }
            if ($model->validation == "number") {
                if ((int) $model->value > 0) {
                    $this->error[$model->id] = false;
                } else {
                    $this->error[$model->id] = "Μη έγκυρος Αριθμός";
                }
            }
        }
        if ($this->error[$model->id])
            return false;
        return true;
    }

}