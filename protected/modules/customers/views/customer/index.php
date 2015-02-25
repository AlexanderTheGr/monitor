<button style="float:right" class="addnew btn-primary btn">Νέος Πελάτης</button>
<button class="btn updatesoftone btn-success">Update Softone</button>
<?php require Yii::app()->params['widget']."datatable.php";?>


<script>
$(".addnew").click(function(){

    location.href = "<?php echo Yii::app()->request->baseUrl?>/customers/customer/edit/"    
})
    $(".updatesoftone").click(function () {
        var data = {}
        data.id = '<?php echo $model->id; ?>'
        ProgressBar.displayProgressBar();
        $.post("<?php echo Yii::app()->request->baseUrl ?>/customers/customer/retrievesoftonedata", data, function () {
            ProgressBar.hideProgressBar();
        })
    })
</script>