<?php

/**
 * This is the model class for table "customeradress".
 *
 * The followings are the available columns in table 'customeradress':
 * @property integer $id
 * @property integer $reference
 * @property integer $customer
 * @property string $code
 * @property string $name
 * @property integer $country
 * @property string $city
 * @property string $distrinct
 * @property integer $distrinct1
 * @property string $address
 * @property string $email
 * @property integer $branch
 * @property string $discount
 * @property integer $iscenter
 * @property integer $isactive
 * @property integer $vatsts
 *
 * The followings are the available model relations:
 * @property Customer $customer0
 */
class Customeradress extends Eav {

    function Customeradress() {
        $this->tableName = $this->tableName();
        $this->validationRules();
    }


    public function load() {
        $this->startEavModel($this);
        return parent::load();
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'customeradress';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('reference, customer, code, name, city, distrinct, distrinct1, address, email, branch, discount, iscenter, isactive', 'required'),
            array('reference, customer, country, distrinct1, branch, iscenter, isactive, vatsts', 'numerical', 'integerOnly' => true),
            array('code', 'length', 'max' => 20),
            array('name, city, distrinct, address, email', 'length', 'max' => 255),
            array('discount', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, reference, customer, code, name, country, city, distrinct, distrinct1, address, email, branch, discount, iscenter, isactive, vatsts', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            '_customer_' => array(self::BELONGS_TO, 'Customer', 'customer'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'reference' => 'Reference',
            'customer' => 'Customer',
            'code' => 'Code',
            'name' => 'Name',
            'country' => 'Country',
            'city' => 'City',
            'distrinct' => 'Distrinct',
            'distrinct1' => 'Distrinct1',
            'address' => 'Address',
            'email' => 'Email',
            'branch' => 'Branch',
            'discount' => 'Discount',
            'iscenter' => 'Iscenter',
            'isactive' => 'Isactive',
            'vatsts' => 'Vatsts',
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
        $criteria->compare('customer', $this->customer);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('country', $this->country);
        $criteria->compare('city', $this->city, true);
        $criteria->compare('distrinct', $this->distrinct, true);
        $criteria->compare('distrinct1', $this->distrinct1);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('branch', $this->branch);
        $criteria->compare('discount', $this->discount, true);
        $criteria->compare('iscenter', $this->iscenter);
        $criteria->compare('isactive', $this->isactive);
        $criteria->compare('vatsts', $this->vatsts);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }



}
