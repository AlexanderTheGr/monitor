<?php

/**
 * This is the model class for table "usersettings".
 *
 * The followings are the available columns in table 'usersettings':
 * @property integer $id
 * @property integer $user_id
 * @property string $key
 * @property string $label
 * @property string $type
 * @property string $multidata
 * @property string $value
 * @property string $ts
 */
class Usersettings extends Eav {

    function Usersettings() {
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
     * @return Sinallasomenos the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'usersettings';
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
			array('user_id, key, label, multidata, value, ts', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('key, label', 'length', 'max'=>255),
			array('type', 'length', 'max'=>12),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, key, label, type, multidata, value, ts', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'key' => 'Key',
			'label' => 'Label',
			'type' => 'Type',
			'multidata' => 'Multidata',
			'value' => 'Value',
			'ts' => 'Ts',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('key',$this->key,true);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('multidata',$this->multidata,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('ts',$this->ts,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}