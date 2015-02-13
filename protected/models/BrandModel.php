<?php

/**
 * This is the model class for table "brand_model".
 *
 * The followings are the available columns in table 'brand_model':
 * @property integer $id
 * @property integer $brand
 * @property integer $group
 * @property string $group_name
 * @property string $brand_model
 * @property integer $year_from
 * @property integer $year_to
 * @property integer $enable
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Brand $brand0
 * @property BrandModelType[] $brandModelTypes
 */
class BrandModel extends Eav {

    function BrandModel() {
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
        return 'brand_model';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('brand, group, group_name, brand_model, year_from, year_to', 'required'),
            array('brand, group, year_from, year_to, enable, status', 'numerical', 'integerOnly' => true),
            array('group_name, brand_model', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, brand, group, group_name, brand_model, year_from, year_to, enable, status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            '_brand_' => array(self::BELONGS_TO, 'Brand', 'brand'),
            '_brandModelTypes_' => array(self::HAS_MANY, 'BrandModelType', 'brand_model'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'brand' => 'Brand',
            'group' => 'Group',
            'group_name' => 'Group Name',
            'brand_model' => 'Brand Model',
            'year_from' => 'Year From',
            'year_to' => 'Year To',
            'enable' => 'Enable',
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
        $criteria->compare('brand', $this->brand);
        $criteria->compare('group', $this->group);
        $criteria->compare('group_name', $this->group_name, true);
        $criteria->compare('brand_model', $this->brand_model, true);
        $criteria->compare('year_from', $this->year_from);
        $criteria->compare('year_to', $this->year_to);
        $criteria->compare('enable', $this->enable);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return BrandModel the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
