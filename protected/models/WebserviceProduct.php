<?php

/**
 * This is the model class for table "webservice_products".
 *
 * The followings are the available columns in table 'webservice_products':
 * @property integer $id
 * @property integer $product
 * @property integer $webservice
 * @property integer $article_id
 *
 * The followings are the available model relations:
 * @property Webservice $webservice0
 * @property Product $product0
 */
class WebserviceProduct extends Eav {

    
    
    function WebserviceProduct() {
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
        return 'webservice_product';
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
            array('product, webservice, article_id', 'required'),
            array('product, webservice, article_id', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, product, webservice, article_id, article_name', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            '_webservice_' => array(self::BELONGS_TO, 'Webservice', 'webservice'),
            '_product_' => array(self::BELONGS_TO, 'Product', 'product'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'product' => 'Product',
            'webservice' => 'Webservice',
            'article_id' => 'Article',
            'article_name' => 'Article Name'
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
        $criteria->compare('product', $this->product);
        $criteria->compare('webservice', $this->webservice);
        $criteria->compare('article_id', $this->article_id);
        $criteria->compare('article_name', $this->article_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
