<button style="float:right" class="addnew btn-primary btn">Νέος Πελάτης</button>
<?php require Yii::app()->params['widget']."datatable.php";?>


<script>
$(".addnew").click(function(){

    location.href = "<?php echo Yii::app()->request->baseUrl?>/customers/customer/edit/"    
})
</script>