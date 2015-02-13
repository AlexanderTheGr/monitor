<?php

class AttributesController extends Controller {

    /**
     * @return array action filters
     */
    //public $ajaxnew = "settings/attributes/0";
    public $dataTableId = "attributes";
    public $ajaxformsave = "settings/attributes/ajaxformsave/";
    public $ajaxdelete = "settings/attributes/ajaxdelete/";
    public $ajaxform = "settings/attributes/ajaxform/";
    public $ajaxformtitle = "settings/attributes/ajaxformtitle/";
    public $sAjaxSource = "settings/attributes/ajaxjson";
    public $returnaftersafe = "settings/attributes/";

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
                   "label"=>$this->translate("ID"),
                   "type"=>"text",
                   ) 
                );        
        $this->addColumn(array(
                   "label"=>$this->translate("Type"),
                   "type"=>"text",
                   ) 
                ); 
         $this->addColumn(array(
                   "label"=>$this->translate("Identifier"),
                   "type"=>"text",
                   ) 
                ); 
        $this->addColumn(array(
                   "label"=>$this->translate("Title"),
                   "type"=>"text",
                   ) 
                );          
        $this->addColumn(array(
                   "label"=>$this->translate("Required"),
                   "type"=>"text",
                   ) 
                ); 
        $this->addColumn(array(
                   "label"=>$this->translate("Visible"),
                   "type"=>"text",
                   ) 
                );         
        $this->renderPartial('tab', array());
    }

    public function actionAjaxJson() {
        //$ls = Langtranslater::getSingleton();
        $datas = $this->getModelArray("Attributes", "", array());
        $jsonArr = array();
        $this->enumArray();
        foreach ($datas as $data) {
            $data->load();
            $json = array();
            $json[] = $data->id;
            $json[] = $this->enumArray["type"][$data->type];
            $json[] = $data->identifier;
            $json[] = $data->title;
            $json[] = $this->boolenArray[$data->required];
            $json[] = $this->boolenArray[$data->visible];
            $json["DT_RowId"] = 'attributes_' . $data->id;
            $json["DT_RowClass"] = 'attributes';
            $jsonArr[] = $json;
        }
        echo json_encode(array('aaData' => $jsonArr));
    }

    public function actionAjaxForm() {
        $model = $this->loadModel($_POST["id"]);
        //$ls = Langtranslater::getSingleton();
        $this->addFormField("select", $this->translate("Type"), "type", $this->enumArray["type"]);
        $this->addFormField("text", $this->translate("Identifier"), "identifier");
        $this->addFormField("text", $this->translate("Title"), "title");
        $this->addFormField("text", $this->translate("Css Style"), "css");
        $this->addFormField("boolen", $this->translate("Required"), "required");
        $this->addFormField("boolen", $this->translate("Visible"), "visible");
        $this->addFormField("boolen", $this->translate("Searchable"), "searchable");
        $this->addFormField("boolen", $this->translate("Unique"), "unique");
        $this->addFormField("boolen", $this->translate("Locked"), "locked");
        $this->addFormField("select", $this->translate("Validation"), "validation", $this->enumArray["validation"]);
        $this->addFormField("text", $this->translate("Select Data"), "select_data");

        $this->renderPartial('ajaxform', array(
            'model' => $model
        ));
    }

    public function actionAjaxFormSave() {
        $model = $this->loadModel($_POST["id"]);
        $model->attributes = $_POST;
        $model->attrs = $_POST["attrs"];
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
            echo $this->translate("Edit Atrribute");
        } else {
            echo $this->translate("Create new Atrribute");
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
        $model = Attributes::model()->findByPk($id);
        if ((int) $model->id == 0) {
            $model = new Attributes();
        }
        $model->load();
        $this->enumArray();
        return $model;
    }

}