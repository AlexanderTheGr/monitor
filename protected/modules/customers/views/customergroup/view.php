<div style="text-align: right">
<?php if ($this->showSave):?>    
<button class="save_model_<?php echo $model->className()?>">Αποθήκευση</button>
<?php endif;?>
<?php if ($this->showDelete):?> 
<button class="delete_model_<?php echo $model->className()?>">Διαγραφή</button>
<?php endif;?>
<button class="return_to_main_<?php echo $model->className()?>">Επιστροφή</button>
</div>

<?php echo  HtmlWidget::tabber($tabs);?>