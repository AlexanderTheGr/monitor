<?php

class Eav extends CActiveRecord {

    public $iseav = true;
    public $tableName;
    public $dbName = false;
    public $attrs = array();
    public $items = array();
    public $columns = array();
    public $groupitems = array();
    public $groups = array();
    public $groupObjs = array();
    public $modelItem = array();
    public $itemError = array();
    public $tabError = array();
    private $attribute = array();
    private $attributeIds = array();
    public $validationRules = array();
    public $viewstyle = 'none';
    private $out;
    private $model;
    protected $userrole;
    public $attributeitems = array();

    public function startEavModel($model) {
        if (!$this->dbName) {
            $curdb = explode('=', Yii::app()->db->connectionString);
            $this->dbName = $curdb[2];
        }
        $this->model = $model;
        $items = $this->items();


        $data = Yii::app()->db->createCommand()
                ->select('*')
                ->from($this->dbName . '.eavmodel')
                ->where('`eav_model`=:eav_model', array(':eav_model' => $this->tableName))
                ->queryRow();
        
        $this->viewstyle = $data["viewstyle"];
        $this->groups[1] = "General";
        foreach ((array) $items as $item) {
            $value = $this->getItemValue($item->id);
            $this->attribute[$this->createName($item->attribute()->identifier)] = $value;
            $this->attributeitems[$item->attribute()->identifier] = $value;
            $this->attributeIds[$this->createName($item->attribute()->identifier)] = $item->id;
            $this->items[$item->id] = $value;
            $this->groupitems[$item->group_id][$item->id] = $value;
            $this->modelItem[$item->id] = $item;
            $this->groups[$item->group_id] = $item->group->group;
            $this->columns[$item->group_id] = $this->columns[$item->group_id] < $item->column ? $item->column : $this->columns[$item->group_id];
            $this->groupObjs[$item->group_id] = $item->group();
        }
    }

    function validationRules() {
        $this->validationRules["required"] = array();
        $this->validationRules["selectrequired"] = array();
        $this->validationRules["unique"] = array();
        $this->validationRules["locked"] = array();
        $this->validationRules["email"] = array();
        $this->validationRules["date"] = array();
        $this->validationRules["number"] = array();
        $this->validationRules["phone"] = array();
    }

    function cssstyles() {
        return true;
    }

    /* attribute function server */

    function __call($method, $arguments) {

        if (substr($method, 0, 3) == 'get') {
            $meth = $this->from_camel_case(substr($method, 3, strlen($method) - 3));
            return array_key_exists($meth, $this->attribute) ? $this->getter($meth) : '';
        }
        if (substr($method, 0, 3) == 'set') {
            $meth = $this->from_camel_case(substr($method, 3, strlen($method) - 3));
            return array_key_exists($meth, $this->attribute) ? $this->setter($meth, $arguments) : '';
        }
    }

    function getter($meth) {
        return $this->attribute[$meth];
    }

    function setter($meth, $arguments) {
        $this->attribute[$meth] = $arguments[0];
        $this->items[$this->attributeIds[$meth]] = $this->attribute[$meth];

        //echo $this->items[$this->attributeIds[$meth]];
    }

    /* uncamelcaser: via http://www.paulferrett.com/2009/php-camel-case-functions/ */

    function from_camel_case($str) {
        $str = $this->createName($str);
        //echo $str;
        //$str[0] = strtolower($str[0]);
        $func = create_function('$c', 'return $c[1];');
        return preg_replace_callback('/([A-Z])/', $func, $str);
    }

    public function getModelViewStyle() {

        return $this->viewstyle;
    }

    public function items() {
        $this->out = array();
        if (count($this->out) > 0) {
            return $this->out;
        }
        if (!$this->dbName) {
            $curdb = explode('=', Yii::app()->db->connectionString);
            $this->dbName = $curdb[2];
        }
        $datas = Yii::app()->db->createCommand()
                ->select('id')
                ->from($this->dbName . '.attribute_items')
                ->where('`eav_model`=:eav_model', array(':eav_model' => $this->tableName))
                ->order('sort')
                ->queryAll();

        foreach ($datas as $data) {
            $this->out[] = AttributeItems::model()->findByPk($data["id"]);
        }
        return $this->out;
    }

