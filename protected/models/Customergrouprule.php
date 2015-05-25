<?php

/**
 * This is the model class for table "customerrulegrouprule".
 *
 * The followings are the available columns in table 'customerrulegrouprule':
 * @property integer $id
 * @property integer $group
 * @property string $val
 * @property string $supplier
 *
 * The followings are the available model relations:
 * @property Customerrulegroup $group0
 */
class Customergrouprule extends Eav {

    function Customergrouprule() {
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
        return 'customergrouprule';
    }

    public function className() {
        return __CLASS__;
    }

    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('group, val, supplier', 'required'),
            array('group', 'numerical', 'integerOnly' => true),
            array('val', 'length', 'max' => 10),
            array('supplier', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, group, val, supplier', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            '_group_' => array(self::BELONGS_TO, 'Customergroup', 'group'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'group' => 'Group',
            'val' => 'Val',
            'supplier' => 'Supplier',
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
        $criteria->compare('group', $this->group);
        $criteria->compare('val', $this->val, true);
        $criteria->compare('supplier', $this->supplier, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }


}
