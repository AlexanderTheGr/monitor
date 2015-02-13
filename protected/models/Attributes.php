<?php

/**
 * This is the model class for table "attributes".
 *
 * The followings are the available columns in table 'attributes':
 * @property integer $id
 * @property string $type
 * @property string $identifier
 * @property string $title
 * @property integer $required
 * @property integer $visible
 * @property integer $searchable
 * @property string $validation
 * @property string $select_data
 */
class Attributes extends Eav {

    function Attributes() {
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
     * @return Attributes the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'attributes';
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
            array('type, identifier, title, css, visible, searchable, validation,unique,locked', 'required'),
            array('required, visible, searchable', 'numerical', 'integerOnly' => true),
            array('type', 'length', 'max' => 12),
            array('identifier, title', 'length', 'max' => 100),
            array('validation', 'length', 'max' => 6),
            array('select_data', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, type, identifier, title, required, visible, searchable, validation, select_data,unique,locked', 'safe', 'on' => 'search'),
        );
    }

    function validationRules() {
        $this->validationRules["required"] = array("type", "identifier", "title");
        $this->validationRules["unique"] = array("identifier");
        $this->validationRules["locked"] = array("identifier", "type");
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
            'type' => 'Type',
            'identifier' => 'Identifier',
            'title' => 'Title',
            'css' => "Css",
            'required' => 'Required',
            'visible' => 'Visible',
            'searchable' => 'Searchable',
            'validation' => 'Validation',
            'unique' => 'Unique',
            'locked' => 'Locked',
            'select_data' => 'Select Data',
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
        $criteria->compare('type', $this->type, true);
        $criteria->compare('identifier', $this->identifier, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('css', $this->css, true);
        $criteria->compare('required', $this->required);
        $criteria->compare('visible', $this->visible);
        $criteria->compare('searchable', $this->searchable);
        $criteria->compare('validation', $this->validation, true);
        $criteria->compare('unique', $this->unique, true);
        $criteria->compare('locked', $this->locked, true);
        $criteria->compare('select_data', $this->select_data, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}