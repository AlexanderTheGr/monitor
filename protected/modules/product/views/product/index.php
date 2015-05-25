
<button class="btn updatesoftone btn-success">Update Softone</button>

<button class="btn uploadcsv btn-success">Upload CSV</button>


<?php require Yii::app()->params['widget'] . "datatable.php"; ?>

<script>
    $(".uploadcsv").click(function () {
		location.href='<?php echo Yii::app()->request->baseUrl ?>/product/product/upload';
	})
	$(".updatesoftone").click(function () {
        var data = {}
        data.id = '<?php echo $model->id; ?>'
        ProgressBar.displayProgressBar();
        $.post("<?php echo Yii::app()->request->baseUrl ?>/product/product/retrievesoftonedata", data, function () {
            ProgressBar.hideProgressBar();
        })
    })
</script>    