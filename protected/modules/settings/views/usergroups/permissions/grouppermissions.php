<table cellpadding="0" cellspacing="0" border="0" class="display" id="accessgroup" width="100%">
    <thead>
        <tr>
            <th><?php echo $this->translate(TITLE) ?></th>
            <th><?php echo $this->translate(BLOCK) ?><input class="accessgroupcheckbox" value="block" type="checkbox"></th>
            <th><?php echo $this->translate(VIEW) ?><input class="accessgroupcheckbox" value="view" type="checkbox"></th>
            <th><?php echo $this->translate(ADMIN) ?><input class="accessgroupcheckbox" value="admin" type="checkbox"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($datas as $data): ?>


            <?php
            $access = unserialize($data["access"]);
            ?>
            <tr>
                <td><?php echo $data["title"] ?></td>
                <td><input class="accessgroupradio" ref="<?php echo $data["id"] ?>" type="radio" <?php echo $access[$model->group] == "block" ? "checked" : "" ?> value="block" class="accessradio" name="<?php echo $data["title"] ?>"></td>
                <td><input class="accessgroupradio" ref="<?php echo $data["id"] ?>" type="radio" <?php echo $access[$model->group] == "view" ? "checked" : "" ?> value="view" class="accessradio" name="<?php echo $data["title"] ?>"></td>
                <td><input class="accessgroupradio" ref="<?php echo $data["id"] ?>" type="radio" <?php echo $access[$model->group] == "admin" ? "checked" : "" ?> value="admin" class="accessradio" name="<?php echo $data["title"] ?>"></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<BR><BR>
<script>
    $(document).ready(function() {
        $('#accessgroup').dataTable();
        $(".accessgroupradio").live("click",function(){
            var data = {}
            data.id = $(this).attr("ref");
            data.group = "<?php echo $model->group ?>";
            data.access = $(this).val();
            data.type = "Accessgroup";
            ProgressBar.displayProgressBar();
            $.post("<?php echo Yii::app()->params['mainurl'] ?>settings/usergroups/ajaxaccesssave",data,function(result){
                $.post("<?php echo Yii::app()->params['mainurl'] ?>settings/usergroups/accessgroupupdate",data,function(result){
                    ProgressBar.hideProgressBar();
                    location.reload();
                    
                })
            }) 
        })  
    })
</script>
