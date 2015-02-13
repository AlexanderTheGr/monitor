<?php

/**
 * This is the model class for table "tecdoc_supplier".
 *
 * The followings are the available columns in table 'tecdoc_supplier':
 * @property integer $id
 * @property string $supplier
 * @property string $ts
 * @property integer $actioneer
 * @property string $created
 * @property string $modified
 *
 * The followings are the available model relations:
 * @property Product[] $products
 */
class TecdocSupplier extends Eav {

    function TecdocSupplier() {
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
        return 'tecdoc_supplier';
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
            array('supplier, ts, actioneer, created, modified', 'required'),
            array('actioneer', 'numerical', 'integerOnly' => true),
            array('supplier', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, supplier, ts, actioneer, created, modified', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'products' => array(self::HAS_MANY, 'Product', 'tecdoc_supplier_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'supplier' => 'Supplier',
            'ts' => 'Ts',
            'actioneer' => 'Actioneer',
            'created' => 'Created',
            'modified' => 'Modified',
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
        $criteria->compare('supplier', $this->supplier, true);
        $criteria->compare('ts', $this->ts, true);
        $criteria->compare('actioneer', $this->actioneer);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('modified', $this->modified, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
