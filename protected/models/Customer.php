<?php

/**
 * This is the model class for table "customer".
 *
 * The followings are the available columns in table 'customer':
 * @property integer $id
 * @property string $email
 * @property string $username
 * @property string $password
 * @property string $ts
 * @property string $status
 * @property integer $actioneer
 * @property string $created
 * @property string $modified
 *
 * The followings are the available model relations:
 * @property Order[] $orders
 */
class Customer extends Eav {

    function Customer() {
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
        return 'customer';
    }

    public function className() {
        return __CLASS__;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('reference, customer_code, customer_name, customer_afm, customer_address, customer_district, customer_zip, customer_phone01, customer_phone02, customer_fax, customer_webpage, customer_email, ts, created, modified, flat_data', 'required'),
            array('reference, customer_afm, customer_zip, actioneer', 'numerical', 'integerOnly'=>true),
            array('email, username', 'length', 'max'=>45),
            array('password', 'length', 'max'=>80),
            array('customer_code, customer_name, customer_address, customer_district, customer_phone01, customer_phone02, customer_fax, customer_webpage, customer_email', 'length', 'max'=>255),
            array('status', 'length', 'max'=>8),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, reference, email, username, password, customer_code, customer_name, customer_afm, customer_address, customer_district, customer_zip, customer_phone01, customer_phone02, customer_fax, customer_webpage, customer_email, ts, status, actioneer, created, modified, flat_data', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'orders' => array(self::HAS_MANY, 'Order', 'customer'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'reference' => 'Reference',
            'email' => 'Email',
            'username' => 'Username',
            'password' => 'Password',
            'customer_code' => 'Customer Code',
            'customer_name' => 'Customer Name',
            'customer_afm' => 'Customer Afm',
            'customer_address' => 'Customer Address',
            'customer_district' => 'Customer District',
            'customer_zip' => 'Customer Zip',
            'customer_phone01' => 'Customer Phone01',
            'customer_phone02' => 'Customer Phone02',
            'customer_fax' => 'Customer Fax',
            'customer_webpage' => 'Customer Webpage',
            'customer_email' => 'Customer Email',
            'ts' => 'Ts',
            'status' => 'Status',
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
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('reference',$this->reference);
        $criteria->compare('email',$this->email,true);
        $criteria->compare('username',$this->username,true);
        $criteria->compare('password',$this->password,true);
        $criteria->compare('customer_code',$this->customer_code,true);
        $criteria->compare('customer_name',$this->customer_name,true);
        $criteria->compare('customer_afm',$this->customer_afm);
        $criteria->compare('customer_address',$this->customer_address,true);
        $criteria->compare('customer_district',$this->customer_district,true);
        $criteria->compare('customer_zip',$this->customer_zip);
        $criteria->compare('customer_phone01',$this->customer_phone01,true);
        $criteria->compare('customer_phone02',$this->customer_phone02,true);
        $criteria->compare('customer_fax',$this->customer_fax,true);
        $criteria->compare('customer_webpage',$this->customer_webpage,true);
        $criteria->compare('customer_email',$this->customer_email,true);
        $criteria->compare('ts',$this->ts,true);
        $criteria->compare('status',$this->status,true);
        $criteria->compare('actioneer',$this->actioneer);
        $criteria->compare('created',$this->created,true);
        $criteria->compare('modified',$this->modified,true);
        $criteria->compare('flat_data',$this->flat_data,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    function setFlat() {
        $flat = $this->attributes;        
        $flat["attributeitems"] = $this->attributeitems;
        unset($flat["flat_data"]);
        $json = json_encode($flat);
        $this->flat_data = $json;
        $this->save();
    }
}
