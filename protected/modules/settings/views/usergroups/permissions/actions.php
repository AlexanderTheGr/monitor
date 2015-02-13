
<table cellpadding="0" cellspacing="0" border="0" class="display" id="actions" width="100%">
    <thead>
        <tr>
            <th><?php echo $this->translate(MODULE) ?></th>
            <th><?php echo $this->translate(CONTROLLER) ?></th>
            <th><?php echo $this->translate(ACTION) ?></th>
            <th><?php echo $this->translate(BLOCK) ?><input class="actions_checkbox" value="block" type="checkbox"></th>
            <th><?php echo $this->translate(VIEW) ?><input class="actions_checkbox" value="view"  type="checkbox"></th>
            <th><?php echo $this->translate(ADMIN) ?><input class="actions_checkbox" value="admin" type="checkbox"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($datas as $data): ?>

            <?php if ($data["module"] == "")
                continue; ?>
            <?php
            $access = unserialize($data["access"]);
            ?>
            <tr>
                <td><?php echo $data["module"] ?></td>
                <td><?php echo $data["controller"] ?></td>
                <td><?php echo $data["action"] ?></td>
                <td><input class="actions_radio" ref="<?php echo $data["id"] ?>" type="radio" <?php echo $access[$model->group] == "block" ? "checked" : "" ?> value="block" class="accessradio" name="<?php echo $data["module"] ?>_<?php echo $data["controller"] ?>_<?php echo $data["action"] ?>"></td>
                <td><input class="actions_radio" ref="<?php echo $data["id"] ?>" type="radio" <?php echo $access[$model->group] == "view" ? "checked" : "" ?> value="view" class="accessradio" name="<?php echo $data["module"] ?>_<?php echo $data["controller"] ?>_<?php echo $data["action"] ?>"></td>
                <td><input class="actions_radio" ref="<?php echo $data["id"] ?>" type="radio" <?php echo $access[$model->group] == "admin" ? "checked" : "" ?> value="admin" class="accessradio" name="<?php echo $data["module"] ?>_<?php echo $data["controller"] ?>_<?php echo $data["action"] ?>"></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<BR><BR>
<script>
    $(document).ready(function() {
        $('#actions').dataTable();
        $(".actions_checkbox").live("click",function(){
            var obj = $(this);
            $(".actions_radio").each(function(){ 
                if ($(this).val() == obj.val()) {
                    $(this).attr("checked",true);
                } 
            })
            data.group = "<?php echo $model->group ?>";
            data.access = $(this).val();
            data.type = "Accessmodulecontrolleraction";
            ProgressBar.displayProgressBar();
            $.post("<?php echo Yii::app()->params['mainurl'] ?>settings/usergroups/ajaxaccesssave",data,function(result){
                ProgressBar.hideProgressBar();
                loadUi();
                location.reload();
            }) 
        })
        
        $(".actions_radio").live("click",function(){
            var data = {}
            data.id = $(this).attr("ref");
            data.group = "<?php echo $model->group ?>";
            data.access = $(this).val();
            data.type = "Accessmodulecontrolleraction";
            ProgressBar.displayProgressBar();
            $.post("<?php echo Yii::app()->params['mainurl'] ?>settings/usergroups/ajaxaccesssave",data,function(result){
                ProgressBar.hideProgressBar();
                loadUi();
            }) 
        })
    });

</script>