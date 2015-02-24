<?php

/**
 * This is the model class for table "order_items".
 *
 * The followings are the available columns in table 'order_items':
 * @property integer $id
 * @property integer $order
 * @property integer $product
 * @property integer $qty
 * @property string $price
 */
class OrderItem extends Eav {

    function OrderItem() {
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
        return 'orderitem';
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
            array('id, order, product, qty, price,lineval,disc1prc', 'required'),
            //array('id, order, product, qty', 'numerical', 'integerOnly'=>true),
            //array('price', 'length', 'max'=>10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, order, product, qty, price,lineval,disc1prc', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            '_product_' => array(self::BELONGS_TO, 'Product', 'product'),
            '_order_' => array(self::BELONGS_TO, 'Order', 'order'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'order' => 'Order',
            'product' => 'Product',
            'qty' => 'Qty',
            'price' => 'Price',
            'lineval' => 'Lineval',
            'disc1prc' => 'disc1prc',
            
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
        $criteria->compare('order', $this->order);
        $criteria->compare('product', $this->product);
        $criteria->compare('qty', $this->qty);
        $criteria->compare('price', $this->price, true);
        $criteria->compare('lineval', $this->lineval, true);
        $criteria->compare('disc1prc', $this->disc1prc, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
