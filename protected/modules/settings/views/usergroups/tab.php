<?php require Yii::app()->params['widget'] . "datatable.php"; ?>
<script>
    $(document).ready(function(){
        $(".usergroup").live('click',function(){
            var arr= $(this).attr("id");
            arr = arr.split("_")
            location.href='<?php echo Yii::app()->params['mainurl'] ?>settings/usergroups/'+arr[1];
        })        
    })
</script>