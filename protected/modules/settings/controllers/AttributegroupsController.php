<?php
class AttributeGroupsController extends Controller {

    //public $ajaxnew = "settings/attributegroups/0";
    public $dataTableId = "attributegroups";
    public $ajaxformsave = "settings/attributegroups/ajaxformsave/";
    public $ajaxdelete = "settings/attributegroups/ajaxdelete/";
    public $ajaxform = "settings/attributegroups/ajaxform/";
    public $ajaxformtitle = "settings/attributegroups/ajaxformtitle/";
    public $sAjaxSource = "settings/attributegroups/ajaxjson";
    public $returnaftersafe = "settings/attributegroups/";
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
        $datas = $this->getModelArray("AttributeGroups", "", array());
        $jsonArr = array();
        foreach((array)$datas as $data) {
            $data->load();
            $json = array();
            $json[] = $data->id;
            $json[] = $data->group;
            $json["DT_RowId"] = 'attributegroups_' . $data->id;
            $json["DT_RowClass"] = 'attributegroups';
            $jsonArr[] = $json;
        }
        echo json_encode(array('aaData' => $jsonArr));
    }

    public function actionAjaxForm() {
        $model = $this->loadModel($_POST["id"]);
        $this->addFormField("text", "Group", "group");
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
            echo json_encode($model->itemError)."|||".json_encode($model->tabError);
    }

    public function actionAjaxDelete() {
        $model = $this->loadModel($_POST["id"]);
        $model->deleteModel();
    }

    public function actionAjaxFormTitle() {
        //$ls = Langtranslater::getSingleton();
        $model = $this->loadModel($_POST["id"]);
        if ($model->id > 0) {
            echo $this->translate("Edit Atrribute Group");
        } else {
            echo $this->translate("Create new Atrribute Group");
        }
    }

    public function actionCreate() {
        $model = new AttributeGroups();
        if (Yii::app()->request->getPost("save")) {
            $this->save($model);
        }
        $this->render('index', array(
            'model' => $model
        ));
    }

    public function loadModel($id) {
        $model = AttributeGroups::model()->findByPk($id);
        if ((int) $model->id == 0) {
            $model = new AttributeGroups();
        }
        $model->load();
        $this->enumArray();
        return $model;
    }

}
