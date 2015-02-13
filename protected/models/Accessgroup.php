<?php

/**
 * This is the model class for table "accessgroup".
 *
 * The followings are the available columns in table 'accessgroup':
 * @property integer $id
 * @property string $title
 * @property string $notes
 * @property string $data
 * @property string $ts
 */
class Accessgroup extends Eav {

    function Accessgroup() {
        $this->tableName = $this->tableName();
        $this->validationRules();
    }

    public function save() {
        return parent::save();
    }

    public function load() {
        $this->startEavModel($this);
        return parent::load();
    }


    /**
     * Returns the static model of the specified AR class.
     * @return Accessgroup the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'accessgroup';
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
            array('title, notes, data, ts', 'required'),
            array('title', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, title, notes, data, access, ts', 'safe', 'on' => 'search'),
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
            'title' => 'Title',
            'notes' => 'Notes',
            'data' => 'Data',
            'access' => 'Access',
            'ts' => 'Ts',
        );
    }
    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('notes', $this->notes, true);
        $criteria->compare('access', $this->accesss, true);
        $criteria->compare('data', $this->data, true);
        $criteria->compare('ts', $this->ts, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}