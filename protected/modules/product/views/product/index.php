
<button class="btn updatesoftone btn-success">Update Softone</button>

<?php require Yii::app()->params['widget'] . "datatable.php"; ?>

<script>
    $(".updatesoftone").click(function () {
        var data = {}
        data.id = '<?php echo $model->id; ?>'
        ProgressBar.displayProgressBar();
        $.post("<?php echo Yii::app()->request->baseUrl ?>/product/product/retrievesoftonedata", data, function () {
            ProgressBar.hideProgressBar();
        })
    })
</script>    