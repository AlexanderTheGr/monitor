

<div style="text-align: right">
    <?php if ($model->fullytrans == 0): ?>
        <?php if ($this->showSave): ?>    
            <button class="btn btn-success savesoftone">Αποστολή Για Τιμολόγιση</button>
            <button class="saveorder btn btn-success save_model_<?php echo $model->className() ?>">Αποθήκευση</button>
        <?php endif; ?>
        <?php if ($this->showDelete): ?> 
            <button class="btn btn-danger delete_model_<?php echo $model->className() ?>">Διαγραφή</button>
        <?php endif; ?>
    <?php else: ?>
        <button style="float:left" class="btn btn-success">Μετασχηματισμένη</button>
    <?php endif; ?>   
    <?php if ($model->reference > 0): ?>
        <button style="float:left" class="btn btn-primary">Απεσταλμένη</button>
    <?php endif; ?>
    <button class="btn return_to_main_<?php echo $model->className() ?>">Επιστροφή</button>
    <button ref="<?php echo $model->id; ?>" class="btn printarea">Εκτύπωση</button>
</div>

<?php echo HtmlWidget::tabber($tabs); ?>
<script>
    $(".printarea").click(function(){
        location.href = '<?php echo Yii::app()->request->baseUrl ?>/orders/order/print/<?php echo $model->id?>';        
    })
    $(document).ready(function () {
        $(".calculate").click(function () {
            var data = {}
            data.id = '<?php echo $model->id; ?>'
            ProgressBar.displayProgressBar();
            $.post("<?php echo Yii::app()->request->baseUrl ?>/orders/order/calculateorder", data, function () {
                ProgressBar.hideProgressBar();
                callback.orderitem();
            })
        })
<?php if ($model->id > 0): ?>
            $("#ui-id-2").click();
<?php endif; ?>

        $(".savesoftone").click(function () {
            var data = {}
            data.id = '<?php echo $model->id; ?>'
            ProgressBar.displayProgressBar();
            $.post("<?php echo Yii::app()->request->baseUrl ?>/orders/order/savesoftone", data, function () {
                callback.orderitem();
               
                ProgressBar.hideProgressBar();
            })
        })
        $(".calculateOrder").click(function () {
            var data = {}
            data.id = '<?php echo $model->id; ?>'
            ProgressBar.displayProgressBar();
            $.post("<?php echo Yii::app()->request->baseUrl ?>/orders/order/calculateOrder", data, function () {
                callback.orderitem();
                ProgressBar.hideProgressBar();
            })
        })

    })

</script>