    function getItemValue($attribute_item) {
        $data = Yii::app()->db->createCommand()
                ->select('value')
                ->from($this->dbName . '.eav')
                ->where('`attribute_item`=:attribute_item AND entity_id=:entity_id', array(':attribute_item' => $attribute_item, ':entity_id' => $this->model->id))
                ->queryRow();
        return $data["value"];
    }

    function validateUniqueItem($attribute_item, $value) {
        $data = Yii::app()->db->createCommand()
                ->select('value,entity_id')
                ->from($this->dbName . '.eav')
                ->where('`attribute_item`=:attribute_item AND value=:value', array(':attribute_item' => $attribute_item, ':value' => $value))
                ->queryRow();
        if ($this->model->id == $data["entity_id"])
            return true;
        return $data["value"] != $value ? true : false;
    }

    function validateUniqueModelAttribute($attribute, $value) {
        $data = Yii::app()->db->createCommand()
                ->select('*')
                ->from($this->tableName)
                ->where('`' . $attribute . '`=:field', array(':field' => $value))
                ->queryRow();
        if ($this->model->id == $data["id"])
            return true;
        return $data[$attribute] != $value ? true : false;
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function deleteModel() {
        $this->deleteAtttibutes();
        $this->model->delete();
    }

    private function deleteAtttibutes() {
        $items = $this->items();
        $attributeIds = $this->attributeIds;
        $attributeIds[] = 0;
        $sql = "Delete from " . $this->dbName . ".`eav` where entity_id = '" . $this->model->id . "' AND attribute_item in (" . implode(",", $attributeIds) . ")";
        Yii::app()->db->createCommand($sql)->execute();
    }

    public function save() {
        $items = $this->items();
        if ($this->validateModel()) {
            $this->deleteAtttibutes();
            $this->saveModel();
            if ($this->attrs) {
                foreach ($items as $item) {
                    $this->items[$item->id] = trim($this->attrs[$item->id]);
                }
            }
            foreach ($items as $item) {
                if (trim($this->items[$item->id]) != "") {
                    $sql = "Replace " . $this->dbName . ".`eav` set attribute_item = '" . $item->id . "', entity_id = '" . $this->model->id . "', value = '" . trim($this->items[$item->id]) . "'";
                    Yii::app()->db->createCommand($sql)->execute();
                }
            }
            $this->model->load();
            return $this->model;
        }
    }

    function validateModel() {
        $this->validateModelAttributes();

        if ($this->attrs)
            $this->validateItems();

        if (count($this->itemError) > 0) {
            return false;
        } else {
            return true;
        }
        return true;
    }
    function translate() {
        
    }
    function validateModelAttributes() {

        foreach ($this->attributes as $modelAttr => $value) {
            foreach ($this->validationRules as $rule => $attr) {
                switch ($rule) {

                    case 'required':
                        if (in_array($modelAttr, $attr)) {
                            if (trim($value) == '') {
                                $this->itemError[$modelAttr] = $this->translate("EMPTY_FIELD");
                                $this->tabError[1]++;
                            }
                            break;
                        }
                        break;
                    case 'selectrequired':
                        if (in_array($modelAttr, $attr)) {
                            if (trim($value) == "0") {
                                $this->itemError[$modelAttr] = $this->translate("REQUIRED");
                                $this->tabError[1]++;
                            }
                            break;
                        }
                        break;
                    case 'unique':
                        if (in_array($modelAttr, $attr)) {
                            if (!$this->validateUniqueModelAttribute($modelAttr, $value) AND $value != "") {
                                $this->itemError[$modelAttr] = $modelAttr . " " . $this->translate("EXIST");
                                $this->tabError[1]++;
                            }
                            break;
                        }
                        break;
                    case 'email':
                        if (in_array($modelAttr, $attr)) {
                            $validator = new CEmailValidator;
                            if ($value != "") {
                                if ($validator->validateValue($value)) {
                                    //$this->itemError[$item->id] = false;
                                } else {
                                    $this->itemError[$modelAttr] = $this->translate("INVALID_EMAIL");
                                    $this->tabError[1]++;
                                }
                            }
                            break;
                        }
                        break;
                    case 'date':
                        if (in_array($modelAttr, $attr)) {
                            if (strtotime($this->convertDate($value))) {
                                //$this->itemError[$item->id] = false;
                            } else {
                                $this->itemError[$modelAttr] = $this->translate("INVALID_DATE");
                                $this->tabError[1]++;
                            }
                            break;
                        }
                        break;
                    case 'number':
                        if (in_array($modelAttr, $attr)) {
                            if (is_numeric($value)) {
                                //$this->itemError[$item->id] = false;
                            } else {
                                $this->itemError[$modelAttr] = $this->translate("INVALID_NUMBER");
                                $this->tabError[1]++;
                            }
                            break;
                        }
                        break;
                    case 'phone':
                        if (in_array($modelAttr, $attr)) {
                            if ((int) $value > 0) {
                                //$this->itemError[$item->id] = false;
                            } else {
                                $this->itemError[$modelAttr] = $this->translate("INVALID_PHONE");
                                $this->tabError[1]++;
                            }
                            break;
                        }
                        break;
                    case 'unique':
                        if (in_array($modelAttr, $attr)) {
                            //$this->itemError[$modelAttr] = "INVALID_phone";
                            break;
                        }
                        break;
                    default:
                        //$this->itemError[$item->id] = false;
                        break;
                }
            }
        }
    }

    function validateItems() {
        //return true;
        //$ls = Langtranslater::getSingleton();
        $items = $this->items();
        foreach ($items as $item) {
            //$this->items[$item->id] = trim($this->items[$item->id]);
            $this->items[$item->id] = trim($_POST["attrs"][$item->id]);
            if ($item->required) {
                if (trim($this->items[$item->id]) == '') {
                    $this->itemError[$item->id] = $this->translate("EMPTY_FIELD");
                    $this->tabError[$item->group_id]++;
                } else {
                    //$this->itemError[$item->id] = false;
                }
            }
            if ($item->unique) {
                if ($this->validateUniqueItem($item->id, trim($this->items[$item->id]))) {
                    
                } elseif (trim($this->items[$item->id]) != "") {
                    $this->itemError[$item->id] = $item->attribute()->title . " " . $this->translate("EXISTS");
                    $this->tabError[$item->group_id]++;
                    //$this->itemError[$item->id] = false;
                }
            }
            if ((int) $this->tabError[$item->group_id] == 0)
                switch ($item->attribute()->validation) {
                    case 'email':
                        if ($this->items[$item->id] != "") {
                            $validator = new CEmailValidator;
                            if ($validator->validateValue($this->items[$item->id])) {
                                //$this->itemError[$item->id] = false;
                            } else {
                                $this->itemError[$item->id] = $this->translate("INVALID_EMAIL");
                                $this->tabError[$item->group_id]++;
                            }
                        }
                        break;
                    case 'date':
                        if (strtotime($this->convertDate($this->items[$item->id]))) {
                            //$this->itemError[$item->id] = false;
                        } else {
                            $this->itemError[$item->id] = $this->translate("INVALID_DATE");
                            $this->tabError[$item->group_id]++;
                        }
                        break;
                    case 'number':
                        if (is_numeric($this->items[$item->id])) {
                            //$this->itemError[$item->id] = false;
                        } else {
                            $this->itemError[$item->id] = $this->translate("INVALID_NUMBER");
                            $this->tabError[$item->group_id]++;
                        }
                        break;
                    case 'phone':
                        if ((int) $this->items[$item->id] > 0) {
                            //$this->itemError[$item->id] = false;
                        } else {
                            $this->itemError[$item->id] = $this->translate("INVALID_PHONE");
                            $this->tabError[$item->group_id]++;
                        }
                        break;

                    default:
                        //$this->itemError[$item->id] = false;
                        break;
                }
        }
    }

    function getAccess($userrole, $field) {
        //return 'admin';
        $modelFieldAccess = $this->getModelFieldAccess($userrole, $field);
        $modelAccess = $this->getModelAccess($userrole);


        $generalgroup = AttributeGroups::model()->findByPk(1);
        $groupaccess = unserialize($generalgroup->access);


        $groupaccess[$userrole];

        switch ($groupaccess[$userrole]) {
            case "block":
                return block;
            case "view":
                if ($modelAccess == "admin")
                    $modelAccess = "view";
                break;
            case "admin":
                //$modelAccess;
                break;
            default:
                return block;
        }


        switch ($modelAccess) {
            case "block":
                return block;
            case "view":
                switch ($modelFieldAccess) {
                    case "block":
                        return block;
                    case "view":
                        return view;
                    case "admin":
                        return view;
                    default:
                        return block;
                }
            case "admin":
                switch ($modelFieldAccess) {
                    case "block":
                        return block;
                    case "view":
                        return view;
                    case "admin":
                        return admin;
                    default:
                        return block;
                }
        }
    }

    function getModelAccess($userrole) {
        //return 'admin';
        $sql = "Select * from accessmodel where model = '" . $this->model->className() . "'";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        $access = unserialize($data["access"]);
        return $access[$userrole];
    }

    function getModelFieldAccess($userrole, $field) {
        //return 'admin';
        $sql = "Select * from accessmodelfield where model = '" . $this->model->className() . "' AND field = '" . $field . "'";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        $access = unserialize($data["access"]);
        return $access[$userrole];
    }

    function load() {
        $sql = "Select * from accessmodel where model = '" . $this->model->className() . "'";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        if (!$data) {
            $sql = "Insert accessmodel set id='" . $this->getSysId() . "', access='" . $this->defaultAccess() . "', model = '" . $this->model->className() . "'";
            Yii::app()->db->createCommand($sql)->execute();
        }

        foreach ($this->model->attributeLabels() as $field => $label) {
            if ($field == "id" OR $field == "ts")
                continue;
            $sql = "Select * from accessmodelfield where model = '" . $this->model->className() . "' AND field = '" . $field . "'";
            $data = Yii::app()->db->createCommand($sql)->queryRow();
            if (!$data) {
                $sql = "Insert accessmodelfield set id='" . $this->getSysId() . "', access='" . $this->defaultAccess() . "', model = '" . $this->model->className() . "', field = '" . $field . "'";
                Yii::app()->db->createCommand($sql)->execute();
            }
        }
        return parent::load();
    }

    function defaultAccess() {
        return serialize(array("admin" => "admin"));
    }

    public function relations($array = array()) {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return $array;
    }

    function createName($str) {
        $strArr = explode("_", $str);
        $i = 0;
        $b = "";
        foreach ($strArr as $a) {
            $b .= ucfirst($a);
        }
        $strArr = explode(".", $b);
        $i = 0;
        $b = "";
        foreach ($strArr as $a) {
            $b .= ucfirst($a);
        }        
        return $b;
    }

    public function saveModel() {
        
        if ((int) $this->model->id == 0) {
            $this->model->setIsNewRecord(true);
            $user = User::model()->findByPk(Yii::app()->user->id);
            $this->model->id = $this->getSysId();
            if ($this->model->hasAttribute("created"))
                $this->model->created = date('Y-m-d H:i:s');
        }
        if ($this->model->hasAttribute("actioneer"))
            $this->model->actioneer = Yii::app()->user->id;
        if ($this->model->hasAttribute("modified"))
            $this->model->modified = date('Y-m-d H:i:s');
        if ($this->model->hasAttribute("ts"))
            $this->model->ts = date('Y-m-d H:i:s');
        
        $this->model->getIsNewRecord() ? $this->model->insert($attributes) : $this->model->update($attributes);

        if ((int) $this->model->id == 0) {
            $this->model->id = Yii::app()->db->getLastInsertID();
            $this->model->findByPk($model->model->id);
        }

        return $this->model;
    }

    function getSysId() {
        $sql = "Select id from sys";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        $data["id"]++;
        $sql = "update sys set id = '" . $data["id"] . "'";
        Yii::app()->db->createCommand($sql)->execute();


        return $data["id"];
    }

    function array_reverse_order($array) {
        $array_key = array_keys($array);
        $array_value = array_values($array);

        $array_return = array();
        for ($i = 1, $size_of_array = sizeof($array_key); $i <= $size_of_array; $i++) {
            $array_return[$array_key[$size_of_array - $i]] = $array_value[$size_of_array - $i];
        }
        return $array_return;
    }

    public function actioneer() {
        $model = User::model()->findByPk($this->actioneer);
        if ((int) $model->id == 0) {
            $model = new User();
        }
        $model->load();
        return $model;
    }

}

?>
