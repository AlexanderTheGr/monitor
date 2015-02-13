<?php

/**
 * This is the model class for table "tables".
 *
 * The followings are the available columns in table 'tables':
 * @property integer $id
 * @property string $tables
 */
class EavModel extends Eav {

    function EavModel() {
        $this->tableName = $this->tableName();
        $this->validationRules();
    }

    public function save() {
        return parent::save();
    }

    public function load() {
        $this->startEavModel($this);
        return parent::load();
    }

    /**
     * Returns the static model of the specified AR class.
     * @return Tables the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'eavmodel';
    }
    public function className() {
        return __CLASS__;
    }
    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('eav_model, viewstyle, softone', 'required'),
            array('eav_model', 'length', 'max' => 255),
            array('viewstyle', 'length', 'max' => 9),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, eav_model, softone, viewstyle', 'safe', 'on' => 'search'),
        );
    }

    function validationRules() {
        $this->validationRules["required"] = array("eav_model");
        $this->validationRules["unique"] = array("eav_model");
        $this->validationRules["locked"] = array("eav_model");
        //$this->validationRules["email"] = array();
        //$this->validationRules["date"] = array();
        //$this->validationRules["number"] = array();
        //$this->validationRules["phone"] = array();
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'eav_model' => 'Eav Model',
            'softone' => 'Softone',
            'viewstyle' => 'Viewstyle',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('eav_model', $this->eav_model, true);
        $criteria->compare('softone', $this->softone, true);
        $criteria->compare('viewstyle', $this->viewstyle, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function AttributeItems() {
        $datas = Yii::app()->db->createCommand()
                ->select('id')
                ->from('attribute_items')
                ->where('`eav_model`=:eav_model', array(':eav_model' => $this->eav_model))
                ->order('sort')
                ->queryAll();
        $out = array();
        foreach ($datas as $data) {
            $out[] = AttributeItems::model()->findByPk($data["id"]);
        }
        return $out;
    }
    function deleteAll() {
        foreach((array)$this->AttributeItems() as $attributeItem) {
            $attributeItem->deleteAll();
        }
        $this->deleteModel();
    }
}