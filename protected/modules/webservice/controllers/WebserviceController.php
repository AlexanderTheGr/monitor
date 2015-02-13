<?php

class WebserviceController extends Controller {

    public $dataTableId = "webservice";
    public $ajaxformsave = "webservice/webservice/ajaxformsave/";
    public $ajaxdelete = "webservice/webservice/ajaxdelete/";
    public $ajaxform = "webservice/webservice/ajaxform/";
    public $ajaxformtitle = "webservice/webservice/ajaxformtitle/";
    public $sAjaxSource = "webservice/webservice/ajaxjson";
    public $returnaftersafe = "webservice/webservice/";
    public $useServerSide = false;
    public $bPaginate = 'true';
    public $bFilter = 'true';
    public $media = "";
    public $pagename = "Webservices";

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
            "label" => $this->translate("Name"),
            "type" => "text",
                )
        );
        $this->addColumn(array(
            "label" => $this->translate("Url"),
            "type" => "text",
                )
        );



        //$this->useServerSide = true;
        $this->sfields = true;
        $this->render('index');
    }

    public function actionAjaxJson() {

        $_POST["iDisplayLength"];
        $_POST["iDisplayStart"];
        $_POST["sSearch"];
        $_POST["iSortCol_0"];


        $sql = "Select id from webservice";

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
            $json[] = $model->name;
            $json[] = $model->url;
            //$json[] = "<a href='" . Yii::app()->params['mainurl'] . "/users/user/" . $model->id . "'>Edit</a>";
            $json["DT_RowId"] = 'webservice_' . $model->id;
            $json["DT_RowClass"] = 'webservice';
            $jsonArr[] = $json;
        }
        echo json_encode(array('iTotalRecords' => count($cntPrd), 'iTotalDisplayRecords' => count($cntPrd), 'aaData' => $jsonArr));
    }

    public function actionAjaxFormTitle() {
        $model = $this->loadModel($_POST["id"]);

        if ($model->id > 0) {
            echo $this->translate("Edit Webservice") . ": " . $model->getFirstname() . " " . $model->getLastname();
        } else {
            echo $this->translate("Create New Webservice");
        }
    }

    public function actionAjaxForm() {
        $model = $this->loadModel($_POST["id"]);

        $this->addFormField("text", "Name", "name");
        $this->addFormField("text", "Url", "url");

        $this->renderPartial('ajaxform', array(
            'model' => $model,
        ));
    }

    public function actionAjaxFormSave() {
        $model = $this->loadModel($_POST["id"]);
        $model->attributes = $_POST;
        $model->attrs = $_POST["attrs"];
        $model->save();
        if (count($model->itemError) > 0)
            echo json_encode($model->itemError) . "|||" . json_encode($model->tabError);
        else {
            echo $model->id;
        }
    }

    public function loadModel($id) {
        return $this->model("Webservice", $id);
    }

}
