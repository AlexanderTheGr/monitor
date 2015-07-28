<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    private $_id;
    private $email;

    public function authenticate() {
        $accesslog = new Accesslog;
        $accesslog->load();
        $user = User::model()->find('LOWER(email)=?', array(strtolower($this->username)));
        
        
        
        if ($user === null) {
            $this->errorCode = USERNAME_INVALID;
            $accesslog->ip = $_SERVER["REMOTE_ADDR"];
            $accesslog->actiontype = 'loginfailed';
            $accesslog->notes = "Invalid Username (user:" . $this->username . ", password:" . $this->password.")";
            $accesslog->save();
           
        } else if (!$user->validatePassword($this->password)) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
            $accesslog->user_id = $user->id;
            $accesslog->ip = $_SERVER["REMOTE_ADDR"];
            $accesslog->actiontype = 'loginfailed';
            $accesslog->notes = "Invalid Password (user:" . $this->username . ", password:" . $this->password.")";
            $accesslog->save();
        } else {

            $this->_id = $user->id;
            $this->email = $user->email;
            $auth = Yii::app()->authManager;
            
            $accesslog->user_id = $user->id;
            $accesslog->ip = $_SERVER["REMOTE_ADDR"];
            $accesslog->actiontype = 'login';
            $accesslog->notes = "Success";
            //$accesslog->save();
            
            
            /*
            if (!$auth->isAssigned($user->role, $this->_id)) {
                if ($auth->assign($user->role, $this->_id)) {
                    Yii::app()->authManager->save();
                }
            }
             * 
             */
            
            //echo self::ERROR_NONE;
            $this->errorCode = self::ERROR_NONE;
        }
        return $this->errorCode == self::ERROR_NONE;
    }

    public function getId() {
        return $this->_id;
    }

}