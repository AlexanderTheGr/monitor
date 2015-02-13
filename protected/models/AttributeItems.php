<?php

/**
 * This is the model class for table "attribute_items".
 *
 * The followings are the available columns in table 'attribute_items':
 * @property integer $id
 * @property string $table
 * @property integer $attribute_id
 * @property string $select_data
 * @property integer $required
 * @property integer $visible
 */
class AttributeItems extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return AttributeItems the static model class
     */
    public $iseav = false;
    public $itemError = "";
    public $tabError = "";

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'attribute_items';
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
        //array('table, attribute_id, select_data, required, visible', 'required'),
        //array('attribute_id, required, visible', 'numerical', 'integerOnly'=>true),
        //array('table, select_data', 'length', 'max'=>255),
        // The following rule is used by search().
        // Please remove those attributes that should not be searched.
        //array('id, table, attribute_id, select_data, required, visible', 'safe', 'on'=>'search'),
        );
    }
    
    


    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'attribute' => array(self::BELONGS_TO, 'Attributes', 'attribute_id'),
            'group' => array(self::BELONGS_TO, 'AttributeGroups', 'group_id')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() 
    { 
        return array( 
            'id' => 'ID',
            'eav_model' => 'Eav Model',
            'attribute_id' => 'Attribute',
            'title' => 'Title',
            'css' => 'Css',
            'list_style' => 'List Style',
            'select_data' => 'Select Data',
            'required' => 'Required',
            'visible' => 'Visible',
            'unique' => 'Unique',
            'sort' => 'Sort',
            'column' => 'Column',
            'group_id' => 'Group',
            'access' => 'Access',
            'ts' => 'Ts',
        ); 
    } 
    function deleteAll() {
        $sql = "Delete from `eav` where  attribute_item = '".$this->id."'";
        Yii::app()->db->createCommand($sql)->execute();
        $this->delete();
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
        $criteria->compare('eav_model',$this->eav_model,true);
        $criteria->compare('attribute_id',$this->attribute_id);
        $criteria->compare('title',$this->title,true);
        $criteria->compare('css',$this->css,true);
        $criteria->compare('list_style',$this->list_style,true);
        $criteria->compare('select_data',$this->select_data,true);
        $criteria->compare('required',$this->required);
        $criteria->compare('visible',$this->visible);
        $criteria->compare('unique',$this->unique);
        $criteria->compare('sort',$this->sort);
        $criteria->compare('column',$this->column);
        $criteria->compare('group_id',$this->group_id);
        $criteria->compare('access',$this->access,true);
        $criteria->compare('ts',$this->ts,true);

        return new CActiveDataProvider($this, array( 
            'criteria'=>$criteria, 
        )); 
    } 

}