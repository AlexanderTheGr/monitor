<table cellpadding="0" cellspacing="0" border="0" class="display" id="modeleav" width="100%">
    <thead>
        <tr>
            <th><?php echo $this->translate(EAVMODEL) ?></th>
            <th><?php echo $this->translate(TITLE) ?></th>
            <th><?php echo $this->translate(BLOCK) ?><input class="modeleavcheckbox" value="block" type="checkbox"></th>
            <th><?php echo $this->translate(VIEW) ?><input class="modeleavcheckbox" value="view" type="checkbox"></th>
            <th><?php echo $this->translate(ADMIN) ?><input class="modeleavcheckbox" value="admin" type="checkbox"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($datas as $data): ?>


            <?php 
            $access = unserialize($data["access"]);
            ?>
            <tr>
                <td><?php echo $data["eav_model"] ?></td>
                <td><?php echo $data["title"] ?></td>
                <td><input class="modeleavradio" ref="<?php echo $data["id"] ?>" type="radio" <?php echo $access[$model->group] == "block" ? "checked" : "" ?> value="block" class="accessradio" name="<?php echo $data["eav_model"] ?>_<?php echo $data["attribute_id"] ?>"></td>
                <td><input class="modeleavradio" ref="<?php echo $data["id"] ?>" type="radio" <?php echo $access[$model->group] == "view" ? "checked" : "" ?> value="view" class="accessradio" name="<?php echo $data["eav_model"] ?>_<?php echo $data["attribute_id"] ?>"></td>
                <td><input class="modeleavradio" ref="<?php echo $data["id"] ?>" type="radio" <?php echo $access[$model->group] == "admin" ? "checked" : "" ?> value="admin" class="accessradio" name="<?php echo $data["eav_model"] ?>_<?php echo $data["attribute_id"] ?>"></td>
            </tr>
<?php endforeach; ?>
    </tbody>
</table>

<BR><BR>
<script>
    $(document).ready(function() {
        $('#modeleav').dataTable();
        $(".modeleavcheckbox").live("click",function(){
            var obj = $(this);
            $(".modeleavradio").each(function(){ 
                if ($(this).val() == obj.val()) {
                    $(this).attr("checked",true);
                } 
            })
            data.group = "<?php echo $model->group ?>";
            data.access = $(this).val();
            data.type = "AttributeItems";
            ProgressBar.displayProgressBar();
            $.post("<?php echo Yii::app()->params['mainurl'] ?>settings/usergroups/ajaxaccesssave",data,function(result){
                ProgressBar.hideProgressBar();
                loadUi();
                location.reload();
            }) 
        })        
        $(".modeleavradio").live("click",function(){
            var data = {}
            data.id = $(this).attr("ref");
            data.group = "<?php echo $model->group?>";
            data.access = $(this).val();
            data.type = "AttributeItems";
            ProgressBar.displayProgressBar();
            $.post("<?php echo Yii::app()->params['mainurl'] ?>settings/usergroups/ajaxaccesssave",data,function(result){
                ProgressBar.hideProgressBar();
                loadUi();
            }) 
        })   
    });

</script>