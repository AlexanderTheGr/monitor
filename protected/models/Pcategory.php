<?php

/**
 * This is the model class for table "pcategory".
 *
 * The followings are the available columns in table 'pcategory':
 * @property integer $id
 * @property integer $reference
 * @property integer $itecategory_mtrcategory
 * @property string $itecategory_code
 * @property string $itecategory_name
 * @property string $ts
 * @property integer $actioneer
 * @property string $created
 * @property string $modified
 * @property string $flat_data
 */
class Pcategory extends Eav {

    function Pcategory() {
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

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'pcategory';
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
            array('id, reference, itecategory_mtrcategory, itecategory_code, itecategory_name, ts, actioneer, created, modified, flat_data', 'required'),
            array('id, reference, itecategory_mtrcategory, actioneer', 'numerical', 'integerOnly' => true),
            array('itecategory_code, itecategory_name', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, reference, itecategory_mtrcategory, itecategory_code, itecategory_name, ts, actioneer, created, modified, flat_data', 'safe', 'on' => 'search'),
        );
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
            'reference' => 'Reference',
            'itecategory_mtrcategory' => 'Itecategory Mtrcategory',
            'itecategory_code' => 'Itecategory Code',
            'itecategory_name' => 'Itecategory Name',
            'ts' => 'Ts',
            'actioneer' => 'Actioneer',
            'created' => 'Created',
            'modified' => 'Modified',
            'flat_data' => 'Flat Data',
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
        $criteria->compare('reference', $this->reference);
        $criteria->compare('itecategory_mtrcategory', $this->itecategory_mtrcategory);
        $criteria->compare('itecategory_code', $this->itecategory_code, true);
        $criteria->compare('itecategory_name', $this->itecategory_name, true);
        $criteria->compare('ts', $this->ts, true);
        $criteria->compare('actioneer', $this->actioneer);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('modified', $this->modified, true);
        $criteria->compare('flat_data', $this->flat_data, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
