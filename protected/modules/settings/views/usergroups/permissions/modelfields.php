<table cellpadding="0" cellspacing="0" border="0" class="display" id="modeleavfields" width="100%">
    <thead>
        <tr>
            <th><?php echo $this->translate(MODEL) ?></th>
            <th><?php echo $this->translate(FIELD) ?></th>
            <th><?php echo $this->translate(BLOCK) ?><input class="modelfields_checkbox" value="block" type="checkbox"></th>
            <th><?php echo $this->translate(VIEW) ?><input class="modelfields_checkbox" value="view" type="checkbox"></th>
            <th><?php echo $this->translate(ADMIN) ?><input class="modelfields_checkbox" value="admin" type="checkbox"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($datas as $data): ?>

            <?php 
            $access = unserialize($data["access"]);
            ?>
            <tr>
                <td><?php echo $data["model"] ?></td>
                 <td><?php echo $data["field"] ?></td>
                <td><input class="modelfields_radio" ref="<?php echo $data["id"] ?>" type="radio" <?php echo $access[$model->group] == "block" ? "checked" : "" ?> value="block" class="accessradio" name="<?php echo $data["model"] ?>_<?php echo $data["field"] ?>"></td>
                <td><input class="modelfields_radio" ref="<?php echo $data["id"] ?>" type="radio" <?php echo $access[$model->group] == "view" ? "checked" : "" ?> value="view" class="accessradio" name="<?php echo $data["model"] ?>_<?php echo $data["field"] ?>"></td>
                <td><input class="modelfields_radio" ref="<?php echo $data["id"] ?>" type="radio" <?php echo $access[$model->group] == "admin" ? "checked" : "" ?> value="admin" class="accessradio" name="<?php echo $data["model"] ?>_<?php echo $data["field"] ?>"></td>
            </tr>
<?php endforeach; ?>
    </tbody>
</table>

<BR><BR>
<script>
    $(document).ready(function() {
        $('#modeleavfields').dataTable();
        $(".modelfields_checkbox").live("click",function(){
            var obj = $(this);
            $(".modelfields_radio").each(function(){ 
                if ($(this).val() == obj.val()) {
                    $(this).attr("checked",true);
                } 
            })
            data.group = "<?php echo $model->group ?>";
            data.access = $(this).val();
            data.type = "Accessmodelfield";
            ProgressBar.displayProgressBar();
            
            $.post("<?php echo Yii::app()->params['mainurl'] ?>settings/usergroups/ajaxaccesssave",data,function(result){
                ProgressBar.hideProgressBar();
                loadUi();
                location.reload();
            }) 
        })        
        $(".modelfields_radio").live("click",function(){
            var data = {}
            data.id = $(this).attr("ref");
            data.group = "<?php echo $model->group?>";
            data.access = $(this).val();
            data.type = "Accessmodelfield";
            ProgressBar.displayProgressBar();
            $.post("<?php echo Yii::app()->params['mainurl'] ?>settings/usergroups/ajaxaccesssave",data,function(result){
                ProgressBar.hideProgressBar();
                
                loadUi();
            }) 
        })   
    });

</script>