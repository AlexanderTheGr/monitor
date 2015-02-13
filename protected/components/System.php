<?php
class System {
    static function settings($key) {
        $sql = "Select * from settings where `key` = '".$key."'";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        if ($data) {
            $model = Settings::model()->findByPk($data["id"]);
            $multidata = explode(",",$model->multidata);
            return (count($multidata) > 0 AND $model->multidata != "") ? $multidata[(int)$model->value] : $model->value;
        }       
    }
    static function usersettings($key) {
        $sql = "Select * from usersettings where `key` = '".$key."' AND user_id = '".Yii::app()->user->id."'";
        
        
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        if ($data) { 
            $model = Usersettings::model()->findByPk($data["id"]);
            $model->load();
            
            
            $multidata = explode(",",$model->multidata);
            
            
            return (count($multidata) > 0 AND $model->multidata != "") ? $multidata[(int)$model->value] : $model->value;
        }       
    }    
}
?>