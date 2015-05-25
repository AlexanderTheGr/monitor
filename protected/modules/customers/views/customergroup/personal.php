<div style="text-align: right">
<?php if ($this->showSave):?>    
<button class="save_model_<?php echo $model->className()?>"><?php echo $this->ls->translate(SAVE)?></button>
<?php endif;?>
<?php require Yii::app()->params['widget'] . "ajaxform.php"; ?>