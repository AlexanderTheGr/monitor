<?php

/**
 * This is the model class for table "product".
 *
 * The followings are the available columns in table 'product':
 * @property integer $id
 * @property string $erp_code
 * @property string $tecdoc_code
 * @property integer $tecdoc_supplier_id
 * @property string $supplier_code
 * @property string $erp_supplier
 * @property string $title
 * @property string $tecdoc_article_name
 * @property integer $tecdoc_generic_article_id
 * @property integer $updated
 * @property string $ts
 * @property integer $actioneer
 * @property string $created
 * @property string $modified
 *
 * The followings are the available model relations:
 * @property TecdocSupplier $tecdocSupplier
 */
class Product extends Eav {

    function Product() {
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
        return 'product';
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
            array('reference, catalogue, erp_code,item_mtrplace, tecdoc_code, supplier_code, erp_supplier, title, disc1prc, tecdoc_article_name, tecdoc_generic_article_id, item_cccfxrelbrand, item_cccfxreltdcode, item_vat, item_cccfxcode1, item_mtrmanfctr, item_pricer, item_pricew, item_pricer02, item_pricer01, item_pricew02, item_pricew01, item_mtrunit1, item_name1, item_name, item_code, item_mtrl_itemtrdata_qty1, ts, actioneer, created, modified, flat_data, search, gnisia', 'required'),
            array('reference, catalogue, tecdoc_supplier_id, tecdoc_generic_article_id, item_cccfxrelbrand, item_vat, item_mtrunit1, item_mtrl_itemtrdata_qty1, updated, actioneer', 'numerical', 'integerOnly' => true),
            array('erp_code, tecdoc_code, supplier_code, erp_supplier,item_mtrplace, tecdoc_article_name, item_cccfxreltdcode, item_cccfxcode1, item_mtrmanfctr, item_name1, item_name, item_code', 'length', 'max' => 255),
            array('disc1prc, item_pricer, item_pricew,item_pricer02, item_pricer01, item_pricew02, item_pricew01', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, reference, catalogue, erp_code,item_mtrplace, tecdoc_code, tecdoc_supplier_id, supplier_code, erp_supplier, title, disc1prc, tecdoc_article_name, tecdoc_generic_article_id, item_cccfxrelbrand, item_cccfxreltdcode, item_vat, item_cccfxcode1, item_mtrmanfctr, item_pricer, item_pricew, item_pricer02, item_pricer01, item_pricew02, item_pricew01, item_mtrunit1, item_name1, item_name, item_code, item_mtrl_itemtrdata_qty1, updated, ts, actioneer, created, modified, flat_data, search,gnisia', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            '_catalogue_' => array(self::BELONGS_TO, 'ProductCatalogue', 'catalogue'),
            '_tecdocSupplier_' => array(self::BELONGS_TO, 'TecdocSupplier', 'tecdoc_supplier_id'),
            '_productLangs_' => array(self::HAS_MANY, 'ProductLang', 'product', 'index' => 'language'),
            '_webserviceProducts_' => array(self::HAS_MANY, 'WebserviceProduct', 'product', 'index' => 'webservice'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'reference' => 'Reference',
            'catalogue' => 'Catalogue',
            'erp_code' => 'Erp Code',
            'tecdoc_code' => 'Tecdoc Code',
            'tecdoc_supplier_id' => 'Tecdoc Supplier',
            'supplier_code' => 'Supplier Code',
            'erp_supplier' => 'Erp Supplier',
            'title' => 'Title',
            'disc1prc' => 'Disc1prc',
            'tecdoc_article_name' => 'Tecdoc Article Name',
            'tecdoc_generic_article_id' => 'Tecdoc Generic Article',
            'item_cccfxrelbrand' => 'Item Cccfxrelbrand',
            'item_cccfxreltdcode' => 'Item Cccfxreltdcode',
            'item_vat' => 'Item Vat',
            'item_cccfxcode1' => 'Item Cccfxcode1',
            'item_mtrmanfctr' => 'Item Mtrmanfctr',
            'item_pricer' => 'Item Pricer',
            'item_pricew' => 'Item Pricew',
            'item_pricer01' => 'Item Pricer',
            'item_pricew01' => 'Item Pricew',
            'item_pricer02' => 'Item Pricer',
            'item_pricew02' => 'Item Pricew',
            'item_mtrunit1' => 'Item Mtrunit1',
            'item_name1' => 'Item Name1',
            'item_name' => 'Item Name',
            'item_code' => 'Item Code',
            'item_mtrplace' => 'item_mtrplace',
            'item_mtrl_itemtrdata_qty1' => 'Item Mtrl Itemtrdata Qty1',
            'updated' => 'Updated',
            'ts' => 'Ts',
            'actioneer' => 'Actioneer',
            'created' => 'Created',
            'modified' => 'Modified',
            'flat_data' => 'Flat Data',
            'search' => 'Search',
            'gnisia' => 'Gnisia',
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
        $criteria->compare('reference', $this->reference);
        $criteria->compare('catalogue', $this->catalogue);
        $criteria->compare('erp_code', $this->erp_code, true);
        $criteria->compare('tecdoc_code', $this->tecdoc_code, true);
        $criteria->compare('tecdoc_supplier_id', $this->tecdoc_supplier_id);
        $criteria->compare('supplier_code', $this->supplier_code, true);
        $criteria->compare('erp_supplier', $this->erp_supplier, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('disc1prc', $this->disc1prc, true);
        $criteria->compare('tecdoc_article_name', $this->tecdoc_article_name, true);
        $criteria->compare('tecdoc_generic_article_id', $this->tecdoc_generic_article_id);
        $criteria->compare('item_cccfxrelbrand', $this->item_cccfxrelbrand);
        $criteria->compare('item_cccfxreltdcode', $this->item_cccfxreltdcode, true);
        $criteria->compare('item_vat', $this->item_vat);
        $criteria->compare('item_cccfxcode1', $this->item_cccfxcode1, true);
        $criteria->compare('item_mtrmanfctr', $this->item_mtrmanfctr, true);
        $criteria->compare('item_pricer', $this->item_pricer, true);
        $criteria->compare('item_pricew', $this->item_pricew, true);

        $criteria->compare('item_pricer01', $this->item_pricer01, true);
        $criteria->compare('item_pricew01', $this->item_pricew01, true);
        $criteria->compare('item_pricer02', $this->item_pricer02, true);
        $criteria->compare('item_pricew02', $this->item_pricew02, true);

        $criteria->compare('item_mtrunit1', $this->item_mtrunit1);
        $criteria->compare('item_name1', $this->item_name1, true);
        $criteria->compare('item_name', $this->item_name, true);
        $criteria->compare('item_code', $this->item_code, true);
        $criteria->compare('item_mtrplace', $this->item_mtrplace, true);

        $criteria->compare('item_mtrl_itemtrdata_qty1', $this->item_mtrl_itemtrdata_qty1);
        $criteria->compare('updated', $this->updated);
        $criteria->compare('ts', $this->ts, true);
        $criteria->compare('actioneer', $this->actioneer);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('modified', $this->modified, true);
        $criteria->compare('flat_data', $this->flat_data, true);
        $criteria->compare('search', $this->search, true);
        $criteria->compare('gnisia', $this->gnisia, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function media() {

        $product = json_decode($this->flat_data);
        if ($product->_webserviceProducts_->article_id == "")
            return;

        if ($this->media != "")
            return $this->media;

        $url = "http://service.fastwebltd.com/";
        $fields = array(
            'action' => 'media',
            'tecdoc_article_id' => $product->_webserviceProducts_->article_id
        );

        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        rtrim($fields_string, '&');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $data = curl_exec($ch);
        $this->media = $data;
        $this->save();
        return $this->media;
    }

    function updateSynafies() {
        $search = str_replace("\n", "|", $this->search);
        $searchArr = explode("|", $search);
        foreach ($searchArr as $srch) {
            $sql = "Select id from product_search where search LIKE '%" . $srch . "%' OR item_code = '" . $srch . "'  limit 0,30";
            $srches = Yii::app()->db->createCommand($sql)->queryAll();
            $searchArr2[] = $srch;
            foreach ($srches as $srchs) {
                if ($srchs["id"] != $this->id) {
                    $submodel = Product::model()->findByPk($srchs["id"]);
                    $submodel->load();
                    $subsearch = str_replace("\n", "|", $submodel->search);
                    $subsearchArr = explode("|", $subsearch);
                    foreach ($searchArr as $srch2) {
                        if ($srch2 != $submodel->item_code)
                            $subsearchArr[] = $srch2;
                    }
                    foreach ($subsearchArr as $srch3) {
                        if ($srch3 != $this->item_code) {
                            $searchArr2[] = $srch3;
                        }
                    }
                    $subsearchArr[] = $this->item_code;
                    $subsearchArr = array_filter(array_unique($subsearchArr));
                    $subsearchArr = array_diff($subsearchArr, array($submodel->item_code));
                    $submodel->search = implode("|", (array) $subsearchArr);
                    $submodel->save();
                    $submodel->setProductSearch();
                }
            }
        }       
        $searchArr = array_filter(array_unique($searchArr2));
        $searchArr = array_diff($searchArr, array($this->item_code));
        $this->search = implode("|", $searchArr);
        $this->save();
        $this->setProductSearch();
    }

    function setProductSearch() {
        $sql = "replace product_search set id = '" . $this->id . "', item_code = '" . $this->item_code . "', search = '" . $this->search . "', gnisia = '" . $this->gnisia . "'";
        Yii::app()->db->createCommand($sql)->execute();
    }

    function setFlat() {
        $flat = $this->attributes;

        $flat["attributeitems"] = $this->attributeitems;
        unset($flat["flat_data"]);

        $sql = "replace product_search set id = '" . $this->id . "', item_code = '" . $this->item_code . "', search = '" . $this->search . "', gnisia = '" . $this->gnisia . "'";
        Yii::app()->db->createCommand($sql)->execute();

        $flat["media"] = $this->media();
        $this->media = $this->media();
        $flat["_tecdocSupplier_"]["supplier"] = $this->_tecdocSupplier_->supplier;
        $flat["_webserviceProducts_"]["article_id"] = $this->_webserviceProducts_[11632]->article_id;
        $flat["_webserviceProducts_"]["article_name"] = $this->_webserviceProducts_[11632]->article_name;

        $json = json_encode($flat);
        $this->flat_data = $json;

        //$this->search = $this->_tecdocSupplier_->supplier."|".$this->attributeitems[""]."|";


        $this->save();
    }

}
