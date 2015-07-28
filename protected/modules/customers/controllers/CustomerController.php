<?php

class CustomerController extends Controller {

    public $dataTableId = "customer";
    public $ajaxformsave = "customers/customer/ajaxformsave/";
    public $ajaxdelete = "customers/customer/ajaxdelete/";
    public $ajaxform = "customers/customer/ajaxform/";
    public $ajaxpage = "customers/customer/edit/";
    public $ajaxformtitle = "customers/customer/ajaxformtitle/";
    public $sAjaxSource = "customers/customer/ajaxjson";
    public $returnaftersafe = "customers/customer/";
    public $useServerSide = false;
    public $bPaginate = 'true';
    public $bFilter = 'true';
    public $media = "";
    public $pagename = "Πελάτες";

    public function beforeAction($action) {
        parent::beforeAction();
        if ($action->id == 'index')
            $this->breadcrumbs[] = $this->translate("Customers");
        else
            $this->breadcrumbs[$this->translate("Customers")] = Yii::app()->params['mainurl'] . "/customers/customer";

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
            array('deny', // deny all customers
                'users' => array('*'),
            ),
        );
    }

    public function actionRetrievesoftonedata() {
        $params = array("softone_object" => "CUSTOMER", "eav_model" => "customer", "model" => "Customer", "list" => "monitor");
        $this->retrieveSoftoneData($params);
    }

    function retrieveSoftoneData($params = array()) {
        $params["softone_object"];
        $params["list"];
        $params["eav_model"];
        $params["model"];
        $softone = new Softone();
        //$datas = $softone->retrieveData($params["softone_object"], $params["list"]);
        
        $sql = "Select max(ts) as t from customer";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        $date = date("Y-m-d",strtotime($data["t"]));
        $filters = "CUSTOMER.UPDDATE=" . $date . "&CUSTOMER.UPDDATE_TO=" . date("Y-m-d");
        $datas = $softone->retrieveData($params["softone_object"], $params["list"]);
        /*
        $fields = $softone->retrieveFields($params["softone_object"], $params["list"]);
        foreach ($fields as $field) {
            $attribute = Attributes::model()->findByAttributes(array('identifier' => $field));
            if ($attribute->id) {
                $attributeitem = AttributeItems::model()->findByAttributes(array('attribute_id' => $attribute->id, "eav_model" => $params["eav_model"]));
                $fld[$field] = $attributeitem->id;
            }
        }
         * 
         */

        foreach ($datas as $data) {
            $zoominfo = $data["zoominfo"];
            $info = explode(";", $zoominfo);
            $model = $params["model"]::model()->findByAttributes(array('customer_code' => $data["customer_code"]));
            $model = $this->model($params["model"], $model->id);
            //$model->attributes = $params["attributes"];    

            //$customer = Customer::model()->findByAttributes(array('reference' => $data["saldoc_trdr"]));
            //$model->catalogue = 1; //$customer->id;

            unset($data["zoominfo"]);
            unset($data["fld-1"]);
            $model->reference = $info[1];
            foreach ($data as $identifier => $dt) {
                $imporetedData[$identifier] = addslashes($dt);
            }
            print_r($imporetedData);
            $model->attributes = $imporetedData;
            $model->save(false);
            //$model->setFlat();
            //if ($i++ > 100)
            //    break;
        }
    }

    public function actionIndex() {

        $this->clearColumns();

        $this->addColumn(array(
            "label" => $this->translate(ID),
            "type" => "text",
                )
        );
        $this->addColumn(array(
            "label" => $this->translate("Customer Code"),
            "type" => "text",
                )
        );
        $this->addColumn(array(
            "label" => $this->translate("Customer Name"),
            "type" => "text",
                )
        );
        $this->addColumn(array(
            "label" => $this->translate("Customer Afm"),
            "type" => "text",
                )
        );
        $this->bAddnewpos = "''";
        //$this->useServerSide = true;
        $this->sfields = true;

        $this->render('index', array());
    }

    public function actionSearch() {
        $sql = "Select * from customer where "
                . "customer_name LIKE '%" . $_GET["term"] . "%'"
                . " OR customer_afm LIKE '%" . $_GET["term"] . "%'"
                . " OR customer_code LIKE '%" . $_GET["term"] . "%'"
                . " limit 0,100";
        $datas = Yii::app()->db->createCommand($sql)->queryAll();
        $out = array();
        foreach ((array) $datas as $data) {
            //$data["flat_data"] = "";
            if ($data["flat_data"] == "") {
                $model = $this->loadModel($data["id"]);
                $model->load();
                $model->tecdoc_code = $model->getItemCode();
                $model->tecdoc_supplier_id = $model->getItemMtrmanfctr();
                $this->updatetecdoc($model);
                $model->setFlat();
                $model = json_decode($model->flat_data);
            } else {
                $model = json_decode($data["flat_data"]);
            }
            $json["id"] = $data["id"];
            $json["value"] = $model->customer_name . " ($model->customer_afm - $model->customer_code) ";
            $json["label"] = $model->customer_name . " ($model->customer_afm - $model->customer_code) ";
            
            
            $out[] = $json;
        }
        echo json_encode($out);
    }

    public function actionAjaxJson() {

        $_POST["iDisplayLength"];
        $_POST["iDisplayStart"];
        $_POST["sSearch"];
        $_POST["iSortCol_0"];

        $sql = "Select id,flat_data from customer";

        $user = $this->loadModel(Yii::app()->user->id);
        $cntPrd = Yii::app()->db->createCommand($sql)->queryAll();
        $datas = Yii::app()->db->createCommand($sql)->queryAll();
        $jsonArr = array();
        foreach ((array) $datas as $data) {
            //$model = $this->loadModel($data["id"]);
            if ($data["flat_data"] == "") {
                $model = $this->loadModel($data["id"]);
                $model->load();
                $model->setFlat();
                $model = json_decode($model->flat_data);
                //echo "SS";
            } else {
                $model = json_decode($data["flat_data"]);
            }
            $json = array();
            $fields = array();
            $json[] = $model->id;
            $json[] = $model->customer_code;
            $json[] = $model->customer_name; // "";//$model->getCustomerName();
            $json[] = $model->customer_afm; //"";//$model->getCustomerAfm();
            //$json[] = "<a href='" . Yii::app()->params['mainurl'] . "/customers/customer/" . $model->id . "'>Edit</a>";
            $json["DT_RowId"] = 'customer_' . $model->id;
            $json["DT_RowClass"] = 'customerpage';
            $jsonArr[] = $json;
            //if ($i++ > 10) break;
        }
        echo json_encode(array('iTotalRecords' => count($cntPrd), 'iTotalDisplayRecords' => count($cntPrd), 'aaData' => $jsonArr));
    }

    public function actionAjaxFormTitle() {
        $model = $this->loadModel($_POST["id"]);

        if ($model->id > 0) {
            echo $this->translate("Edit Customer") . ": " . $model->getFirstname() . " " . $model->getLastname();
        } else {
            echo $this->translate("Create New Customer");
        }
    }
    public function actionAjaxrulesformtitle() {
        //$model = $this->loadModel($_POST["id"]);
        $model = $this->model("Customerrule",$_POST["id"]);
        if ($model->id > 0) {
            echo $this->translate("Edit Rule") . ": " . $model->supplier;
        } else {
            echo $this->translate("Create New Rule");
        }
    }
    
    public function actionEdit($id = 0) {
        //echo $id;
        $model = $this->loadModel($id);

        $softone = new Softone();
        $data = $softone->getData("CUSTOMER", $model->reference);
        //print_r($data);

        $this->returntomain = "customers/customer";
        
        $trdcategory = array(
            3000=>"Πελάτες Εσωτερικού Με Κανονικό Φ.Π.Α.",
            3001=>"Πελάτες Εσωτερικού Με Μειωμένο Φ.Π.Α.",
            3100=>"Πελάτες Εξωτερικού με ΑΠΑΛΛΑΓΗ ΦΠΑ",
            );
        
        $this->addFormField("text", $this->translate("Κωδικός"), "customer_code", "");
        
        $this->addFormField("select", $this->translate("Group"), "group", CHtml::listData(Customergroup::model()->findAll(), 'id', 'title'));
        
        $this->addFormField("select", $this->translate("Λογ Κατηγορία"), "customer_trdcategory", $trdcategory, 'id', 'title');
        
        
        $this->addFormField("text", $this->translate("<b>Επωνυμία</b><span style='color:red'>*</span>"), "customer_name", "");
        
        
        
        $this->addFormField("text", $this->translate("ΑΦΜ"), "customer_afm", "");

        $this->addFormField("text", $this->translate("Διεύθυνση"), "customer_address", "");
        $this->addFormField("text", $this->translate("Περιοχή"), "customer_district", "");
        $this->addFormField("text", $this->translate("TK"), "customer_zip", "");
        $this->addFormField("text", $this->translate("Τηλ 1"), "customer_phone01", "");
        $this->addFormField("text", $this->translate("Τηλ 2"), "customer_phone02", "");

        $this->addFormField("text", $this->translate("FAX"), "customer_fax", "");
        
        $this->addFormField("text", $this->translate("Webpage"), "customer_webpage", "");
        $this->addFormField("text", $this->translate("Email"), "customer_email", "");

        

        $this->render('view', array(
            'model' => $model,
            'specialityArr' => $specialityArr,
            'tabs' => $this->createEditTabs($model)
        ));
    }
    function createEditTabs($model) {
        $tabs = array();
        $tabs[] = array("title"=>"Τιμολογιακή Πολιτική","reg"=>"customers/customer/rules/" . $model->id);
        if ($model->id > 0) {
            return $tabs;
        }
    }
    
    public function actionRules($id = 0) {
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
        $this->sAjaxSource = "customers/customer/ajaxrulesjson/".$id;
        $this->dataTableId = "customerrule";
        $this->ajaxdelete = "customers/customer/ajaxrulesdelete/";
        //$this->useServerSide = true;
        $this->ajaxformtitle = "customers/customer/ajaxrulesformtitle/";
        $this->ajaxform = "customers/customer/ajaxrulesform/".$id;
        $this->ajaxformsave = "customers/customer/ajaxforrulesmsave/";
        $this->sfields = true;

        $this->renderPartial('rules', array());
    }
    public function actionAjaxRulesJson($id = 0) {
        $sql = "Select id from customerrule where customer = '".$id."'";
        
        $user = $this->loadModel(Yii::app()->user->id);
        $cntPrd = Yii::app()->db->createCommand($sql)->queryAll();
        $datas = Yii::app()->db->createCommand($sql)->queryAll();
        $jsonArr = array();
        foreach ((array) $datas as $data) {
            $model = $this->model("Customerrule",$data["id"]);
            $json = array();
            $fields = array();
            $json[] = $model->supplier;
            $json[] = $model->val; // "";//$model->getCustomerName();
            $json["DT_RowId"] = 'customerrule_' . $model->id;
            $json["DT_RowClass"] = 'customerrule';
            $jsonArr[] = $json;
            //if ($i++ > 10) break;
        }
        echo json_encode(array('iTotalRecords' => count($cntPrd), 'iTotalDisplayRecords' => count($cntPrd), 'aaData' => $jsonArr));
    }
    public function actionAjaxForm() {
        $model = $this->loadModel($_POST["id"]);


        $this->addFormField("text", $this->translate("Username"), "username", "");
        $this->renderPartial('ajaxform', array(
            'model' => $model,
            'specialityArr' => $specialityArr,
            'tabs' => $this->createTabs($model)
        ));
    }
    
    public function actionAjaxRulesForm($id = 0) {
        
        $model = $this->model("Customerrule",$_POST["id"]);
        
        $model->customer = $id;
        
        $sql = "SELECT distinct(item_mtrmanfctr) FROM `product` order by item_mtrmanfctr";
        $datas = Yii::app()->db->createCommand($sql)->queryAll();
        
        foreach($datas as $data) {
            $supliers[$data["item_mtrmanfctr"]] = $data["item_mtrmanfctr"];
        }
        $this->addFormField("select", "Supplier","supplier",$supliers);
        $this->addFormField("text", "Value", "val", "");
        $this->addFormField("hidden", "", "customer", $id);
        
        $this->renderPartial('ajaxform', array(
            'model' => $model,
            'specialityArr' => $specialityArr
        ));
    }
  
    public function actionAjaxforrulesmsave() {
        $model = $this->model("Customerrule",$_POST["id"]);
        $model->attributes = $_POST;
        $model->attrs = $_POST["attrs"];
        $model->save();
    }

    public function actionAjaxFormSave() {
        $model = $this->loadModel($_POST["id"]);
        $model->attributes = $_POST;
        $model->attrs = $_POST["attrs"];
        if ($_POST["password"]) {
            $model->password = md5($_POST["password"]);
        }
        $model->validationRules["required"] = array("customer_name");
        //$model->validationRules["selectrequired"] = array("role");


        if ($model->id == 0)
            $isnew = true;

        $model->save();
        

        $params = array("softone_object" => "CUSTOMER", "eav_model" => "customer", "model" => $model);
        
        if (count($model->itemError) > 0)
            echo json_encode($model->itemError) . "|||" . json_encode($model->tabError);
        else {
            
            echo $model->id;
            if ($isnew) {
                $model->customer_code = "ΠΠ".$model->id;
                $model->save();
            }
            
            $model->setFlat();
            
            //$this->saveSoftone($params);
        }
    }
    
   
    public function actionAjaxRulesDelete() {
        $model = $this->model("customerrule",$_POST["id"]);
        $model->deleteModel();
    }
    public function actionAjaxDelete() {
        $model = $this->loadModel($_POST["id"]);
        $model->deleteModel();
    }

    public function actionSoftone() {
        $monitor = new Monitor();
        $monitor = retrieveCustomers();
        //print_r($monitor);
    }

    function createTabs($model) {
        $tabs = array();
        if ($model->id > 0) {
            return $tabs;
        }
    }

    public function loadModel($id) {
        return $this->model("Customer", $id);
    }

}
