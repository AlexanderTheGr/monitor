<?php

class EavModelController extends Controller {

    /**
     * @return array action filters
     */
    //public $ajaxnew = "settings/attributes/0";
    public $dataTableId = "eavmodel";
    public $ajaxformsave = "settings/eavmodel/ajaxformsave/";
    public $ajaxdelete = "settings/eavmodel/ajaxdelete/";
    public $ajaxform = "settings/eavmodel/ajaxform/";
    public $ajaxformtitle = "settings/eavmodel/ajaxformtitle/";
    public $sAjaxSource = "settings/eavmodel/ajaxjson";
    public $returnaftersafe = "settings/eavmodel/";

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
        
    }

    public function actionTab() {
        $this->clearColumns();
        $this->addColumn(array(
            "label" => $this->translate("ID"),
            "type" => "text",
                )
        );
        $this->addColumn(array(
            "label" => $this->translate("Eav Model"),
            "type" => "text",
                )
        );
        $this->addColumn(array(
            "label" => $this->translate("View Style"),
            "type" => "text",
                )
        );
        $this->renderPartial('tab', array());
    }

    public function enumArray() {
        $this->enumArray["viewstyle"] = array('none' => 'None', 'tab' => 'Tab', 'accordion' => 'Accordion');
        $this->enumArray["list_style"] = array('horizontal' => 'Horizontal', 'vertical' => 'Vertical');
    }

    public function actionAjaxJson() {
        $datas = $this->getModelArray("EavModel", "", array());
        $jsonArr = array();
        $this->enumArray();
        foreach ((array) $datas as $data) {
            $data->load();
            $json = array();
            $fields = array();
            $json[] = $data->id;
            $json[] = $data->eav_model;
            $json[] = $this->enumArray["viewstyle"][$data->viewstyle];
            $json["DT_RowId"] = 'eavmodel_' . $data->id;
            $json["DT_RowClass"] = 'eavmodel';

            $jsonArr[] = $json;
        }
        echo json_encode(array('aaData' => $jsonArr));
    }

    public function actionAjaxForm() {
        //$ls = Langtranslater::getSingleton();
        $model = $this->loadModel($_POST["id"]);
        $this->formFields = array();


        $this->addFormField("text", $this->translate("Eav Model"), "eav_model");
        $this->addFormField("select", $this->translate("View Style"), "viewstyle", $this->enumArray["viewstyle"]);
        $this->addFormField("text", $this->translate("Softone"), "softone");
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
            echo $this->translate("Edit Eav Model");
        } else {
            echo $this->translate("Create new Eav Model");
        }
    }

    public function actionAjaxAddAttr() {
        $model = new AttributeItems;
        $modelItem = Attributes::model()->findByPk($_POST["attr"]);
        $model->eav_model = $_POST["eav_model"];
        $model->attribute_id = $modelItem->id;
        $model->required = $modelItem->required;
        $model->visible = $modelItem->visible;
        $model->unique = $modelItem->unique;
        $model->title = $modelItem->title;
        $model->css = $modelItem->css;
        $model->select_data = $modelItem->select_data;
        $model->save();
    }

    public function actionSoftone() {
        $model = EavModel::model()->findByPk($_POST["id"]);
        $monitor = new Monitor();
        $fields = $monitor->retrieveFields($model->softone, $model->list);
        $columns = $monitor->retrieveColumns($model->softone, $model->list);
        foreach ($fields as $field) {
            $attribute = Attributes::model()->findByAttributes(array('identifier' => $field));
            if ((int) $attribute->id == 0) {
                $attribute = $this->model("Attributes");
                $attribute->type = "text";
                $attribute->identifier = $field;
                $attribute->title = $columns[$field] ? $columns[$field] : ucwords(str_replace("_", " ", $field));
                $attribute->visible = 1;
                $attribute->save();
                $modelItem = $attribute;
                $attributeitem = new AttributeItems;
                $attributeitem->eav_model = $model->eav_model;
                $attributeitem->attribute_id = $modelItem->id;
                $attributeitem->required = $modelItem->required;
                $attributeitem->visible = $modelItem->visible;
                $attributeitem->unique = $modelItem->unique;
                $attributeitem->title = $modelItem->title;
                $attributeitem->css = $modelItem->css;
                $attributeitem->access = 'a:3:{s:5:"admin";s:5:"admin";s:3:"crm";s:5:"admin";s:4:"user";s:5:"admin";}';
                $attributeitem->select_data = $modelItem->select_data;
                $attributeitem->save();
            }
        }
    }

    public function actionAjaxSetSort() {
        $model = AttributeItems::model()->findByPk($_POST["id"]);
        $model->sort = $_POST["value"];
        $model->save();
    }

    public function actionAjaxSetTitle() {
        $model = AttributeItems::model()->findByPk($_POST["id"]);
        $model->title = $_POST["value"];
        $model->save();
    }

    public function actionAjaxSetCss() {
        $model = AttributeItems::model()->findByPk($_POST["id"]);
        $model->css = $_POST["value"];
        $model->save();
    }

    public function actionAjaxSetVisible() {
        $model = AttributeItems::model()->findByPk($_POST["id"]);
        $model->visible = !$model->visible;
        $model->save();
    }

    public function actionAjaxsetliststyle() {
        $model = AttributeItems::model()->findByPk($_POST["id"]);
        $model->list_style = $_POST["value"];
        $model->save();
    }

    public function actionAjaxSetUnique() {
        $model = AttributeItems::model()->findByPk($_POST["id"]);
        $model->unique = !$model->unique;
        $model->save();
    }

    public function actionAjaxSetRequired() {
        $model = AttributeItems::model()->findByPk($_POST["id"]);
        $model->required = !$model->required;
        $model->save();
    }

    public function actionAjaxSetGroup() {
        $model = AttributeItems::model()->findByPk($_POST["id"]);
        $model->group_id = $_POST["value"];
        $model->save();
    }

    public function actionAjaxRemoveItem() {
        $model = AttributeItems::model()->findByPk($_POST["id"]);
        $model->deleteAll();
    }

    function getAttributesFormData($attribute_ids = array(0)) {
        $datas = Yii::app()->db->createCommand()
                ->select('id,title')
                ->from('attributes')
                ->where(array('not in', 'id', $attribute_ids))
                ->queryAll();
        $out = array();
        foreach ((array) $datas as $data) {
            $out[$data["id"]] = $data["title"];
        }
        return $out;
    }

    function getAttributeGroupsFormData() {
        $datas = Yii::app()->db->createCommand()
                ->select('id,group')
                ->from('attributegroups')
                ->queryAll();
        $out = array();
        foreach ((array) $datas as $data) {
            $out[$data["id"]] = $data["group"];
        }
        return $out;
    }

    public function loadModel($id) {

        $model = EavModel::model()->findByPk($id);
        if ((int) $model->id == 0) {
            $model = new EavModel();
        }
        $model->load();
        $this->enumArray();
        return $model;
    }

}

?>
