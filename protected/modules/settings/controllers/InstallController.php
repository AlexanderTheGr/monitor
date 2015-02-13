<?php

class InstallController extends Controller {

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /*
      public function accessRules() {
      return array(
      array('allow', // allow admin user to perform 'admin' and 'delete' actions
      'users' => array('@'),
      ),
      array('deny', // deny all users
      'users' => array('*'),
      ),
      );
      }
     */

    public function actionIndex() {


        $json = json_decode($_POST["data"]);
        

        $str = print_r($_POST, true);
        $handle = fopen("/home/platform/test.txt", "w++");
        fwrite($handle, $str . "sssss");
        fclose($handle);

        //{"id":"81324001363017289","name":"ClinicView","service_server":"66219001363016804","port":"80","subpath":"","subdomain":"platform","db_prefix":"platform_","db_name":"platform_db","db_user":"platform_user","db_pass":"cf-tCSpC4dGC","account_user":"plclinic","account_pass":"qKG^a7c8(8Q.","install_cb":"http:\/\/platform.clinicview.gr\/index.php\/settings\/install","update_cb":"index.php\/settings\/update","uninstall_cb":"index.php\/settings\/unistall","status":"1","ts":"2013-03-11 15:54:49","service_domain":"clinicview.gr","service_name":"ClinicView","server_id":"189310001360672563","server_domain":"216.38.53.160","server_name":"ugaga","client_name":"Test5","client_domain":"test5","client_email":"a.dimeas@gmail.com","client_status":"4","client_app_username":"admin","client_app_password":"123456","client_key":"BAB5B2F84B147FCA424B891B79210C74","client_plans":[],"main_domain":"test5.clinicview.gr","new_domain":"http:\/\/test5.clinicview.gr:80\/","new_database":"platform_test5","client_id":"100549001363257371"}
        $data = json_decode($_POST["data"]);

        $data->client_app_username;
        $data->client_app_password;
        $name = explode(" ", $data->client_name);
        $data->client_email;

        $sql = "Update platform_".$data->client_domain.".user set email = '".$data->client_app_username."', password = '".md5($data->client_app_password)."' where id = 4"; 
        Yii::app()->db->createCommand($sql)->execute();
        
        error_log($sql);
        
        $sql = "Update platform_".$data->client_domain.".eav set value = '".$data->client_email."' where entity_id = 4 and attribute_item = 20";
        Yii::app()->db->createCommand($sql)->execute();
        
        error_log($sql);
        
        $sql = "Update platform_".$data->client_domain.".eav set value = '".$name[0]."' where entity_id = 4 and attribute_item = 1";
        Yii::app()->db->createCommand($sql)->execute();
        
        error_log($sql);
        
        $sql = "Update platform_".$data->client_domain.".eav set value = '".$name[1]."' where entity_id = 4 and attribute_item = 2";
        Yii::app()->db->createCommand($sql)->execute();
        
        error_log($sql);
        
    }

    public function actionCreateaccount() {
        return;
        if (Yii::app()->params["subdomain"] != Yii::app()->params['dumpdb']["subdomain"])
            return;

        $subdomain = "test";

        $this->createsubdomain($subdomain);
        $this->dumpdb();
        $this->createdb($subdomain);
        $this->createStructure($subdomain);
    }

    public function createStructure($subdomain) {
        @mkdir(Yii::app()->params['root'] . "clientdata/" . $subdomain . "/");
        @mkdir(Yii::app()->params['root'] . "clientdata/" . $subdomain . "/files/");


        echo "Structure has been created<BR>";
    }

    public function createsubdomain($subdomain) {
        return;
        if (Yii::app()->params["subdomain"] != Yii::app()->params['dumpdb']["subdomain"])
            return;

        $xmlapi = new xmlapi('216.38.53.160');
        $xmlapi->set_port(2082);
        $xmlapi->password_auth("plclinic", "qKG^a7c8(8Q.");
        $xmlapi->park("plclinic", "$subdomain.clinicview.gr", '');


        echo "Domain " . $subdomain . " has been created<BR>";
    }

    public function createdb($subdomain) {
        return;
        if (Yii::app()->params["subdomain"] != Yii::app()->params['dumpdb']["subdomain"])
            return;


        $dbname = 'platform_' . $subdomain;

        $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
        Yii::app()->db->createCommand($sql)->execute();

        $dbhost = Yii::app()->params['dumpdb']["dbhost"];
        $dbuser = Yii::app()->params['dumpdb']["dbuser"];
        $dbpwd = Yii::app()->params['dumpdb']["dbpwd"];
        $dumpfile = Yii::app()->params['dumpdb']["dumpfile"];
        $scriptpath = Yii::app()->params['dumpdb']["scriptpathmysql"];

        //echo "$scriptpath -u $dbuser -p$dbpwd $dbname < $dumpfile";

        passthru("$scriptpath -u $dbuser -p$dbpwd $dbname < $dumpfile");

        echo "Database has been created<BR>";
    }

    function dumpdb() {
        return;
        if (Yii::app()->params["subdomain"] != Yii::app()->params['dumpdb']["subdomain"])
            return;

        $dbhost = Yii::app()->params['dumpdb']["dbhost"];
        $dbuser = Yii::app()->params['dumpdb']["dbuser"];
        $dbpwd = Yii::app()->params['dumpdb']["dbpwd"];
        $dbname = Yii::app()->params['dumpdb']["dbname"];
        $dumpfile = Yii::app()->params['dumpdb']["dumpfile"];
        $scriptpath = Yii::app()->params['dumpdb']["scriptpathmysqldump"];

        passthru("$scriptpath --opt --host=$dbhost --user=$dbuser --password=$dbpwd $dbname > $dumpfile");

        error_log("$scriptpath --opt --host=$dbhost --user=$dbuser --password=$dbpwd $dbname > $dumpfile");
        
        echo "Database has been dumped<BR>";
    }

}

?>
