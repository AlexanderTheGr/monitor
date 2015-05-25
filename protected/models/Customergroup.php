<?php

/**
 * This is the model class for table "customerrulegroup".
 *
 * The followings are the available columns in table 'customerrulegroup':
 * @property integer $id
 * @property string $title
 *
 * The followings are the available model relations:
 * @property Customerrule[] $customerrules
 * @property Customerrulegrouprule[] $customerrulegrouprules
 */
class Customergroup extends Eav {

    function Customergroup() {
        $this->tableName = $this->tableName();
        $this->validationRules();
    }

    public function save($runValidation = true, $attributes = NULL) {
        return parent::save();
    }

    public function load() {
        $this->startEavModel($this);
        return parent::load();
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'customergroup';
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
            array('title, base_price', 'required'),
            array('title, base_price', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, base_price', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            '_customergrouprules_' => array(self::HAS_MANY, 'Customergrouprule', 'group'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'title' => 'Title',
            'base_price' => 'Base Price',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('base_price', $this->base_price, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }



}
