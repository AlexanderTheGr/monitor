<?php

class CustomergroupController extends Controller {

    public $dataTableId = "customergroup";
    public $ajaxformsave = "customers/customergroup/ajaxformsave/";
    public $ajaxdelete = "customers/customergroup/ajaxdelete/";
    public $ajaxform = "customers/customergroup/ajaxform/";
    public $ajaxformtitle = "customers/customergroup/ajaxformtitle/";
    public $sAjaxSource = "customers/customergroup/ajaxjson";
    public $returnaftersafe = "customers/customergroup/";
    public $useServerSide = false;
    public $bPaginate = 'true';
    public $bFilter = 'true';
    public $media = "";
    public $pagename = "Customer Group";

    public function beforeAction($action) {
        parent::beforeAction();
        if ($action->id == 'index')
            $this->breadcrumbs[] = $this->translate("Χρήστες");
        else
            $this->breadcrumbs[$this->translate("Χρήστες")] = Yii::app()->params['mainurl'] . "/customers/customergroup";


        return true;
    }

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function enumArray() {
        $this->enumArray["role"] = array(0 => $this->translate(SELECT), 'admin' => $this->translate(ADMIN), 'doctor' => $this->translate(DOCTOR));
    }

    public function accessRules() {
        return array(
            array('allow',
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex() {

        $this->clearColumns();

        $this->addColumn(array(
            "label" => $this->translate(ID),
            "type" => "text",
                )
        );
        $this->addColumn(array(
            "label" => $this->translate("Όνομα"),
            "type" => "text",
                )
        );
        $this->addColumn(array(
            "label" => $this->translate(""),
            "type" => "text",
                )
        );
        //$this->useServerSide = true;
        $this->sfields = true;

        $this->render('index', array());
    }

    public function actionAjaxJson() {

        $_POST["iDisplayLength"];
        $_POST["iDisplayStart"];
        $_POST["sSearch"];
        $_POST["iSortCol_0"];

        $sql = "Select id from customergroup";

        $user = $this->loadModel(Yii::app()->user->id);
        $cntPrd = Yii::app()->db->createCommand($sql)->queryAll();
        $datas = Yii::app()->db->createCommand($sql)->queryAll();
        $jsonArr = array();
        foreach ((array) $datas as $data) {
            $model = $this->loadModel($data["id"]);
            $json = array();
            $profinciecies = array();
            $jobs = array();
            $f = false;


            $fields = array();
            $json[] = $model->id;
            $json[] = $model->title;

            $json[] = "<a href='" . Yii::app()->params['mainurl'] . "/customers/customergroup/" . $model->id . "'>Edit</a>";
            //$json["DT_RowId"] = 'customergroup_' . $model->id;
            //$json["DT_RowClass"] = 'customergrouppage';
            $jsonArr[] = $json;
        }
        echo json_encode(array('iTotalRecords' => count($cntPrd), 'iTotalDisplayRecords' => count($cntPrd), 'aaData' => $jsonArr));
    }

    public function actionItemsAjaxJson($id) {

        $_POST["iDisplayLength"];
        $_POST["iDisplayStart"];
        $_POST["sSearch"];
        $_POST["iSortCol_0"];

        $sql = "Select id from customergrouprule where `group` = '" . $id . "'";

        $user = $this->loadModel(Yii::app()->user->id);
        $cntPrd = Yii::app()->db->createCommand($sql)->queryAll();
        $datas = Yii::app()->db->createCommand($sql)->queryAll();
        $jsonArr = array();
        foreach ((array) $datas as $data) {
            $model = $this->model("Customergrouprule", $data["id"]);
            $json = array();
            $profinciecies = array();
            $jobs = array();
            $f = false;


            $fields = array();
            $json[] = $model->supplier;
            $json[] = $model->val;

            //$json[] = "<a href='" . Yii::app()->params['mainurl'] . "/customers/customergroup/" . $model->id . "'>Edit</a>";
            $json["DT_RowId"] = 'customergrouprule_' . $model->id;
            $json["DT_RowClass"] = 'customergrouprule';
            $jsonArr[] = $json;
        }
        echo json_encode(array('iTotalRecords' => count($cntPrd), 'iTotalDisplayRecords' => count($cntPrd), 'aaData' => $jsonArr));
    }

    public function actionAjaxFormTitle() {
        $model = $this->loadModel($_POST["id"]);

        if ($model->id > 0) {
            echo $this->translate(EDIT_USER) . ": " . $model->getFirstname() . " " . $model->getLastname();
        } else {
            echo $this->translate(CREATE_NEW_USER);
        }
    }

    public function actionItemAjaxFormTitle() {
        $model = $this->model("Customergrouprule", $_POST["id"]);

        if ($model->id > 0) {
            echo $model->supplier;
        } else {
            echo "New Item";
        }
    }

    public function actionView($id) {
        $model = $this->loadModel($id);

        $this->pagename = "Customer Group: ".$model->title;

        $this->returntomain = "customers/customergroup";

        $this->breadcrumbs[] = $model->getFirstName() . " " . $model->getLastName();

        $tabs["Γενικά"] = "customers/customergroup/generaltab/" . $model->id; //$this->generaltab($model);
        $tabs["Items"] = "customers/customergroup/itemstab/" . $model->id; //$this->generaltab($model);

        $this->render('view', array(
            'model' => $model,
            'tabs' => $tabs,
        ));
    }

    public function actionGeneraltab($id) {
        $model = $this->loadModel($id);
        //$model = $this->loadModel($_POST["id"]);
        
        $baseprices = array("item_pricew01"=>"Χονδρικης", "item_pricer01"=>"Χονδρικης με ΦΠΑ","item_pricew02"=>"Λιανικής","item_priceρ02"=>"Λιανικής με ΦΠΑ");
        
        $this->addFormField("text", $this->translate("Title"), "title");

        $this->addFormField("select", $this->translate("Base Price"), "base_price",$baseprices);
        
        $this->renderPartial('ajaxform', array(
            'model' => $model,
            'tabs' => $tabs,
        ));
    }

    public function actionItemstab($id) {

        $this->clearColumns();

        $this->addColumn(array(
            "label" => $this->translate("Supplier"),
            "type" => "text",
                )
        );
        $this->addColumn(array(
            "label" => $this->translate("Value"),
            "type" => "text",
                )
        );
        //$this->useServerSide = true;
        $this->sfields = true;
        $this->sAjaxSource = "customers/customergroup/itemsajaxjson/" . $id;
        $this->ajaxformsave = "customers/customergroup/itemajaxformsave/" . $id;
        $this->ajaxdelete = "customers/customergroupitem/ajaxdelete/" . $id;
        $this->ajaxform = "customers/customergroup/itemajaxform/" . $id;
        $this->ajaxformtitle = "customers/customergroup/itemajaxformtitle/";
        $this->dataTableId = "customergrouprule";
        $this->renderPartial('index', array(
            'model' => $model
        ));
    }

    public function actionAjaxForm() {
        $model = $this->loadModel($_POST["id"]);
        $this->addFormField("text", $this->translate("Title"), "title");

        $this->renderPartial('ajaxform', array(
            'model' => $model,
            'specialityArr' => $specialityArr,
            'tabs' => $this->createTabs($model)
        ));
    }
    public function actionItemAjaxForm($id=0) {
        $model = $this->model("Customergrouprule",$_POST["id"]);
        $model->group = $id;
        
        
        $sql = "Select distinct(item_mtrmanfctr) from product where item_mtrmanfctr != ''";

        $user = $this->loadModel(Yii::app()->user->id);
        $datas = Yii::app()->db->createCommand($sql)->queryAll();
        foreach($datas as $data) {
            $supplier[$data["item_mtrmanfctr"]] = $data["item_mtrmanfctr"];
        }
        
        ksort($supplier);
        $this->addFormField("select", $this->translate("Supplier"), "supplier",$supplier);
        $this->addFormField("text", $this->translate("Value"), "val");
        
        
        $this->addFormField("hidden", "", "group");
        $this->renderPartial('ajaxform', array(
            'model' => $model,
            'specialityArr' => $specialityArr,
            'tabs' => $this->createTabs($model)
        ));
    }
    
    public function actionAjaxFormSave() {
        $model = $this->loadModel($_POST["id"]);
        $model->attributes = $_POST;
        $model->attrs = $_POST["attrs"];
        if ($_POST["password"]) {
            $model->password = md5($_POST["password"]);
        }
        $model->validationRules["selectrequired"] = array("title");
        if ($model->id == 0)
            $isnew = true;

        $model->save();

        if (count($model->itemError) > 0)
            echo json_encode($model->itemError) . "|||" . json_encode($model->tabError);
        else {
            echo $model->id;
        }
    }
    public function actionItemAjaxFormSave() {
         $model = $this->model("Customergrouprule",$_POST["id"]);
        $model->attributes = $_POST;
        $model->attrs = $_POST["attrs"];
        if ($_POST["password"]) {
            $model->password = md5($_POST["password"]);
        }
        $model->validationRules["selectrequired"] = array("role");
        if ($model->id == 0)
            $isnew = true;

        $model->save();

        if (count($model->itemError) > 0)
            echo json_encode($model->itemError) . "|||" . json_encode($model->tabError);
        else {
            echo $model->id;
        }
    }
    
    public function actionAjaxDelete() {
        $model = $this->loadModel($_POST["id"]);
        $model->deleteModel();
    }

    function createTabs($model) {
        $tabs = array();
        if ($model->id > 0) {
            return $tabs;
        }
    }

    public function loadModel($id) {
        return $this->model("Customergroup", $id);
    }

}
