<div style="text-align: right">
<?php if ($this->showSave):?>    
<button class="btn btn-success save_model_<?php echo $model->className()?>">Αποθήκευση</button>
<?php endif;?>
<?php if ($this->showDelete):?> 
<button class="btn btn-danger delete_model_<?php echo $model->className()?>">Διαγραφή</button>
<?php endif;?>
<button class="btn return_to_main_<?php echo $model->className()?>">Επιστροφή</button>
</div>

<?php //echo HtmlWidget::tabber($tabs); ?>

<?php require Yii::app()->params['widget'] . "ajaxform.php"; ?>
<?php //echo  HtmlWidget::tabber($tabs);?>