<?php

/**
 * This is the model class for table "order".
 *
 * The followings are the available columns in table 'order':
 * @property integer $id
 * @property integer $customer
 * @property integer $status
 * @property integer $actioneer
 * @property string $created
 * @property string $modified
 *
 * The followings are the available model relations:
 * @property Customer $customer0
 */
class Order extends Eav {

    function Order() {
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

    function getSoftoneOrderItems() {
        $monitor = new Monitor();
        $orderdata = $monitor->getData("SALDOC", $this->reference);
        return $orderdata->data->ITELINES;
    }

    function saveSoftone() {
        $monitor = new Monitor();
        $orderdata = $monitor->getData("SALDOC", $this->reference);
        print_r($monitor->setData($orderdata, "SALDOC", $this->reference));
        //print_r($monitor->setData($orderdata, "SALDOC", $this->reference));
    }

    function addOrderItem($id) {
        //$item->MTRL;
        echo $id;
        $product = $product = Product::model()->findByAttributes(array('id' => $id));

        $orderitem = new OrderItem;
        $orderitem->product = $product->id;
        $orderitem->order = $this->id;
        $orderitem->qty = 0;
        $orderitem->price = 0;
        $orderitem->save(false);

    }

    function createOrderItems() {
        $items = $this->getSoftoneOrderItems();
        foreach ($this->_items_ as $item) {
            $item->delete();
        }
        //print_r($items);
        foreach ($items as $item) {
            //$item->MTRL;

            $product = Product::model()->findByAttributes(array('reference' => $item->MTRL));


            $orderitem = new OrderItem;
            $orderitem->product = $product->id;
            $orderitem->order = $this->id;
            $orderitem->qty = $item->QTY;
            $orderitem->price = $item->PRICE;

            $orderitem->save(false);
        }
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'order';
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
            array('reference, customer_name,comments,customer,fullytrans,user, tfprms, fprms, noorder, insdate, seriesnum, series, fincode, status, created, modified', 'required'),
            array('reference, customer_name,comments, customer,fullytrans,user tfprms, fprms, noorder, seriesnum, series, status, actioneer', 'numerical', 'integerOnly' => true),
            array('fincode', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, reference, customer,user, tfprms, fprms, insdate, seriesnum,fullytrans, noorder, series, fincode, status, actioneer, created, modified', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            '_customer_' => array(self::BELONGS_TO, 'Customer', 'customer'),
            '_user_' => array(self::BELONGS_TO, 'User', 'user'),
            '_items_' => array(self::HAS_MANY, 'OrderItem', 'order'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'customer' => 'Customer',
            'customer_name' => 'Customer Name',
            'fullytrans' => 'Fullytrans',
            'insdate' => 'Insdate',
            'comments'=>'comments',
            'noorder'=>'noorder',
            'status' => 'Status',
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
        $criteria->compare('customer', $this->customer);
        $criteria->compare('insdate', $this->insdate);
        $criteria->compare('comments', $this->comments);
        $criteria->compare('norder', $this->norder);
        $criteria->compare('fullytrans', $this->fullytrans);
        $criteria->compare('customer_name', $this->customer_name);
        $criteria->compare('status', $this->status);
        $criteria->compare('actioneer', $this->actioneer);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('modified', $this->modified, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
