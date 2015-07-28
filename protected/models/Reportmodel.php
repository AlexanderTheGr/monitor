<?php

/**
 * This is the model class for table "reportmodel".
 *
 * The followings are the available columns in table 'reportmodel':
 * @property integer $id
 * @property integer $customer_id
 * @property integer $model
 * @property string $session_id
 * @property string $ip
 * @property string $ts
 * @property integer $actioneer
 * @property string $created
 * @property string $modified
 * @property string $flat_data
 */
class Reportmodel extends Eav {

    function Reportmodel() {
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
        return 'reportmodel';
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
            array('customer_id, model, session_id, ip, ts, actioneer, created, modified, flat_data', 'required'),
            array('customer_id, model, actioneer', 'numerical', 'integerOnly' => true),
            array('session_id', 'length', 'max' => 255),
            array('ip', 'length', 'max' => 20),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, customer_id, model, session_id, ip, ts, actioneer, created, modified, flat_data', 'safe', 'on' => 'search'),
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
            'customer_id' => 'Customer',
            'model' => 'Model',
            'session_id' => 'Session',
            'ip' => 'Ip',
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
        $criteria->compare('customer_id', $this->customer_id);
        $criteria->compare('model', $this->model);
        $criteria->compare('session_id', $this->session_id, true);
        $criteria->compare('ip', $this->ip, true);
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
