<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $telephone
 * @property string $username
 * @property string $password
 * @property string $role
 */
class User extends Eav {

    function User() {
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
        return 'user';
    }

    public function className() {
        return __CLASS__;
    }

    public function validatePassword($password) {
        return $this->hashPassword($password) === $this->password;
    }

    public function hashPassword($password) {
        return $password;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('email, key, username,role', 'length', 'max' => 45),
            //array('password', 'length', 'max' => 80),
            //array('role', 'length', 'max' => 10),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id,email,username, key, password, role', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations($array = array()) {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            '_quizes_' => array(self::HAS_MANY, 'MdlQuiz', 'userid'),
            '_jobs_' => array(self::MANY_MANY, 'Job',
                'job2user(user, job)'),
            '_profinciecies_' => array(self::MANY_MANY, 'Profinciecylvl',
                'profinciecylvl2user(user, profinciecylvl)'),
        );
    }

    public function getJobIds() {
        $out = array();
        foreach ($this->_jobs_ as $job) {
            $out[] = $job->id;
        }
        return $out;
    }

    public function clearjobs() {
        $sql = "delete from  job2user where user = '" . $this->id . "'";
        Yii::app()->db->createCommand($sql)->execute();
    }

    public function removejob($id) {
        $sql = "delete from  job2user where job = '" . $id . "'";
        Yii::app()->db->createCommand($sql)->execute();
    }

    public function addjob($id) {
        $sql = "insert job2user set id = '" . $this->getSysId() . "', user = '" . $this->id . "', job= '" . $id . "'";
        Yii::app()->db->createCommand($sql)->execute();
    }

    public function addjobs($array = array()) {
        $this->clearjobs();
        foreach ((array) $array as $id) {
            $this->addjob($id);
        }
    }

    public function getProfinciecyIds() {
        $out = array();
        foreach ($this->_profinciecies_ as $profinciecy) {
            $out[] = $profinciecy->id;
        }
        return $out;
    }

    public function getProfinciecyNames() {
        $out = array();
        foreach ($this->_profinciecies_ as $profinciecy) {
            $out[] = $profinciecy->name;
        }
        return $out;
    }

    public function clearprofinciecies() {
        $sql = "delete from profinciecylvl2user where user = '" . $this->id . "'";
        Yii::app()->db->createCommand($sql)->execute();
    }

    public function removeprofinciecy($id) {
        $sql = "delete from profinciecylvl2user where profinciecylvl = '" . $id . "'";
        Yii::app()->db->createCommand($sql)->execute();
    }

    public function addprofinciecy($id) {
        $sql = "insert profinciecylvl2user set id = '" . $this->getSysId() . "', user = '" . $this->id . "', profinciecylvl= '" . $id . "'";
        Yii::app()->db->createCommand($sql)->execute();
    }

    public function addprofinciecies($array = array()) {
        $this->clearprofinciecies();
        foreach ((array) $array as $id) {
            $this->addprofinciecy($id);
        }
    }

    function getLastLogin() {
        $sql = "Select max(ts) as ts from accesslog where user_id = '" . $this->id . "' ";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        return $data["ts"];
    }

    function getFullname() {
        return $this->getFirstName() . " " . $this->getLastName();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'email' => 'Email',
            'key' => "Key",
            'username' => 'Username',
            'password' => 'Password',
            'profinciecies' => "Profinciecies",
            'jobs' => "Jobs",
            'role' => 'Role',
        );
    }

    function getSpecialitiesIds() {
        $sql = "Select * from doctor2doctorspecialities where user_id = '" . $this->id . "'";
        $datas = Yii::app()->db->createCommand($sql)->queryAll();
        $out = array();
        foreach ((array) $datas as $data) {
            $out[] = $data["doctorspeciality_id"];
        }
        return $out;
    }

    function getSpecialities() {
        $sql = "Select * from doctor2doctorspecialities where user_id = '" . $this->id . "'";
        $datas = Yii::app()->db->createCommand($sql)->queryAll();
        $out = array();
        foreach ((array) $datas as $data) {
            $model = Doctorspeciality::model()->findByPk($data["doctorspeciality_id"]);
            $model->load();
            $out[] = $model->speciality;
        }
        return $out;
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
        $criteria->compare('email', $this->email, true);
        $criteria->compare('key', $this->key, true);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('role', $this->role, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}