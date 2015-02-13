<?php

class UsergroupsController extends Controller {

    //public $ajaxnew = "settings/usergroups/0";
    public $dataTableId = "usergroup";
    public $ajaxformsave = "settings/usergroups/ajaxformsave/";
    public $ajaxdelete = "settings/usergroups/ajaxdelete/";
    public $ajaxform = "settings/usergroups/ajaxform/";
    public $ajaxformtitle = "settings/usergroups/ajaxformtitle/";
    public $sAjaxSource = "settings/usergroups/ajaxjson";
    public $returnaftersafe = "settings/usergroups/";

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

    public function enumArray() {
        
    }

    public function actionTab() {
        $this->clearColumns();
        $this->addColumn(array(
                    "label" => $this->translate(ID),
                    "type" => "text",
                        )
        );
        $this->addColumn(array(
                    "label" => $this->translate(GROUP),
                    "type" => "text",
                        )
        );   
        $this->sfields = true;
        $this->renderPartial('tab', array());
    }

    public function actionAjaxJson() {
        $datas = $this->getModelArray("Usergroup", "", array());
        $jsonArr = array();
        foreach ((array) $datas as $data) {
            $data->load();
            $json = array();
            $json[] = $data->id;
            $json[] = $data->group;
            $json["DT_RowId"] = 'usergroup_' . $data->id;
            $json["DT_RowClass"] = 'usergroup';
            $jsonArr[] = $json;
        }
        echo json_encode(array('aaData' => $jsonArr));
    }

    public function actionView($id=0) {


        $model = $this->loadModel($id);

        $this->breadcrumbs[] = $model->group;

        if ($this->functionAccess("generaltab") != "block")
            $tabs["General"] = $this->generaltab($model->id);

        if ($model->id > 0) {
            if ($this->functionAccess("grouppermissiontab") != "block")
                $tabs["Group Permissions"] = $this->grouppermissiontab($model->id);
            if ($this->functionAccess("permissiontab") != "block")
                $tabs["Permissions"] = $this->permissiontab($model->id);
        }

        $this->render('index', array(
            'model' => $model,
            'tabs' => $tabs
        ));
    }

    public function actionAjaxForm() {


        $model = $this->loadModel($_POST["id"]);

        $this->breadcrumbs[] = $model->group;

        if ($this->functionAccess("generaltab") != "block")
            $tabs["General"] = $this->generaltab($model->id);

        if ($model->id > 0) {
            if ($this->functionAccess("grouppermissiontab") != "block")
                $tabs["Group Permissions"] = $this->grouppermissiontab($model->id);
            if ($this->functionAccess("permissiontab") != "block")
                $tabs["Permissions"] = $this->permissiontab($model->id);
        }

        $this->renderPartial('index', array(
            'model' => $model,
            'tabs' => $tabs
        ));
    }

    function generaltab($id) {
        $model = $this->loadModel($id);
        $this->formFields = array();
        $this->addFormField("text", "Group", "group");
        return $this->renderPartial('ajaxform', array(
                    'model' => $model,
                        ), true);
    }

    function grouppermissiontab($id) {
        $model = $this->loadModel($id);
        $datas = $this->getModelArray("Accessgroup", "", array());
        return $this->renderPartial('permissions/grouppermissions', array(
                    'model' => $model,
                    'datas' => $datas
                        ), true);
    }

    function permissiontab($id) {


        if ($this->functionAccess("permitionmodulestab") != "block")
            $accordions["Modules"] = $this->permitionmodulestab($id);
        if ($this->functionAccess("permitioncontrolerstab") != "block")
            $accordions["Controllers"] = $this->permitioncontrolerstab($id);
        if ($this->functionAccess("permitionactionstab") != "block")
            $accordions["Actions"] = $this->permitionactionstab($id);
        if ($this->functionAccess("permitionmodelstab") != "block")
            $accordions["Models"] = $this->permitionmodelstab($id);
        if ($this->functionAccess("permitionmodelfieldsstab") != "block")
            $accordions["Model Fields"] = $this->permitionmodelfieldsstab($id);
        if ($this->functionAccess("permitionmodeleavstab") != "block")
            $accordions["Model EAV"] = $this->permitionmodeleavstab($id);
        if ($this->functionAccess("permitionmodelgroupeavstab") != "block")
            $accordions["Model Group EAV"] = $this->permitionmodelgroupeavstab($id);

        return $this->renderPartial('permissions', array(
                    'model' => $model,
                    'accordions' => $accordions
                        ), true);
    }

