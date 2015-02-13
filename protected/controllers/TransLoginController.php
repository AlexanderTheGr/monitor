<?php
class TransLoginController extends Controller {
    public function actionIndex() {
        session_start();
        require_once Yii::app()->params['root'].'/langtranslater/editing/langtranslaterlogin.php';
    }
    public function enumArray() {
        
    }
}
?>
