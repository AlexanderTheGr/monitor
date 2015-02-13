<?php

class SiteController extends Controller {

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        if (Yii::app()->user->name == '' OR Yii::app()->user->name == 'Guest') {
            $this->redirect(array('login'));
        }
        $this->enumArray = array();
        $this->render('index');
    }

    public function enumArray() {
        
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $headers = "From: {$model->email}\r\nReply-To: {$model->email}";
                mail(Yii::app()->params['adminEmail'], $model->subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    public function actionSendRecoverEmail() {

        $model = new SendRecoverEmailForm;
        if (isset($_POST['SendRecoverEmailForm'])) {
            $model->attributes = $_POST['SendRecoverEmailForm'];
            if ($model->validate() AND $model->sendemail()) {
                $msg = $this->translate("An Email send to " . $model->email . ". Check your email");
            }
        }

        $this->render('sendrecoveremail', array("msg" => $msg, 'model' => $model));
    }

    public function actionRecoverPassword($id = false) {
        $model = new RecoverForm;
        if ($id) {
            $model->key = $id;
            if (!$model->getUser()) {
                return false;
            }
        }
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        if (isset($_POST['RecoverForm'])) {
            $model->attributes = $_POST['RecoverForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() AND $model->recover()) {
                $msg = $this->translate("Password has been Changed");
            }
        }
        $this->render('recover', array("msg" => $msg, 'model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        $model = new LoginForm;
        $this->layout='//layouts/login';
        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            //echo "SSS";
            if ($model->login()) {
                $this->redirect(Yii::app()->user->returnUrl);
                
            }
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        $accesslog = new Accesslog;
        $accesslog->load();
        $accesslog->user_id = Yii::app()->user->id;
        $accesslog->ip = $_SERVER["REMOTE_ADDR"];
        $accesslog->actiontype = 'logout';
        $accesslog->notes = "Success";
        $accesslog->save();
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
        ;
    }

}