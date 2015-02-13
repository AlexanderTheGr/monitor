<?php $datas = $this->getModelArray("Usergroup", "", array()); ?>
<?php
$access = unserialize($model->access);
?>
<table id="accessform" border="1">
    <thead>
        <tr>
            <th><?php echo $this->translate(GROUP) ?></th>
            <th><?php echo $this->translate(BLOCK) ?></th>
            <th><?php echo $this->translate(VIEW) ?></th>
            <th><?php echo $this->translate(ADMIN) ?></th>
        </tr>          
    </thead>


    <?php foreach ($datas as $data): ?>
        <tr>
            <td><?php echo $data["group"] ?></td>
            <td><input type="radio" <?php echo $access[$data["group"]] == "block" ? "checked" : "" ?> value="block" class="accessradio" name="<?php echo $data["group"] ?>"></td>
            <td><input type="radio" <?php echo $access[$data["group"]] == "view" ? "checked" : "" ?> value="view" class="accessradio" name="<?php echo $data["group"] ?>"></td>
            <td><input type="radio" <?php echo $access[$data["group"]] == "admin" ? "checked" : "" ?> value="admin" class="accessradio" name="<?php echo $data["group"] ?>"></td>
        </tr>
    <?php endforeach; ?>
</table>
<script>
    $(document).ready(function() {
        $('#accessform').dataTable({bFilter:false,bPaginate:false,bInfo:false});
    })
    
    
    $(".accessradio").click(function(){
        var arr = [];
        if ($(this).attr("checked")) {
            $(this).attr("name")    
            data.access = $(this).val();
            data.group = $(this).attr("name");
            data.id = '<?php echo $model->id; ?>';
            $.post('<?php echo Yii::app()->params['mainurl'] . "settings/accessgroup/accessformsave/" ?>',data,function(result){
                $dialog.accessitem.dialog( "close" );
            })                                 
        }  

    })
         
</script>