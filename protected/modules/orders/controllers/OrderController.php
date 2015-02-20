<?php

class OrderController extends Controller {

    public $dataTableId = "order";
    public $ajaxformsave = "orders/order/ajaxformsave/";
    public $ajaxdelete = "orders/order/ajaxdelete/";
    public $ajaxform = "orders/order/ajaxform/";
    public $ajaxpage = "orders/order/edit/";
    public $ajaxformtitle = "orders/order/ajaxformtitle/";
    public $sAjaxSource = "orders/order/ajaxjson";
    public $returnaftersafe = "orders/order/";
    public $useServerSide = false;
    public $bPaginate = 'true';
    public $bFilter = 'true';
    public $media = "";
    public $pagename = "Παραγγελίες";

    public function beforeAction($action) {
        parent::beforeAction();
        if ($action->id == 'index')
            $this->breadcrumbs[] = $this->translate("Customers");
        else
            $this->breadcrumbs[$this->translate("Customers")] = Yii::app()->params['mainurl'] . "/orders/order";

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
            array('deny', // deny all orders
                'users' => array('*'),
            ),
        );
    }

    public function actionRetrievesoftonedata() {
        $params = array("softone_object" => "SALDOC", "eav_model" => "order", "model" => "Order", "list" => "B2B");

        $this->retrieveSoftoneData($params);
    }

    function retrieveSoftoneData($params = array()) {

        $params["softone_object"];
        $params["list"];
        $params["eav_model"];
        $params["model"];


        $softone = new Softone();
        $datas = $softone->retrieveData($params["softone_object"], $params["list"]);

        $fields = $softone->retrieveFields($params["softone_object"], $params["list"]);
        foreach ($fields as $field) {
            $attribute = Attributes::model()->findByAttributes(array('identifier' => $field));
            if ($attribute->id) {
                $attributeitem = AttributeItems::model()->findByAttributes(array('attribute_id' => $attribute->id, "eav_model" => $params["eav_model"]));
                $fld[$field] = $attributeitem->id;
            }
        }
        foreach ($datas as $data) {
            $zoominfo = $data["zoominfo"];
            $info = explode(";", $zoominfo);
            $model = $params["model"]::model()->findByAttributes(array('reference' => $info[1]));
            $model = $this->model($params["model"], $model->id);
            //$model->attributes = $params["attributes"];    

            $customer = Customer::model()->findByAttributes(array('reference' => $data["saldoc_trdr"]));
            $model->customer = 21100; //$customer->id;

            unset($data["zoominfo"]);
            unset($data["fld-1"]);
            $model->reference = $info[1];
            foreach ($data as $identifier => $dt) {
                $imporetedData[$fld[$identifier]] = addslashes($dt);
            }
            $model->attrs = $imporetedData;
            $model->save();
            $model->createOrderItems();
            //$model->setFlat();
            if ($i++ > 10)
                break;
        }
    }

    public function actionIndex() {

        $this->clearColumns();

        $this->addColumn(array(
            "label" => $this->translate(Ημερομηνία),
            "type" => "text",
                )
        );
        $this->addColumn(array(
            "label" => $this->translate("Κωδικός"),
            "type" => "text",
                )
        );
        $this->addColumn(array(
            "label" => $this->translate("Πελάτης"),
            "type" => "text",
                )
        );
        $this->addColumn(array(
            "label" => $this->translate("Χρήστης"),
            "type" => "text",
                )
        );
        $this->addColumn(array(
            "label" => $this->translate("Price"),
            "type" => "text",
                )
        );
        $this->addColumn(array(
            "label" => $this->translate("Softone"),
            "type" => "text",
                )
        );

        $this->addColumn(array(
            "label" => $this->translate("Ιμιτελής"),
            "type" => "text",
                )
        );

        $this->sfields = true;
        $this->bAddnewpos = "''";
        $this->render('index', array());
    }

    public function actionAjaxJson() {

        $_POST["iDisplayLength"];
        $_POST["iDisplayStart"];
        $_POST["sSearch"];
        $_POST["iSortCol_0"];



        if ($this->userrole == 'admin')
            $sql = "Select id from `order`";
        else
            $sql = "Select id from `order` where user = '" . Yii::app()->user->id . "'";

        $user = $this->loadModel(Yii::app()->user->id);
        $cntPrd = Yii::app()->db->createCommand($sql)->queryAll();
        $datas = Yii::app()->db->createCommand($sql)->queryAll();
        $jsonArr = array();

        foreach ((array) $datas as $data) {

            $model = $this->loadModel($data["id"]);
            $customer = $this->model("Customer", $model->customer);
            $user = $this->model("User", $model->user);
            $price = 0;
            foreach ($model->_items_ as $item) {
                if ($item->chk == 1) {
                    $price += $item->price * $item->qty;
                }
            }

            $json = array();
            $fields = array();
            $json[] = $model->insdate;
            $json[] = $model->fincode;
            $json[] = $customer->customer_name;
            $json[] = $user->email;
            $json[] = $price;

            $json[] = $model->reference ? "ΝΑΙ" : "OXI";
            $imitelis = 0;
            foreach ($model->_items_ as $item) {
                if ($item->chk == 0) {
                    $imitelis = 1;
                    break;
                }
            }

            $json[] = $imitelis ? "ΝΑΙ" : "OXI";


            $json["DT_RowId"] = 'order_' . $model->id;
            $json["DT_RowClass"] = 'orderpage';
            $jsonArr[] = $json;
        }

        echo json_encode(array('iTotalRecords' => count($cntPrd), 'iTotalDisplayRecords' => count($cntPrd), 'aaData' => $jsonArr));
    }

    public function actionAjaxFormTitle() {
        $model = $this->loadModel($_POST["id"]);

        if ($model->id > 0) {
            echo $this->translate("Edit Order") . ": " . $model->getFirstname() . " " . $model->getLastname();
        } else {
            echo $this->translate("Create New Order");
        }
    }

    public function actionEdit($id = 0) {
        //echo $id;
        $model = $this->loadModel($id);
        $this->returntomain = "orders/order";
        $customer = $this->model("Customer", $model->customer);
        $this->pagename = $model->fincode . " " . $customer->customer_name;

        $softone = new Softone();
        $SALDOC = $softone->getData("SALDOC", $model->reference, "");
        
        //if ($ITEM->data->SALDOC[0]->FULLYTRANSF != $model->fullytrans) {
        //$model->fullytrans = $ITEM->data->SALDOC[0]->FULLYTRANSF; 
        //$model->save();
        //}
        $tabs["Γενικά Στοιχεία "] = "orders/order/general/" . $model->id;
        $tabs["Είδη"] = "orders/order/orderitems/" . $model->id;

        $this->render('view', array(
            'model' => $model,
            'tabs' => $tabs,
        ));
    }

    public function actionEditorderitem() {
        //$order = $this->model("Order", $_POST["order"]);
        //$order->addOrderItem($_POST["product"]);
        if ($_POST["id"]) {
            $field = $_POST["field"];
            $orderitem = $this->model("OrderItem", $_POST["id"]);
            $orderitem->$field = $_POST["value"];
            $orderitem->save(false);
        }
    }

    public function actionEditorder() {
        //$order = $this->model("Order", $_POST["order"]);
        //$order->addOrderItem($_POST["product"]);
        if ($_POST["id"]) {
            $field = $_POST["field"];
            $orderitem = $this->model("Order", $_POST["id"]);
            $orderitem->$field = $_POST["value"];
            $orderitem->save(false);
        }
    }

    public function actionAddorderitem() {
        //$order = $this->model("Order", $_POST["order"]);
        //$order->addOrderItem($_POST["product"]);
        $orderitem = $this->model("OrderItem");
        $orderitem->product = $_POST["product"];
        $orderitem->order = $_POST["order"];
        $orderitem->qty = $_POST["qty"] > 0 ? $_POST["qty"] : 1;
        $orderitem->price = $_POST["price"];
        $orderitem->chk = 1;
        $orderitem->save(false);
    }

    public function actionOrderitems($id) {
        $model = $this->loadModel($id);
        $this->clearColumns();
        $this->sfields = false;
        $this->dataTableId = "orderitem";
        $this->sAjaxSource = "orders/order/orderitemsajaxJson/" . $id;
        $this->ajaxform = "orders/order/orderitemsajaxform/";
        $this->ajaxformsave = "orders/order/orderitemsajaxformsave/";
        $this->ajaxdelete = "orders/order/orderitemsajaxdelete/";

        
        $this->addColumn(array(
            "label" => $this->translate(""),
            "type" => "text",
                )
        );        
        $this->addColumn(array(
            "label" => $this->translate(""),
            "type" => "text",
                )
        );
        $this->addColumn(array(
            "label" => $this->translate(""),
            "type" => "text",
                )
        );
        $this->addColumn(array(
            "label" => $this->translate("Κωδικός"),
            "type" => "text")
        );
        $this->addColumn(array(
            "label" => $this->translate("Περιγραφή"),
            "type" => "text",
                )
        );
        $this->addColumn(array(
            "label" => $this->translate("Εργοστάσιο"),
            "type" => "text")
        );
        $this->addColumn(array(
            "label" => $this->translate("Τιμή"),
            "aoColumns" => array("sWidth" => '100'),
            "type" => "text")
        );
        $this->addColumn(array(
            "label" => $this->translate("Ποσότητα"),
            "aoColumns" => array("sWidth" => '100'),
            "type" => "text")
        );
        $this->addColumn(array(
            "label" => $this->translate("Σύνολο"),
            "aoColumns" => array("sWidth" => '100'),
            "type" => "text")
        );
        //$this->useServerSide = true;

        $this->renderPartial('orderitems', array("order" => $id, "model" => $model));
    }

    public function actionOrderitemsAjaxDelete() {
        $model = $this->model("OrderItem", $_POST["id"]);
        $model->deleteModel();
    }

    public function actionOrderitemsAjaxFormSave() {
        $model = $this->model("OrderItem", $_POST["id"]);
        $model->attributes = $_POST;
        $model->attrs = $_POST["attrs"];
        if ($model->id == 0)
            $isnew = true;
        $model->save(false);
        if (count($model->itemError) > 0)
            echo json_encode($model->itemError) . "|||" . json_encode($model->tabError);
        else {
            echo $model->id;
        }
    }

    public function actionOrderitemsajaxform() {
        $model = $this->model("OrderItem", $_POST["id"]);

        $this->addFormField("text", $this->translate("Price"), "price");
        $this->addFormField("text", $this->translate("QTY"), "qty");

        $this->renderPartial('orderitem', array(
            'model' => $model,
        ));
    }

    public function actionGeneral($id = 0) {



        $model = $this->loadModel($id);
        $this->returntomain = "orders/order";

        $sql = "Select id,flat_data from customer";
        $datas = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ((array) $datas as $data) {
            $customer = json_decode($data["flat_data"]);
            $listData[$customer->id] = $customer->attributeitems->customer_name;
        }

        $model->seriesnum = $model->id;
        $model->fincode = "ΠΑΡ-" . $model->id;
        if ($model->id == 0) {
            $model->insdate = date("Y-m-d H:s:i");
        }

        $this->addFormField("text", $this->translate("Αναζήτηση Πελάτη"), "customer_name", $listData, "width:500px");

        $this->addFormField("hidden", "", "customer", $listData);

        $this->addFormField("datetime", $this->translate("Ημερ.εισαγωγής"), "insdate", "", "width:500px");

        $this->addFormField("select", $this->translate("Αιτιολογία"), "comments",array("Για Τιμολόγιο"=>"Για Τιμολόγιο","Για Απόδειξη"=>"Για Απόδειξη","Για Δελτίο Αποστολής"=>"Για Δελτίο Αποστολής"));
        //$this->addFormField("text", $this->translate("Παραστατικό"), "fincode");


        $this->renderPartial('ajaxform', array(
            'model' => $model,
        ));
    }

    public function actionOrderitemsAjaxJson($id) {

        $_POST["iDisplayLength"];
        $_POST["iDisplayStart"];
        $_POST["sSearch"];
        $_POST["iSortCol_0"];
        $order = $this->loadModel($id);
        $sql = "Select id from `orderitem` where `order` = '" . $id . "'";

        $cntPrd = Yii::app()->db->createCommand($sql)->queryAll();
        $datas = Yii::app()->db->createCommand($sql)->queryAll();
        $jsonArr = array();

        
        
        $openorderssql = "Select id from `order` where `customer` = '" . $order->customer . "' AND fullytrans = 0";
        $openorderdatas = Yii::app()->db->createCommand($openorderssql)->queryAll();
        
        
        $list[0] = "Νέα παραγγελεία";
        foreach($openorderdatas as $openorderdata) {
           $list[$openorderdata["id"]] = $openorderdata["id"];
        }
        //$monitor = new Monitor();
        foreach ((array) $datas as $data) {

            $model = OrderItem::model()->findByPk($data["id"]); //$this->model("OrderItem",$data["id"]);

            $product = $this->model("Product", $model->product);

            $json = array();
            $fields = array();
            $json[] = "<img width=100 src='".$product->media()."' />";
            
            if ($model->chk == 1) {
                $json[] = "<button ".($order->fullytrans > 0 ? 'disabled' : '')." ref='" . $model->id . "' class='btn btn-danger delete_model'>Διαγραφή</button>";
            } elseif ($order->fullytrans  == 0) {
                $json[] = "<button ".($order->fullytrans > 0 ? 'disabled' : '')." ref='" . $model->id . "' class='btn btn-danger delete_model'>Διαγραφή</button>";
            } else {
                $json[] = CHtml::dropDownList('sendtoorderlist', $select,$list). "<button ref='" . $model->id . "' class='btn btn-success sendtoorder'>Αποστολή</button>";
            }
            
            $json[] = "<input ".($order->fullytrans > 0 ? 'disabled' : '')." type='checkbox' " . ($model->chk == 1 ? "checked" : "" ) . " ref='" . $model->id . "' field='chk' class='orderitem chk' value='1'/>";
            $json[] = $product->item_cccfxcode1;
            $json[] = $product->item_name;
            $json[] = $product->item_mtrmanfctr;
            
            
            $json[] = "<input ".($order->fullytrans > 0 ? 'disabled' : '')." style='width:100px' type='text' ref='" . $model->id . "' field='price' class='orderitem price' value='" . $model->price . "'/>";
            $json[] = "<input ".($order->fullytrans > 0 ? 'disabled' : '')." style='width:100px' type='text' ref='" . $model->id . "' field='qty' class='orderitem qty' value='" . $model->qty . "'/>";
            
            $json[] = $model->price * $model->qty;


            $json["DT_RowId"] = 'orderitem_' . $model->id;
            $json["DT_RowClass"] = '';
            $jsonArr[] = $json;
            if ($model->chk == 1)
                $price += $model->price * $model->qty;
        }

        if (count($jsonArr)) {
            $json = array();
            $json[] = "";
            $json[] = "";
            $json[] = "";
            $json[] = "";
            $json[] = "";
            $json[] = "";
            $json[] = "";
            $json[] = "Σύνολο";
            $json[] = $price;
            $jsonArr[] = $json;

            $json = array();
            $json[] = "";
            $json[] = "";
            $json[] = "";
            $json[] = "";
            $json[] = "";
            $json[] = "";
            $json[] = "";
            $json[] = "Εκπτωση";
            $json[] = "<input ".($order->fullytrans >= 0 ? 'disabled' : '')." style='width:100px' type='text' ref='" . $model->order . "' field='disc1prc' class='order disc1prc' value='" . $model->_order_->disc1prc . "'/>";
            $jsonArr[] = $json;

            $json = array();
            $json[] = "";
            $json[] = "";
            $json[] = "";
            $json[] = "";
            $json[] = "";
            $json[] = "";
            $json[] = "";
            $json[] = "Τελικό Σύνολο";
            $json[] = $price * (1 - ($model->_order_->disc1prc / 100));
            $jsonArr[] = $json;
        }
        $this->bAddnewpos = "''";
        $this->bFilter = false;

        echo json_encode(array('iTotalRecords' => count($cntPrd), 'iTotalDisplayRecords' => count($cntPrd), 'aaData' => $jsonArr));
    }

    
    public function actionSendtoorder() {
 
        $orderitem = $this->model("Orderitem",$_POST["id"]);
        if ($_POST["order"] > 0) {
            $orderitem->order = $_POST["order"];
            $orderitem->save();
        } else {
            $oldorder = $orderitem->_order_;
            $order = $this->loadModel($_POST["order"]);
            $order->user = Yii::app()->user->id;
            $order->customer = $oldorder->customer;
            $order->comments = $oldorder->comments;
            $order->customer_name = $oldorder->customer_name;
            $order->insdate = date("Y-m-d H:i:s");
            $order->save();
            
            $order->fincode = "ΠΑΡ-" . $order->id;
            $order->seriesnum = $order->id;
            $order->save();   
            $orderitem->order = $order->id;
            $orderitem->save();    
            
        }
        echo $order->id;
    }


    public function actionAjaxForm() {
        $model = $this->loadModel($_POST["id"]);

        //$this->addFormField("text", $this->translate("Username"), "username", "");
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

        if ($model->id == 0) {
            $isnew = true;
            $model->user = Yii::app()->user->id;
        }
        $model->save(false);


        $params = array("softone_object" => "SALDOC", "eav_model" => "order", "model" => $model, "list" => 'monitor');


        if (count($model->itemError) > 0)
            echo json_encode($model->itemError) . "|||" . json_encode($model->tabError);
        else {
            $model->seriesnum = $model->id;
            $model->fincode = "ΠΑΡ-" . $model->id;
            $model->save(false);
            echo $model->id;
        }
    }

    function actionCalculateOrder() {
        $model = $this->loadModel($_POST["id"]);
        $params = array("softone_object" => "SALDOC", "eav_model" => "order", "model" => $model, "list" => 'monitor');
        $this->calculateOrder($params);
    }

    function calculateOrder($params) {
        $params["softone_object"];
        $params["eav_model"];
        $model = $params["model"];
        $object = $params["softone_object"];
        $softone = new Softone();
        $fields = $softone->retrieveFields($object, $params["list"]);
        if ($model->reference) {
            $objectArr = array();
            $objectArr[0]["TRDR"] = $model->_customer_->reference;
            $objectArr[0]["SERIESNUM"] = $model->seriesnum;
            $objectArr[0]["FINCODE"] = $model->fincode;
            $objectArr[0]["PAYMENT"] = 1000;
            //$objectArr[0]["TFPRMS"] = $model->tfprms;
            //$objectArr[0]["FPRMS"] = $model->fprms;
            $objectArr[0]["SERIES"] = 7023; //$model->series;
            //$objectArr[0]["COMMENTS"] = $model->comments;

            $dataOut[$object] = (array) $objectArr;
            $k = 9000001;
            foreach ($model->_items_ as $item) {
                $dataOut["ITELINES"][] = array("VAT" => 1310, "LINENUM" => $k++, "MTRL" => $item->_product_->reference, "QTY1" => $item->qty);
            }
            //print_r($dataOut);
            $out = $softone->calculate((array) $dataOut, $object, $model->reference);

            print_r($out);
            foreach ($model->_items_ as $item) {
                $item->delete();
            };

            foreach ((array) $out->data->ITELINES as $item) {
                if ($item->chk == 1) {
                    $product = Product::model()->findByAttributes(array('reference' => $item->MTRL));
                    $orderitem = $this->model("OrderItem");
                    $orderitem->product = $product->id;
                    $orderitem->order = $model->id;
                    $orderitem->qty = $item->QTY1;
                    $orderitem->price = $item->PRICE;
                    $orderitem->save();
                }
            }
        } else {
            $objectArr = array();
            $objectArr[0]["TRDR"] = $model->_customer_->reference;
            $objectArr[0]["SERIESNUM"] = $model->seriesnum;
            $objectArr[0]["FINCODE"] = $model->fincode;
            $objectArr[0]["PAYMENT"] = 1000;
            //$objectArr[0]["TFPRMS"] = $model->tfprms;
            //$objectArr[0]["FPRMS"] = $model->fprms;
            $objectArr[0]["SERIES"] = 7023; //$model->series;
            //$objectArr[0]["DISC1PRC"] = $model->disc1prc;
			//$objectArr[0]["COMMENTS"] = $model->comments;

            $dataOut[$object] = (array) $objectArr;
            $k = 9000001;
            foreach ($model->_items_ as $item) {
                if ($item->chk == 1)
                    $dataOut["ITELINES"][] = array("VAT" => 1310,
                        "LINENUM" => $k++,
                        "MTRL" => $item->_product_->reference,
                        "QTY1" => $item->qty);
            }
            print_r($dataOut);
            $out = $softone->calculate((array) $dataOut, $object, (int) 0);

            foreach ($model->_items_ as $item) {
                $item->delete();
            };

            foreach ($out->data->ITELINES as $item) {
                if ($item->chk == 1) {
                    $product = Product::model()->load()->findByAttributes(array('reference' => $item->MTRL));
                    $orderitem = new OrderItem;
                    $orderitem->product = $product->id;
                    $orderitem->order = $model->id;
                    $orderitem->qty = $item->QTY;
                    $orderitem->price = $item->PRICE;
                    $orderitem->save(false);
                }
            }

            if ($out->id > 0) {
                $model->reference = $out->id;
                $model->save();
            }
            print_r($out);
        }
    }

    function actionSaveSoftone() {
        $model = $this->loadModel($_POST["id"]);
        $params = array("softone_object" => "SALDOC", "eav_model" => "order", "model" => $model, "list" => 'monitor');
        $this->saveSoftone($params);
    }

    function saveSoftone($params) {
        $params["softone_object"];
        $params["eav_model"];
        $model = $params["model"];

        $object = $params["softone_object"];
        $softone = new Softone();
        $fields = $softone->retrieveFields($object, $params["list"]);
        if ($model->reference) {
            $objectArr = array();
            $objectArr[0]["TRDR"] = $model->_customer_->reference;
            $objectArr[0]["SERIESNUM"] = $model->seriesnum;
            $objectArr[0]["FINCODE"] = $model->fincode;
            $objectArr[0]["PAYMENT"] = 1000;
            //$objectArr[0]["TFPRMS"] = $model->tfprms;
            //$objectArr[0]["FPRMS"] = $model->fprms;
            $objectArr[0]["SERIES"] = 7021; //$model->series;
            $objectArr[0]["DISC1PRC"] = $model->disc1prc;
            $objectArr[0]["COMMENTS1"] = $model->comments;

            $dataOut[$object] = (array) $objectArr;
            //$k = 9000001;
            foreach ($model->_items_ as $item) {
                if ($item->chk == 1)
                    $dataOut["ITELINES"][] = array("VAT" => 1310, "LINENUM" => $k++, "MTRL" => $item->_product_->reference, "PRICE" => $item->price, "QTY1" => $item->qty);
            }
            print_r($dataOut);
            $out = $softone->setData((array) $dataOut, $object, $model->reference);
            print_r($out);
        } else {
            $objectArr = array();
            $objectArr[0]["TRDR"] = $model->_customer_->reference;
            $objectArr[0]["SERIESNUM"] = $model->seriesnum;
            $objectArr[0]["FINCODE"] = $model->fincode;
            $objectArr[0]["PAYMENT"] = 1000;
            //$objectArr[0]["TFPRMS"] = $model->tfprms;
            //$objectArr[0]["FPRMS"] = $model->fprms;
            $objectArr[0]["SERIES"] = 7021; //$model->series;
            $objectArr[0]["DISC1PRC"] = $model->disc1prc;
            $objectArr[0]["COMMENTS1"] = $model->comments;

            $dataOut[$object] = (array) $objectArr;
            //$k = 9000001;
            foreach ($model->_items_ as $item) {
                if ($item->chk == 1)
                    $dataOut["ITELINES"][] = array("VAT" => 1310, "LINENUM" => $k++, "MTRL" => $item->_product_->reference, "PRICE" => $item->price, "QTY1" => $item->qty);
            }
            print_r($dataOut);
            $out = $softone->setData((array) $dataOut, $object, (int) 0);

            if ($out->id > 0) {
                $model->reference = $out->id;
                $model->save();
            }

            //print_r($out);
        }
    }

    public function getBrands() {
        return Brand::model()->findAll();
    }

    public function vehiclesBlock($model) {
        $this->renderPartial('vehicles', array(
            'model' => $model,
        ));
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
        return $this->model("Order", $id);
    }

}
