<?php

class UserController extends Controller {

    public $dataTableId = "user";
    public $ajaxformsave = "users/user/ajaxformsave/";
    public $ajaxdelete = "users/user/ajaxdelete/";
    public $ajaxform = "users/user/ajaxform/";
    public $ajaxformtitle = "users/user/ajaxformtitle/";
    public $sAjaxSource = "users/user/ajaxjson";
    public $returnaftersafe = "users/user/";
    public $useServerSide = false;
    public $bPaginate = 'true';
    public $bFilter = 'true';
    public $media = "";
    public $pagename = "Users";

    public function beforeAction($action) {
        parent::beforeAction();
        if ($action->id == 'index')
            $this->breadcrumbs[] = $this->translate("Χρήστες");
        else
            $this->breadcrumbs[$this->translate("Χρήστες")] = Yii::app()->params['mainurl'] . "/users/user";
        

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
            "label" => $this->translate("Επίθετο"),
            "type" => "text",
                )
        );

        $this->addColumn(array(
            "label" => $this->translate("Last Login"),
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

        $sql = "Select id from user";

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
            $json[] = $model->getFirstName();
            $json[] = $model->getLastName();

            $json[] = $model->getLastLogin();

            //$json[] = "<a href='" . Yii::app()->params['mainurl'] . "/users/user/" . $model->id . "'>Edit</a>";
            $json["DT_RowId"] = 'user_' . $model->id;
            $json["DT_RowClass"] = 'user';
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

    public function actionView($id) {
        $model = $this->loadModel($id);

        $this->returntomain = "users/user";

        $this->breadcrumbs[] = $model->getFirstName() . " " . $model->getLastName();

        $tabs["Γενικά"] = "users/user/generaltab/" . $model->id; //$this->generaltab($model);

        $this->render('view', array(
            'model' => $model,
            'tabs' => $tabs,
        ));
    }




    public function actionAccessLogsTab($id = 0) {
        $model = $this->model("User", $id);

        $this->bAddnewpos = "''";
        $this->sAjaxSource = "users/user/accesslogsajaxjson/" . (int) $model->id;
        $this->addColumn(array(
            "label" => $this->translate(DATETIME),
            "type" => "text",
            "aoColumns" => array()
                )
        );
        $this->addColumn(array(
            "label" => $this->translate(USER),
            "type" => "text",
            "aoColumns" => array()
                )
        );
        $this->addColumn(array(
            "label" => $this->translate(ACTIONTYPE),
            "type" => "text",
            "aoColumns" => array()
                )
        );
        $this->addColumn(array(
            "label" => $this->translate(IP),
            "type" => "text",
            "aoColumns" => array()
                )
        );

        $this->addColumn(array(
            "label" => $this->translate(NOTES),
            "type" => "text",
            "aoColumns" => array()
                )
        );
        $this->renderPartial('view/accesslogs', array(
            'model' => $model,
        ));
    }

    public function actionAccessLogsAjaxJson($id = 0) {

        $sql = "Select id from accesslog where user_id = '" . $id . "'" . $queryStr;

        $cntPrd = Yii::app()->db->createCommand($sql)->queryAll();
        //$sql = "Select id from  sinallasomenos " . $queryStr . " " . $squery . " limit " . $_POST["iDisplayStart"] . ", " . $_POST["iDisplayLength"];
        $datas = Yii::app()->db->createCommand($sql)->queryAll();
        $jsonArr = array();
        foreach ((array) $datas as $data) {
            $model = $this->model("Accesslog", $data["id"]);
            $model->load();
            $json = array();
            $user = $this->model("User", $data->user_id);
            $json[] = $model->ts;
            $json[] = $model->user->email;
            $json[] = $model->actiontype;

            $json[] = $model->ip;

            $json[] = $model->notes;

            //$json["DT_RowId"] = 'accesslog_' . $data->id;
            //$json["DT_RowClass"] = 'accesslog';
            $jsonArr[] = $json;
        }
        echo json_encode(array('aaData' => $jsonArr));
    }

    public function actionAjaxForm() {
        $model = $this->loadModel($_POST["id"]);
        $this->addFormField("text", $this->translate("Email"), "email");
        $this->addFormField("password", $this->translate("Password"), "password");
        $this->addFormField("select", $this->translate("Ρολος"), "role", array("admin"=>"Admin","user"=>"User"));
        
        $this->renderPartial('ajaxform', array(
            'model' => $model,
            'specialityArr' => $specialityArr,
            'tabs' => $this->createTabs($model)
        ));
    }

    public function actionAjaxFormPersonalSave() {
        $model = $this->loadModel($_POST["id"]);
        $model->attributes = $_POST;
        $model->attrs = $_POST["attrs"];
        if ($_POST["password"]) {
            $model->password = md5($_POST["password"]);
        }
        $model->validationRules["required"] = array("email");
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

    public function actionAjaxFormSave() {
        $model = $this->loadModel($_POST["id"]);
        $model->attributes = $_POST;
        $model->attrs = $_POST["attrs"];
        if ($_POST["password"]) {
            $model->password = md5($_POST["password"]);
        }
        $model->validationRules["selectrequired"] = array("role");
        if ($model->id == 0)
            $isnew = true;
        
        $model->save();

        if ($isnew) {
            $datas = $this->getModelArray("Usersettings", "user=:user", array(":user" => Yii::app()->user->id));
            foreach ($datas as $data) {
                $usersettings = $this->model("Usersettings");
                $usersettings->key = $data->key;
                $usersettings->label = $data->label;
                $usersettings->multidata = $data->multidata;
                $usersettings->type = $data->type;
                $usersettings->value = $data->value;
                $usersettings->user_id = $model->id;
                $usersettings->save();
            }
        }

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
        return $this->model("User", $id);
    }

}