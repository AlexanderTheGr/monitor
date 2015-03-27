<?php

class ProductController extends Controller {

    public $dataTableId = "product";
    public $ajaxformsave = "product/product/ajaxformsave/";
    public $ajaxdelete = "product/product/ajaxdelete/";
    public $ajaxform = "product/product/ajaxform/";
    public $ajaxpage = "product/product/edit/";
    public $ajaxformtitle = "product/product/ajaxformtitle/";
    public $sAjaxSource = "product/product/ajaxjson";
    public $returnaftersafe = "product/product/";
    public $useServerSide = false;
    public $bPaginate = 'true';
    public $bFilter = 'true';
    public $media = "";
    public $pagename = "Products";
    public $webservice = 11632;

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

    public function actionRetrievesoftonedata() {
        $params = array("softone_object" => "ITEM", "eav_model" => "product", "model" => "Product", "list" => "monitor");
        //$params["filters"] = "ITEM.MTRMANFCTR=180";
        $params["attributes"] = array("catalogue" => 1);
        $this->retrieveSoftoneData($params);
    }

    function retrieveSoftoneData($params = array()) {
        $params["softone_object"];
        $params["list"];
        $params["eav_model"];
        $params["model"];
        $softone = new Softone();


        $sql = "Select max(ts) as t from product";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        $date = date("Y-m-d", strtotime($data["ts"]));
        $date = "2015-03-15";
        $filters = "ITEM.UPDDATE=" . $date . "&ITEM.UPDDATE_TO=" . date("Y-m-d");
        //$filters = "ITEM.MTRPLACE=*";
        $datas = $softone->retrieveData($params["softone_object"], $params["list"], $filters);
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

        $i = 0;
        foreach ($datas as $data) {

            $zoominfo = $data["zoominfo"];
            $info = explode(";", $zoominfo);

            //$model = $params["model"]::model()->findByAttributes(array('item_code' => $data["item_code"]));

            $model = $params["model"]::model()->findByAttributes(array('reference' => $info[1]));
            
            if ($model->id > 0) {
                $i++;
            }

            $model = $this->model($params["model"], $model->id);

            $model->catalogue = 1; //$customer->id;

            unset($data["zoominfo"]);
            unset($data["fld-1"]);
            $model->reference = $info[1];
            foreach ($data as $identifier => $dt) {
                $imporetedData[$identifier] = addslashes($dt);
            }
            print_r($imporetedData);
            $model->attributes = $imporetedData;

            /*
              $locateinfo = "MTRSUBSTITUTE:CODE;";
              $ITEM = $softone->getData("ITEM", $model->reference, "",$locateinfo);
              $codes = array();
              foreach ((array) $ITEM->data->MTRSUBSTITUTE as $item) {
              $codes[] = $item->CODE;
              }
             * 
             */
            //if (count($codes) == 0)
            //    continue;
            //$model->search = implode("|", $codes);
            $model->erp_code = $model->item_code;
            $model->tecdoc_code = $model->item_cccfxreltdcode;
            $model->tecdoc_supplier_id = $model->item_cccfxrelbrand;
            //echo $model->reference . "<BR>";
            $model->save(false);

            //if ($model->flat_data == "") {
                $this->updatetecdoc($model);
            //}

            $model->setFlat();
            $i++;
            //if ($i++>100) break;
        }
        echo $i;
    }

    public function actionIndex() {

        $this->clearColumns();

        $this->addColumn(array(
            "label" => $this->translate(ID),
            "type" => "text",
                )
        );
        $this->addColumn(array(
            "label" => $this->translate("Erp Name"),
            "type" => "text",
                )
        );
        $this->addColumn(array(
            "label" => $this->translate("Erp Code"),
            "type" => "text",
                )
        );
        $this->addColumn(array(
            "label" => $this->translate("Θέση Ράφι"),
            "type" => "text",
                )
        );        
        $this->addColumn(array(
            "label" => $this->translate("Erp Supplier"),
            "type" => "text",
                )
        );
        $this->addColumn(array(
            "label" => $this->translate("Tecdoc Code"),
            "type" => "text",
                )
        );
        $this->addColumn(array(
            "label" => $this->translate("Tecdoc Supplier"),
            "type" => "text",
                )
        );
        $this->addColumn(array(
            "label" => $this->translate("Tecdoc Article ID"),
            "type" => "text",
                )
        );
        $this->addColumn(array(
            "label" => $this->translate("Tecdoc Article Name"),
            "type" => "text",
                )
        );

        $this->useServerSide = true;
        $this->sfields = true;
        $this->render('index');
    }

    public function vehiclesBlock() {
        $this->renderPartial('vehicles');
    }

    public function categoriesBlock() {
        $sql = "Select id from category where parent = 0";
        $datas = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ((array) $datas as $data) {
            $categories[] = $this->model("Category", $data["id"]);
        }
        $tecdocdata = $this->getLinkingTarget();
        $this->renderPartial('categories', array("categories" => $categories, "tecdocdata" => $tecdocdata));
    }

    public function actionGetcategories() {
        $sql = "Select id from category where parent = 0";
        $order = $this->model("Order", $_POST["order"]);
        $brandModelType = $this->model("BrandModelType", $_POST["car"]);
        $datas = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ((array) $datas as $data) {
            $categories[] = $this->model("Category", $data["id"]);
        }
        $tecdocdata = $this->getLinkingTarget();
        $this->renderPartial('categories', array("brandModelType" => $brandModelType, "categories" => $categories, "tecdocdata" => $tecdocdata, "order" => $order));
    }

