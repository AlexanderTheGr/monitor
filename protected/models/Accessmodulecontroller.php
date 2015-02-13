<?php

/**
 * This is the model class for table "accessmodulecontroller".
 *
 * The followings are the available columns in table 'accessmodulecontroller':
 * @property integer $id
 * @property string $module
 * @property string $controller
 * @property string $access
 */
class Accessmodulecontroller extends Eav {

    function Accessmodulecontroller() {
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
     * @return Accessmodulecontroller the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'accessmodulecontroller';
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
            array('module, controller, access', 'required'),
            array('module, controller', 'length', 'max' => 50),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, module, controller, access', 'safe', 'on' => 'search'),
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
            'module' => 'Module',
            'controller' => 'Controller',
            'access' => 'Access',
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
        $criteria->compare('module', $this->module, true);
        $criteria->compare('controller', $this->controller, true);
        $criteria->compare('access', $this->access, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}