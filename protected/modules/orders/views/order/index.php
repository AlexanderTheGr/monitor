<button style="float:left" class="addnew btn-primary btn">Νέα Παραγγελία</button>
<BR><BR>
<?php require Yii::app()->params['widget']."datatable.php";?>


<script>
$(".addnew").click(function(){
    location.href = "<?php echo Yii::app()->request->baseUrl?>/orders/order/edit"    
})

</script>