    public function actionGetsubcategories() {
        $category = $this->model("Category", $_POST["id"]);

        $tecdocdata = $this->getLinkingTarget();

        $this->renderPartial('subcategories', array('category' => $category, "tecdocdata" => $tecdocdata));
    }

    public function actionSearch() {
        $sql = "Select * from product where item_code LIKE '%" . $_GET["term"] . "%' limit 0,100";
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
            $json["value"] = $model->attributeitems->item_name . "(" . $model->item_code . ")";
            $json["label"] = $model->attributeitems->item_name . "(" . $model->item_code . ")";
            $out[] = $json;
        }
        echo json_encode($out);
    }

    public function getBrands() {
        return Brand::model()->findAll();
    }

    public function actionGetmodels() {
        $sql = "Select id from brand_model where brand = '" . $_POST["brand"] . "'";
        $datas = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ((array) $datas as $data) {

            $brandmodel = $this->model("BrandModel", $data["id"]);
            $yearfrom = substr($brandmodel->year_from, 4, 2) . "/" . substr($brandmodel->year_from, 0, 4);
            $yearto = substr($brandmodel->year_to, 4, 2) . "/" . substr($brandmodel->year_to, 0, 4);
            $yearto = $yearto == 0 ? $this->translate('Today') : $yearto;
            $out[$brandmodel->group_name . "-" . $brandmodel->brand_model . "-" . $brandmodel->id] = array("group_name" => $brandmodel->group_name, "id" => $brandmodel->id, "name" => $brandmodel->brand_model . " " . $yearfrom . "-" . $yearto);
        }
        ksort($out);
        $json = json_encode($out);
        echo $json;
    }

    public function actionMotorsearch() {
        $sql = "Select id from brand_model_type where engine LIKE '%" . $_GET["term"] . "%'";
        $datas = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ((array) $datas as $data) {
            $brandmodeltype = $this->model("BrandModelType", $data["id"]);
            $brandmodel = $this->model("BrandModel", $brandmodeltype->brand_model); //Mage::getModel('tecdoc/brandmodel')->load($brandmodeltype->getBrandModel());
            $yearfrom = substr($brandmodel->year_from, 4, 2) . "/" . substr($brandmodel->year_from, 0, 4);
            $yearto = substr($brandmodel->year_to, 4, 2) . "/" . substr($brandmodel->year_to, 0, 4);
            //$yearto = $yearto == 0 ? Mage::helper('tecdoc')->__('Today') : $yearto;
            $json = array();
            $json["id"] = $brandmodeltype->id;
            $json["value"] = $brandmodeltype->getFullTitle() . " " . $yearfrom . "-" . $yearto;
            $json["label"] = $brandmodeltype->getFullTitle() . " " . $yearfrom . "-" . $yearto;
            $out[] = $json;
            if ($i++ > 20)
                break;
        }
        echo json_encode($out);
    }

    public function actionGetmodeltypes() {
        $sql = "Select id from brand_model_type where brand_model = '" . $_POST["model"] . "'";
        $datas = Yii::app()->db->createCommand($sql)->queryAll();
        $out = array();
        foreach ($datas as $data) {
            $brandmodeltype = $this->model("BrandModelType", $data["id"]);
            //$engine = $tecdocbrandmodeltype->engine;
            //$tecdocbrandmodeltype->motor_type = "s";
            $out[$brandmodeltype->motor_type . "-" . $brandmodeltype->brand_model_type . "-" . $brandmodeltype->power_hp] = array("chk" => (int) $chk, "motor_type" => $brandmodeltype->motor_type, "id" => $brandmodeltype->id, "name" => $brandmodeltype->brand_model_type . " " . $engine . " (" . $brandmodeltype->power_hp . "HP)");
        }
        ksort($out);
        $json = json_encode($out);
        echo $json;
    }

    public function actionfororderajaxjson() {
        $time_start_tot = microtime(true);
        $products = array();
        $order = $this->model("Order", $_POST["order"]);
        if ($_POST["articleIds"]) {
            $articleIds = unserialize($_POST["articleIds"]);
        };
        if (count($articleIds)) {
            //$sql = "Select id, flat_data from product where id in (Select product from webservice_product where webservice = '" . $this->settings["webservice"] . "' AND article_id in (" . implode(",", $articleIds) . "))";
            $sql = "Select product from webservice_product where webservice = '" . $this->settings["webservice"] . "' AND article_id in (" . implode(",", $articleIds) . ")";

            $datas = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($datas as $data) {
                $products[] = $this->loadModel($data["product"]);
            }
        } else {


            $time_start = microtime(true);
            $articleIds = unserialize($this->getArticlesSearch($_POST["terms"]));
            //print_r($articleIds);

            if (count($articleIds)) {
                $sql = "Select id from product where id in (Select product from webservice_product where webservice = '" . $this->settings["webservice"] . "' AND article_id in (" . implode(",", $articleIds) . "))";
                $datas = Yii::app()->db->createCommand($sql)->queryAll();
                foreach ($datas as $data) {
                    $products[] = $this->loadModel($data["id"]);
                }
            }

            $time_end = microtime(true);
            $execution_time = ($time_end - $time_start);
            //echo '<b>Execution Time:</b> '.$execution_time."<BR>";


            $time_start = microtime(true);
            //if (count($products) == 0) {
            if ($_POST["terms"]) {
                $sql = "Select id from product_search where item_code LIKE '%" . $_POST["terms"] . "%' OR search LIKE '%" . $_POST["terms"] . "%' OR gnisia LIKE '%" . $_POST["terms"] . "%'   limit 0,100";
                $datas = Yii::app()->db->createCommand($sql)->queryAll();
                //echo $sql;
                //print_r($datas);
                foreach ((array) $datas as $data) {
                    $product = $this->loadModel($data["id"]);
                    $products[$data["id"]] = $product;
                }
            }
            //}

            $time_end = microtime(true);
            $execution_time = ($time_end - $time_start);


            //echo '<b>Execution Time:</b> '.$execution_time."<BR>";               
        }
        $time_start = microtime(true);
        $softone = new Softone();
        foreach ($order->_items_ as $item) {
            $items[] = $item->product;
        }
        $object = "SALDOC";
        $objectArr = array();
        $objectArr[0]["TRDR"] = $order->_customer_->reference;
        $objectArr[0]["SERIESNUM"] = $order->seriesnum;
        $objectArr[0]["FINCODE"] = $order->fincode;
        $objectArr[0]["PAYMENT"] = 1000;
        //$objectArr[0]["TFPRMS"] = $model->tfprms;
        //$objectArr[0]["FPRMS"] = $model->fprms;
        $objectArr[0]["SERIES"] = 7021; //$model->series;
        //$objectArr[0]["DISC1PRC"] = 10;

        $dataOut[$object] = (array) $objectArr;
        $k = 9000001;
        $dataOut["ITELINES"] = array();
        foreach ($products as $product) {
            $dataOut["ITELINES"][] = array("VAT" => 1310, "LINENUM" => $k++, "MTRL" => $product->reference, "QTY1" => 1);
        }
        //echo "1";
        //print_r($dataOut);
        $locateinfo = "MTRL,NAME,PRICE,QTY1,VAT;ITELINES:DISC1PRC,ITELINES:LINEVAL,MTRL,MTRL_ITEM_CODE,MTRL_ITEM_CODE1,MTRL_ITEM_NAME,MTRL_ITEM_NAME1,PRICE,QTY1;SALDOC:BUSUNITS,EXPN,TRDR,MTRL,PRICE,QTY1,VAT";

        $out = $softone->calculate((array) $dataOut, $object, "", "", $locateinfo);
        //print_r($out);
        if (!$out->success) {
            $dataOut["ITELINES"] = array();
            foreach ($products as $product) {
                $ITEM = $softone->getData("ITEM", $product->reference, "", "ITEM:CODE");
                if ($ITEM->success) {
                    $dataOut["ITELINES"][] = array("VAT" => 1310, "LINENUM" => $k++, "MTRL" => $product->reference, "QTY1" => 1);
                } else {
                    $sql = "Delete from product_search where id = '" . $product->id . "'";
                    Yii::app()->db->createCommand($sql)->execute();
                    $product->delete();

                    //$sql = "Delete from product_search where "
                }
            }
            //print_r($dataOut);
            $out = $softone->calculate((array) $dataOut, $object, "", "", $locateinfo);
            //print_r($out);
        }


        $sql = "Select id from `order` where customer = '" . $order->customer . "' AND insdate >= '" . date("Y-m-d") . " 00:00:00' AND insdate < '" . date("Y-m-d") . " 23:59:59'";
        $datas = Yii::app()->db->createCommand($sql)->queryAll();

        foreach ($datas as $data) {
            $ordertoday = $this->model("Order", $data["id"]);
            foreach ($ordertoday->_items_ as $item) {
                if ($ordertoday->id != $order->id)
                    $itemstoday[$item->product][$ordertoday->id] = "<a href='" . Yii::app()->request->baseUrl . "/orders/order/" . $ordertoday->id . "'>" . $ordertoday->id . "</a>";
            }
        }

        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start);
        //echo '<b>Execution Time:</b> '.$execution_time."<BR>";   
        $time_start = microtime(true);

        echo "<table class='fororder display'>";
        echo "<thead><tr>";
        echo "<th></th>";
        echo "<th>Κωδικός</th>";
        echo "<th>Είδος</th>";
        echo "<th>Εργοστάσιο</th>";
        echo "<th>Χονδρ</th>";
        echo "<th>Λιαν</th>";
        echo "<th>Τιμή</th>";
        echo "<th>Εκπτ</th>";
        echo "<th>Νέτη</th>";
        echo "<th>Απόθεμα</th>";
        echo "<th>Θέση Ράφι</th>";
        echo "<th>Ποσότητα</th>";
        echo "<th></th>";
        echo "<th></th></tr></thead>";
        echo "<tbody>";
        foreach ((array) $out->data->ITELINES as $item) {
            echo "<tr price='" . $item->PRICE . "' class='productitem' mtrl='" . $item->MTRL . "' ref='" . $product->id . "'>";
            $sql = "Select id from product where reference = '" . $item->MTRL . "'";
            $data = Yii::app()->db->createCommand($sql)->queryRow();
            //$product = Product::model()->findByAttributes(array('reference' => $item->MTRL));
            $product = $this->model("Product", $data["id"]);
            $i++;
            $item_code = str_replace($_POST["terms"], "<b>" . $_POST["terms"] . "</B>", $item->MTRL_ITEM_CODE);
            //echo "<td><img  class='product_info' ref='" . $product->id . "' width=100 src='" . $product->media() . "' /></td>";

            if ($product->media()) {
                echo "<td><img  class='product_info' ref='" . $product->id . "' width=100 src='" . $product->media() . "' /></td>";
            } else {
                //$json[] = "<a class='product_info' ref='" . $product->id . "'  />Νο Image</a>";
                echo "<td><a class='product_info' ref='" . $product->id . "'  >Νο Image</a></td>";
            }


            echo "<td>" . $item_code . "</td>";
            echo "<td>" . $item->MTRL_ITEM_NAME . "</td>";
            echo "<td>" . $product->item_mtrmanfctr . "</td>";
            echo "<td>" . $product->item_pricew . "</td>";
            echo "<td>" . $product->item_pricer . "</td>";
            echo "<td>" . $item->PRICE . "</td>";
            echo "<td>" . $item->DISC1PRC . "</td>";
            echo "<td>" . $item->LINEVAL . "</td>";
            echo "<td>" . $product->item_mtrl_itemtrdata_qty1 . "</td>";
            echo "<td>" . $product->item_mtrplace . "</td>";
            echo "<td><input lineval='" . $item->LINEVAL . "' disc1prc='" . $item->DISC1PRC . "' price='" . $item->PRICE . "' class='productitemqty " . ($i == 1 ? 'first' : "") . "' ref='" . $product->id . "' type='text' value='" . ($i == 1 ? '' : 1) . "' style='width:20px;' ></td>";
            echo "<td><img width=20 style='width:20px; max-width:20px; display:" . (in_array($product->id, (array) $items) ? "block" : "none") . "' class='tick_" . $product->id . "' src='" . Yii::app()->request->baseUrl . "/img/tick.png'></td>";
            echo "<td>" . implode(",", (array) $itemstoday[$product->id]) . "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";

        $time_end = microtime(true);
        $time_end_tot = microtime(true);
        $execution_time = ($time_end - $time_start);
        $execution_time_tot = ($time_end_tot - $time_start_tot);
        //echo '<b>Execution Time:</b> '.$execution_time."<BR>"; 
        echo '<BR><b>Total Execution Time:</b> ' . $execution_time_tot . "<BR>";
    }

    public function actionAjaxJson() {

        $_POST["iDisplayLength"];
        $_POST["iDisplayStart"];
        $_POST["sSearch"];
        $_POST["iSortCol_0"];

        $this->settings["webservice"] = 11632;


        if ($_POST["iDisplayLength"]) {
            $limiter = " limit " . $_POST["iDisplayStart"] . ", " . $_POST["iDisplayLength"];
        }
        if ($_POST["articleIds"]) {
            $articleIds = unserialize($_POST["articleIds"]);
        };




        if (count($articleIds)) {
            $sql = "Select id, flat_data from product where id in (Select product from webservice_product where webservice = '" . $this->settings["webservice"] . "' AND article_id in (" . implode(",", $articleIds) . "))";
        } else {
            $sql = "Select id, flat_data from product";
            $sqlcnt = "Select count(*) as cnt from product";
        }




        if ($_POST["sSearch_0"])
            $queryarr[] = "id = '" . $_POST["sSearch_0"] . "'";
        if ($_POST["sSearch_1"])
            $queryarr[] = "item_name like '%" . $_POST["sSearch_1"] . "%'";
        if ($_POST["sSearch_2"]) 
            $queryarr[] = "(item_code like '%" . $_POST["sSearch_2"] . "%' OR search LIKE '%" . $_POST["sSearch_2"] . "%' OR gnisia LIKE '%" . $_POST["sSearch_2"] . "%')";
         if ($_POST["sSearch_3"])
            $queryarr[] = "item_mtrplace like '%" . $_POST["sSearch_3"] . "%'";
        if ($_POST["sSearch_4"])
            $queryarr[] = "item_mtrmanfctr like '%" . $_POST["sSearch_4"] . "%'";
        if ($_POST["sSearch_5"])
            $queryarr[] = "item_cccfxreltdcode like '%" . $_POST["sSearch_5"] . "%'";

        if (count($queryarr)) {
            $query = " where " . implode(" AND ", $queryarr);
        }
        $softone = new Softone();
        //echo "(Select product from webservice_product where webservice = '".$this->settings["webservice"]."' AND article_id in (".implode(",",$articleIds)."))";
        $user = $this->loadModel(Yii::app()->user->id);
        $cnt = Yii::app()->db->createCommand($sqlcnt . " " . $query)->queryRow();
        $datas = Yii::app()->db->createCommand($sql . " " . $query . " " . $limiter)->queryAll();
        $jsonArr = array();
        foreach ((array) $datas as $data) {
            //$data["flat_data"] = "";
            if ($data["flat_data"] == "") {

                $model = $this->loadModel($data["id"]);
                $model->load();


                if ($data["flat_data"] == "") {
                    $model->erp_code = $model->item_code;
                    $model->tecdoc_code = $model->item_cccfxreltdcode;
                    $model->tecdoc_supplier_id = $model->item_cccfxrelbrand;
                }
                if ($model->item_cccfxreltdcode != "") {
                    $this->updatetecdoc($model);
                }
                if ($data["flat_data"] == "") {
                    $model->setFlat();
                }

                $model = json_decode($model->flat_data);
            } else {
                $model = json_decode($data["flat_data"]);
            }
            $model1 = $this->loadModel($data["id"]);
            $json = array();
            $f = false;
            $fields = array();
            $json[] = "<img width=100 src='" . $model1->media() . "' />";
            //$json[] = $model->_productLangs_[$this->settings["language"]]->title;
            $json[] = $model->item_name;
            $json[] = $model->item_code;
            $json[] = $model->item_mtrplace;
            $json[] = $model->item_mtrmanfctr;
            $json[] = $model->item_cccfxreltdcode;
            $json[] = $model->_tecdocSupplier_->supplier;
            $json[] = $model->_webserviceProducts_->article_id;
            $json[] = $model->_webserviceProducts_->article_name;
            /*
              $json[] = $model1->_tecdocSupplier_->supplier;
              $json[] = $model1->_webserviceProducts_[$this->settings["webservice"]]->article_id;
              $json[] = $model1->_webserviceProducts_[$this->settings["webservice"]]->article_name;
             */
            //$json[] = "<a href='" . Yii::app()->params['mainurl'] . "/users/user/" . $model->id . "'>Edit</a>";
            $json["DT_RowId"] = 'product_' . $model->id;
            $json["DT_RowClass"] = 'productpage';
            $jsonArr[] = $json;
        }
        echo json_encode(array('iTotalRecords' => $cnt["cnt"], 'iTotalDisplayRecords' => $cnt["cnt"], 'aaData' => $jsonArr));
    }

    public function actionAjaxFormTitle() {
        $model = $this->loadModel($_POST["id"]);

        if ($model->id > 0) {
            echo $this->translate("Edit Product") . ": " . $model->getFirstname() . " " . $model->getLastname();
        } else {
            echo $this->translate("Create New Product");
        }
    }

    public function actionAjaxForm() {
        $model = $this->loadModel($_POST["id"]);

        $this->addFormField("text", $this->translate("Title"), "title");
        $this->addFormField("text", $this->translate("Erp Code"), "item_code");
        $this->addFormField("text", $this->translate("Erp Supplier"), "erp_supplier");
        $this->addFormField("text", $this->translate("Tecdoc Code"), "tecdoc_code");
        $this->addFormField("select", $this->translate("Tecdoc Supplier"), "tecdoc_supplier_id", CHtml::listData(TecdocSupplier::model()->findAll(), 'id', 'supplier'));

        $this->addFormField("textarea", $this->translate("Σύνάφιες"), "search");

        $this->renderPartial('ajaxform', array(
            'model' => $model,
        ));
    }

    public function actionEdit($id = 0) {
        $model = $this->loadModel($id);


        $model->reference;

        //$locateinfo = "MTRSUBSTITUTE:MTRL;";
        $softone = new Softone();
        $data = $softone->getData("ITEM", $model->reference, "");
        //print_r($data);

        $model->search = str_replace("|", "\n", $model->search);
        $model->gnisia = str_replace("|", "\n", $model->gnisia);

        $this->addFormField("text", $this->translate("Περιγραφή"), "item_name", "", "width:500px");
        $this->addFormField("text", $this->translate("Κωδικός Είδους"), "item_code", "", "width:500px");
        $this->addFormField("text", $this->translate("Erp Supplier"), "item_mtrmanfctr", "", "width:500px");

        $this->addFormField("select", $this->translate("Tecdoc Supplier"), "item_cccfxrelbrand", CHtml::listData(TecdocSupplier::model()->findAll(), 'id', 'supplier'));
        $this->addFormField("text", $this->translate("Tecdoc Code"), "item_cccfxreltdcode");

        $this->addFormField("textarea", $this->translate("Σύνάφιες"), "search");
        $this->addFormField("textarea", $this->translate("Γνήσια"), "gnisia");

        $this->render('edit', array(
            'model' => $model,
        ));
    }

    public function actionUpdateSynafiesActCodes() {
        if (($handle = fopen("SINAF2.csv", "r")) !== FALSE) {
            $as = array();
            while (($data = fgetcsv($handle, 10000, ";")) !== FALSE) {
                $act[$data[1]][] = $data[0];
                $keys[] = $data[1];
            }
            $keys = array_unique($keys);

            $keys2 = array();
            foreach ($keys as $key) {
                if (count($act[$key]) > 1) {
                    $keys2[] = $key;
                }
            }
            $keys = array_unique($keys2);



            foreach ($keys as $key) {


                if (count($act[$key]) > 1) {
                    foreach ($act[$key] as $cccfxcode1) {
                        $sql = "Select id from product where item_cccfxcode1 = '" . $cccfxcode1 . "'";
                        $p = Yii::app()->db->createCommand($sql)->queryRow();
                        if ($p["id"] > 0) {
                            $model = $this->model("Product", $p["id"]);
                            $search = str_replace("\n", "|", $model->search);
                            $searchArr = explode("|", $search);
                            $as = explode("-", $model->item_code);
                            if ($as[1] == "")
                                continue;

                            $con = false;
                            foreach ($act[$key] as $cccfxcode1_2) {
                               
                                $sql = "Select id from product where item_cccfxcode1 = '" . $cccfxcode1_2 . "'";
                                $subp = Yii::app()->db->createCommand($sql)->queryRow();
                                if ($subp["id"] > 0) {
                                    $submodel = $this->model("Product", $subp["id"]);

                                    if ($submodel->item_code == $model->item_code)
                                        continue;;

                                    $subsearch = str_replace("\n", "|", $submodel->search);
                                    $as = explode("-", $submodel->item_code);
                                    if ($as[1] == "")
                                        continue;
                                    $subsearchArr = explode("|", $subsearch);

                                    if (!in_array($model->item_code, $subsearchArr))
                                        $subsearchArr[] = $model->item_code;
                                    if (!in_array($submodel->item_code, $searchArr))
                                        $searchArr[] = $submodel->item_code;

                                    $con = true;

                                    $searchArr = array_filter(array_unique($searchArr));
                                    $subsearchArr = array_filter(array_unique($subsearchArr));
                                    $searchArr = array_diff($searchArr, array($model->item_code));
                                    $subsearchArr = array_diff($subsearchArr, array($submodel->item_code));
                                }
                            }
                        }
                        if ($con == false)
                          continue;
                        
                        
                        foreach ($searchArr as $srch) {
                            $sql = "Select id from product_search where search LIKE '%" . $srch . "%' OR item_code = '" . $srch . "'  limit 0,30";
                            $srches = Yii::app()->db->createCommand($sql)->queryAll();

                            foreach ($srches as $srchs) {
                                if ($srchs["id"] != $model->id) {

                                    $subsubmodel = $this->model("Product", $srchs["id"]);
                                    $subsubsearch = str_replace("\n", "|", $subsubmodel->search);
                                    $subsubsearchArr = explode("|", $subsubsearch);

                                    $as = explode("-", $subsubmodel->item_code);
                                    if ($as[1] == "")
                                        continue;

                                    if (!in_array($subsubmodel->item_code, $searchArr)) {
                                        $searchArr[] = $subsubmodel->item_code;
                                    }
                                    if (!in_array($model->item_code, $subsubsearchArr)) {
                                        $subsubsearchArr[] = $model->item_code;
                                    }
                                    $subsubsearchArr = array_filter(array_unique($subsubsearchArr));
                                    $subsubsearchArr = array_diff($subsubsearchArr, array($subsubmodel->item_code));
                                    $subsubmodel->search = implode("|", $subsubsearchArr);
                                    $subsubmodel->save();
                                    $subsubmodel->setProductSearch();
                                }
                            }
                        }
                        
                        foreach ($searchArr as $srch) {
                            $subsearchArr[] = $srch;
                        }
                        foreach ($subsearchArr as $srch) {
                            $searchArr[] = $srch;
                        } 
                        
                        $searchArr = array_filter(array_unique($searchArr));
                        $subsearchArr = array_filter(array_unique($subsearchArr));
                        $searchArr = array_diff($searchArr, array($model->item_code));
                        $subsearchArr = array_diff($subsearchArr, array($submodel->item_code));

                        $model->search = implode("|", $searchArr);
                        $submodel->search = implode("|", $subsearchArr);
                        $model->save();
                        $submodel->save();
                        $model->setProductSearch();
                        $submodel->setProductSearch();

                        echo $model->item_code . "---" . $submodel->item_code . "\n";
                        print_r($searchArr);
                        print_r($subsearchArr);
                        echo "---\n";
                    }
                }
            }

            /*
              while (($data = fgetcsv($handle, 10000, ";")) !== FALSE) {
              if ($data[0] != $data[1]) {
              //$product = Product::model()->findByAttributes(array('item_cccfxcode1' => $data[0]));
              $sql = "Select id from product where item_cccfxcode1 = '" . $data[0] . "'";
              $p = Yii::app()->db->createCommand($sql)->queryRow();
              if ($p["id"] > 0) {
              $model = $this->model("Product", $p["id"]);
              $search = str_replace("\n", "|", $model->search);
              $searchArr = explode("|", $search);
              $as = explode("-", $model->item_code);
              if ($as[1] == "")
              continue;

              $sql = "Select id from product where item_cccfxcode1 = '" . $data[1] . "'";
              $subp = Yii::app()->db->createCommand($sql)->queryRow();
              if ($subp["id"] > 0) {
              $submodel = $this->model("Product", $subp["id"]);
              $subsearch = str_replace("\n", "|", $submodel->search);


              $as = explode("-", $submodel->item_code);
              if ($as[1] == "")
              continue;

              $subsearchArr = explode("|", $subsearch);

              if (!in_array($model->item_code, $subsearchArr))
              $subsearchArr[] = $model->item_code;

              if (!in_array($submodel->item_code, $searchArr))
              $searchArr[] = $submodel->item_code;

              $searchArr = array_filter(array_unique($searchArr));
              $subsearchArr = array_filter(array_unique($subsearchArr));
              $searchArr = array_diff($searchArr, array($model->item_code));
              $subsearchArr = array_diff($subsearchArr, array($submodel->item_code));

              foreach ($searchArr as $srch) {
              $sql = "Select id from product_search where search LIKE '%" . $srch . "%' OR item_code = '" . $srch . "'  limit 0,30";
              $srches = Yii::app()->db->createCommand($sql)->queryAll();

              foreach ($srches as $srchs) {
              if ($srchs["id"] != $model->id) {

              $subsubmodel = $this->model("Product", $srchs["id"]);
              $subsubsearch = str_replace("\n", "|", $subsubmodel->search);
              $subsubsearchArr = explode("|", $subsubsearch);

              $as = explode("-", $subsubmodel->item_code);
              if ($as[1] == "")
              continue;


              if (!in_array($subsubmodel->item_code, $searchArr)) {
              $searchArr[] = $subsubmodel->item_code;
              }
              if (!in_array($model->item_code, $subsubsearchArr)) {
              $subsubsearchArr[] = $model->item_code;
              }
              $subsubsearchArr = array_filter(array_unique($subsubsearchArr));
              $subsubsearchArr = array_diff($subsubsearchArr, array($subsubmodel->item_code));
              $subsubmodel->search = implode("|", $subsubsearchArr);
              $subsubmodel->save();
              $subsubmodel->setProductSearch();

              echo $model->item_code . "---" . $subsubmodel->item_code . "\n";
              //print_r($searchArr);
              //echo "---\n";
              print_r($subsubsearchArr);
              echo "---\n";
              }
              }
              }
              $searchArr = array_filter(array_unique($searchArr));
              $subsearchArr = array_filter(array_unique($subsearchArr));
              $searchArr = array_diff($searchArr, array($model->item_code));
              $subsearchArr = array_diff($subsearchArr, array($submodel->item_code));

              $model->search = implode("|", $searchArr);
              $submodel->search = implode("|", $subsearchArr);
              $model->save();
              $submodel->save();
              $model->setProductSearch();
              $submodel->setProductSearch();


              echo $model->item_code . "---" . $submodel->item_code . "\n";
              print_r($searchArr);
              print_r($subsearchArr);
              echo "---\n";
              }
              //if ($i++ > 100)
              //    break;
              }
              }
              }
             * 
             */
        }
    }

    public function actionAjaxFormSave() {
        $model = $this->loadModel($_POST["id"]);
        $model->attributes = $_POST;
        $model->attrs = $_POST["attrs"];
        $model->catalogue = 1;
        $model->save();


        $model->tecdoc_code = $model->getItemCode();
        $model->tecdoc_supplier_id = $model->getItemMtrmanfctr();


        $model->search = str_replace("\n", "|", $model->search);


        $searchArr = explode("|", $model->search);


        $model->gnisia = str_replace("\n", "|", $model->gnisia);
        $gnisiaArr = explode("|", $model->gnisia);
        foreach ($gnisiaArr as $gnisia) {
            if ($gnisia != "") {
                $sql = "Select id, flat_data from product where gnisia LIKE '%" . $gnisia . "%'   limit 0,20";
                $datas = Yii::app()->db->createCommand($sql)->queryAll();
                foreach ($datas as $data) {
                    if ($data["id"] != $model->id) {
                        $submodel = $this->model("Product", $data["id"]);
                        if (!in_array($submodel->item_code, $searchArr)) {
                            $searchArr[] = $submodel->item_code;
                        }
                        $searchArr = array_filter(array_unique($searchArr));
                        $model->search = implode("|", $searchArr);
                    }
                }
            }
        }

        foreach ($searchArr as $search) {
            $submodel = Product::model()->findByAttributes(array('item_code' => $search));
            if ($submodel->id > 0) {
                $submodel = $this->model("Product", $submodel->id);
                $subsearchArr = explode("|", $submodel->search);
                if (!in_array($model->item_code, $subsearchArr)) {
                    $subsearchArr[] = $model->item_code;
                }
                $subsearchArr = array_filter(array_unique($subsearchArr));
                $submodel->search = implode("|", $subsearchArr);
                $submodel->save();
            }
        }




        $model->save();

        $params = array("softone_object" => "ITEM", "eav_model" => "product", "model" => $model, "list" => 'parts');
        //$this->saveSoftone($params);
        $this->updatetecdoc($model);


        if (count($model->itemError) > 0)
            echo json_encode($model->itemError) . "|||" . json_encode($model->tabError);
        else {
            $model->setFlat();

            echo $model->id;
        }
    }

    public function loadModel($id) {
        return $this->model("Product", $id);
    }

    function actionUpdatetecdoc($model) {
        
    }

    /*
      public function actionGetcategories() {
      $data_string = json_encode($data);
      $url = "http://service.fastwebltd.com/";

      $fields = array(
      'action' => 'getcategories'
      );
      foreach ($fields as $key => $value) {
      $fields_string .= $key . '=' . $value . '&';
      }
      rtrim($fields_string, '&');
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, count($fields));
      curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      $categogies = unserialize(curl_exec($ch));

      foreach ($categogies as $category) {
      //print_r($category);
      $categoryModel = $this->model("Category", $category->assemblyGroupNodeId);
      $categoryModel->id = $category->assemblyGroupNodeId;
      $categoryModel->weight = $category->weight;
      $categoryModel->parent = $category->parentNodeId;
      $categoryModel->actioneer = 1;
      $categoryModel->save();

      $sql = "Delete from category_lang where category = '" . $categoryModel->id . "' AND language = '" . $this->settings["language"] . "'";
      Yii::app()->db->createCommand($sql)->execute();
      $categoryLang = $this->model("CategoryLang");
      $categoryLang->category = $categoryModel->id;
      $categoryLang->language = $this->settings["language"];
      $categoryLang->name = $category->assemblyGroupName;
      $categoryLang->save();
      }
      }
     */

    public function actionGetLinkingTarget() {
        $this->getLinkingTarget();
    }

    public function getLinkingTarget() {

        if (file_exists(Yii::app()->params['root'] . "cache/cars/" . $_POST["car"] . ".car")) {
            $data = file_get_contents(Yii::app()->params['root'] . "cache/cars/" . $_POST["car"] . ".car");
        } else {
            $data_string = json_encode($data);
            $url = "http://service.fastwebltd.com/";
            $fields = array(
                'action' => 'getcarcategories',
                'linkingTargetId' => $_POST["car"]
            );
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, count($fields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $data = curl_exec($ch);
            $data = gzcompress($data);
            file_put_contents(Yii::app()->params['root'] . "cache/cars/" . $_POST["car"] . ".car", $data);
        }
        $data = gzuncompress($data);
        //print_r(unserialize($data));
        return unserialize($data);
    }

    public function getArticlesSearch($search) {
        if (file_exists(Yii::app()->params['root'] . "cache/terms/" . md5($search) . ".term")) {
            $data = file_get_contents(Yii::app()->params['root'] . "cache/terms/" . md5($search) . ".term");
            return $data;
        } else {
            $url = "http://service.fastwebltd.com/";
            $fields = array(
                'action' => 'getSearch',
                'search' => $search
            );

            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, count($fields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $data = curl_exec($ch);
            file_put_contents(Yii::app()->params['root'] . "cache/terms/" . md5($search) . ".term", $data);
            return $data;
        }
    }

    function getArticlesIds2($articleids) {
        if (count($articleids)) {
            $as = implode(",", $articleids);
            $sql = "Select article_id from webservice_product where article_id in (" . $as . ") AND webservice= '" . $this->webservice . "'";
            $data = Yii::app()->db->createCommand($sql)->queryAll();
            return $data;
        }
        return array();
    }

    function getArticlesIds() {
        $sql = "Select article_id from webservice_product where webservice= '" . $this->webservice . "'";
        $datas = Yii::app()->db->createCommand($sql)->queryAll();
        $out = array();
        foreach ((array) $datas as $data) {
            $out[] = $data["article_id"];
        }
        return $out;
    }

    public function getProductInfo($model) {

        //echo $model->item_cccfxreltdcode;
        //exit;

        $out["originals"] = $this->originals($model);
        $out["articleAttributes"] = $this->articleAttributes($model);
        $out["articlesSearch"] = unserialize($this->getArticlesSearch($model->item_cccfxreltdcode));



        if (count($out["articlesSearch"])) {
            $sql = "Select id, flat_data from product where id in (Select product from webservice_product where webservice = '" . $this->settings["webservice"] . "' AND article_id in (" . implode(",", $out["articlesSearch"]) . "))";


            $sql1 = "Select product from webservice_product where webservice = '" . $this->settings["webservice"] . "' AND article_id in (" . implode(",", $out["articlesSearch"]) . ")";
            //$datas = Yii::app()->db->createCommand($sql)->queryAll();
            //print_r($datas);

            $datas = Yii::app()->db->createCommand($sql)->queryAll();
            $out["antistixies"] = $datas;
        }


        $out["efarmoges"] = unserialize($this->efarmoges($model));
        return $out;
    }

    public function actionGetProductInfo() {

        $product = $this->model("Product", $_POST["id"]);
        $out = $this->getProductInfo($product);

        $this->renderPartial('productinfo', array(
            'model' => $product,
            'info' => $out
        ));
    }

    function updatetecdoc($model) {
        //$data = array("service" => "login", 'username' => 'dev', 'password' => 'dev', 'appId' => '2000');

        $data_string = json_encode($data);
        $url = "http://service.fastwebltd.com/";

        $fields = array(
            'action' => 'updateTecdoc',
            'tecdoc_code' => $model->item_cccfxreltdcode,
            'tecdoc_supplier_id' => $model->item_cccfxrelbrand,
        );
        //print_r($fields);
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        rtrim($fields_string, '&');
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $out = json_decode(curl_exec($ch));

        //echo print_r($out);

        try {
            //$webserviceProduct = WebserviceProduct::model()->findByAttributes(array('product' =>  $model->id,"webservice"=>$this->webservice));
            if ($out->articleId) {

                $sql = "Delete from webservice_product where product = '" . $model->id . "' AND webservice= '" . $this->webservice . "'";
                $data = Yii::app()->db->createCommand($sql)->execute();

                $webserviceProduct = $this->model("WebserviceProduct");
                $webserviceProduct->product = $model->id;
                $webserviceProduct->webservice = $this->webservice;
                $webserviceProduct->article_id = $out->articleId;
                $webserviceProduct->article_name = $out->articleName;
                $webserviceProduct->save();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
        //echo $result;
    }

}