    function permitionmodelgroupeavstab($id) {
        $model = $this->loadModel($id);
        $datas = $this->getModelArray("AttributeGroups", "", array());
        return $this->renderPartial('permissions/modelgroupeav', array(
                    'model' => $model,
                    'datas' => $datas
                        ), true);
    }

    function permitionmodeleavstab($id) {
        $model = $this->loadModel($id);
        $datas = $this->getModelArray("AttributeItems", "", array());
        return $this->renderPartial('permissions/modeleav', array(
                    'model' => $model,
                    'datas' => $datas
                        ), true);
    }

    function permitionmodulestab($id) {
        $model = $this->loadModel($id);

        $datas = $this->getModelArray("Accessmodule", "", array());

        return $this->renderPartial('permissions/modules', array(
                    'model' => $model,
                    'datas' => $datas
                        ), true);
    }

    function permitioncontrolerstab($id) {
        $model = $this->loadModel($id);
        $datas = $this->getModelArray("Accessmodulecontroller", "", array());
        return $this->renderPartial('permissions/controllers', array(
                    'model' => $model,
                    'datas' => $datas
                        ), true);
    }

    function permitionmodelstab($id) {
        $model = $this->loadModel($id);
        $datas = $this->getModelArray("Accessmodel", "", array());
        return $this->renderPartial('permissions/models', array(
                    'model' => $model,
                    'datas' => $datas
                        ), true);
    }

    function permitionmodelfieldsstab($id) {
        $model = $this->loadModel($id);
        $datas = $this->getModelArray("Accessmodelfield", "", array());
        return $this->renderPartial('permissions/modelfields', array(
                    'model' => $model,
                    'datas' => $datas
                        ), true);
    }

    function permitionactionstab($id) {
        $model = $this->loadModel($id);
        $datas = $this->getModelArray("Accessmodulecontrolleraction", "", array());
        return $this->renderPartial('permissions/actions', array(
                    'model' => $model,
                    'datas' => $datas
                        ), true);
    }

    public function actionAjaxaccesssave() {
        $model = $this->model($_POST["type"], $_POST["id"]);
        if ($model->id > 0) {
            $access = unserialize($model->access);
            $access[$_POST["group"]] = $_POST["access"];
            $model->access = serialize($access);
            $model->save();
        } else {
            $datas = $this->getModelArray($_POST["type"], "", array());
            foreach ($datas as $data) {
                $model = $this->model($_POST["type"], $data["id"]);
                $access = unserialize($model->access);
                $access[$_POST["group"]] = $_POST["access"];
                $model->access = serialize($access);
                $model->save();
            }
        }
    }

    public function actionAccessgroupupdate() {
        $model = $this->model("Accessgroup", $_POST["id"]);
        $accesses = unserialize($model->access);
        $datas = unserialize($model->data);

        foreach ((array) $accesses as $group => $access) {
            foreach ((array) $datas as $entity => $arr) {
                foreach ((array) $arr as $ids => $value) {
                    if ($value == 1) {
                        $model = $this->model($entity, $ids);
                        $accesses[$group] = $access;
                        $model->access = serialize($accesses);
                        $model->save();
                    }
                }
            }
        }
    }

    public function actionAjaxFormSave() {
        $model = $this->loadModel($_POST["id"]);
        $model->attributes = $_POST;
        $model->attrs = $_POST["attrs"];
        if ($model->id == 0) {
            $auth = Yii::app()->authManager;
            $auth->createRole($_POST["group"]);
            $auth->save();
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
            echo $this->translate("Edit Atrribute Group");
        } else {
            echo $this->translate("Create new Atrribute Group");
        }
    }

    public function actionCreate() {
        $model = new usergroups();
        if (Yii::app()->request->getPost("save")) {
            $this->save($model);
        }
        $this->render('index', array(
            'model' => $model
        ));
    }

    public function loadModel($id) {
        return $this->model("Usergroup", $id);
    }

}