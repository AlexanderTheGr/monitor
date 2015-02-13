<?php

/**
 * This is the model class for table "accesslog".
 *
 * The followings are the available columns in table 'accesslog':
 * @property integer $id
 * @property integer $user_id
 * @property string $ip
 * @property string $actiontype
 * @property string $path
 * @property string $notes
 * @property string $ts
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Accesslog extends Eav {

    function User() {
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

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'accesslog';
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
            array('user_id, ip, actiontype, path, notes, ts', 'required'),
            array('user_id', 'numerical', 'integerOnly' => true),
            array('ip', 'length', 'max' => 15),
            array('actiontype', 'length', 'max' => 11),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, user_id, ip, actiontype, path, notes, ts', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'user_id' => 'User',
            'ip' => 'Ip',
            'actiontype' => 'Actiontype',
            'path' => 'Path',
            'notes' => 'Notes',
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
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('ip', $this->ip, true);
        $criteria->compare('actiontype', $this->actiontype, true);
        $criteria->compare('path', $this->path, true);
        $criteria->compare('notes', $this->notes, true);
        $criteria->compare('ts', $this->ts, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}