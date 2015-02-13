<?php

/**
 * This is the model class for table "brand_model_type".
 *
 * The followings are the available columns in table 'brand_model_type':
 * @property integer $id
 * @property integer $brand_model
 * @property string $brand_model_type
 * @property string $nodes
 * @property integer $need_update
 * @property string $updated
 * @property integer $enable
 * @property string $motor_type
 * @property integer $power_hp
 * @property string $details
 * @property string $engine
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property BrandModel $brandModel
 */
class BrandModelType extends Eav {

    function BrandModelType() {
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

    public function className() {
        return __CLASS__;
    }

    public function tableName() {
        return 'brand_model_type';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('brand_model, brand_model_type, nodes, updated, motor_type, power_hp, details, engine', 'required'),
            array('brand_model, need_update, enable, power_hp, status', 'numerical', 'integerOnly' => true),
            array('brand_model_type, motor_type', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, brand_model, brand_model_type, nodes, need_update, updated, enable, motor_type, power_hp, details, engine, status', 'safe', 'on' => 'search'),
        );
    }
    
    function getFullTitle() {
        return $this->_brandModel_->_brand_->brand." ".$this->_brandModel_->brand_model." ".$this->brand_model_type;
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            '_brandModel_' => array(self::BELONGS_TO, 'BrandModel', 'brand_model'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'brand_model' => 'Brand Model',
            'brand_model_type' => 'Brand Model Type',
            'nodes' => 'Nodes',
            'need_update' => 'Need Update',
            'updated' => 'Updated',
            'enable' => 'Enable',
            'motor_type' => 'Motor Type',
            'power_hp' => 'Power Hp',
            'details' => 'Details',
            'engine' => 'Engine',
            'status' => 'Status',
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
        $criteria->compare('brand_model', $this->brand_model);
        $criteria->compare('brand_model_type', $this->brand_model_type, true);
        $criteria->compare('nodes', $this->nodes, true);
        $criteria->compare('need_update', $this->need_update);
        $criteria->compare('updated', $this->updated, true);
        $criteria->compare('enable', $this->enable);
        $criteria->compare('motor_type', $this->motor_type, true);
        $criteria->compare('power_hp', $this->power_hp);
        $criteria->compare('details', $this->details, true);
        $criteria->compare('engine', $this->engine, true);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return BrandModelType the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
