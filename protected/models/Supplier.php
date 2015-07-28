<?php

/**
 * This is the model class for table "supplier".
 *
 * The followings are the available columns in table 'supplier':
 * @property integer $id
 * @property integer $reference
 * @property string $supplier_code
 * @property string $supplier_trdr_supfindata_lbal
 * @property string $supplier_name
 * @property string $supplier_afm
 * @property string $supplier_address
 * @property string $supplier_district
 * @property string $supplier_zip
 * @property string $supplier_city
 * @property string $supplier_phone01
 * @property string $supplier_phone02
 * @property string $supplier_fax
 * @property string $supplier_webpage
 * @property string $supplier_upddate
 * @property string $supplier_insdate
 * @property string $ts
 * @property integer $actioneer
 * @property string $created
 * @property string $modified
 * @property string $flat_data
 */
class Supplier extends Eav {

    function Supplier() {
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
        return 'supplier';
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
            array('id, reference, supplier_code, supplier_trdr_supfindata_lbal, supplier_name, supplier_afm, supplier_address, supplier_district, supplier_zip, supplier_city, supplier_phone01, supplier_phone02, supplier_fax, supplier_webpage, supplier_upddate, supplier_insdate, ts, actioneer, created, modified, flat_data', 'required'),
            array('id, reference, actioneer', 'numerical', 'integerOnly' => true),
            array('supplier_code, supplier_trdr_supfindata_lbal, supplier_name, supplier_afm, supplier_address, supplier_district, supplier_zip, supplier_city, supplier_phone01, supplier_phone02, supplier_fax, supplier_webpage', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, reference, supplier_code, supplier_trdr_supfindata_lbal, supplier_name, supplier_afm, supplier_address, supplier_district, supplier_zip, supplier_city, supplier_phone01, supplier_phone02, supplier_fax, supplier_webpage, supplier_upddate, supplier_insdate, ts, actioneer, created, modified, flat_data', 'safe', 'on' => 'search'),
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
            'supplier_code' => 'Supplier Code',
            'supplier_trdr_supfindata_lbal' => 'Supplier Trdr Supfindata Lbal',
            'supplier_name' => 'Supplier Name',
            'supplier_afm' => 'Supplier Afm',
            'supplier_address' => 'Supplier Address',
            'supplier_district' => 'Supplier District',
            'supplier_zip' => 'Supplier Zip',
            'supplier_city' => 'Supplier City',
            'supplier_phone01' => 'Supplier Phone01',
            'supplier_phone02' => 'Supplier Phone02',
            'supplier_fax' => 'Supplier Fax',
            'supplier_webpage' => 'Supplier Webpage',
            'supplier_upddate' => 'Supplier Upddate',
            'supplier_insdate' => 'Supplier Insdate',
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
        $criteria->compare('supplier_code', $this->supplier_code, true);
        $criteria->compare('supplier_trdr_supfindata_lbal', $this->supplier_trdr_supfindata_lbal, true);
        $criteria->compare('supplier_name', $this->supplier_name, true);
        $criteria->compare('supplier_afm', $this->supplier_afm, true);
        $criteria->compare('supplier_address', $this->supplier_address, true);
        $criteria->compare('supplier_district', $this->supplier_district, true);
        $criteria->compare('supplier_zip', $this->supplier_zip, true);
        $criteria->compare('supplier_city', $this->supplier_city, true);
        $criteria->compare('supplier_phone01', $this->supplier_phone01, true);
        $criteria->compare('supplier_phone02', $this->supplier_phone02, true);
        $criteria->compare('supplier_fax', $this->supplier_fax, true);
        $criteria->compare('supplier_webpage', $this->supplier_webpage, true);
        $criteria->compare('supplier_upddate', $this->supplier_upddate, true);
        $criteria->compare('supplier_insdate', $this->supplier_insdate, true);
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
