<table cellpadding="0" cellspacing="0" border="0" class="display" id="modules" width="100%">
    <thead>
        <tr>
            <th><?php echo $this->translate(MODULE) ?></th>
            <th><?php echo $this->translate(BLOCK) ?><input class="modules_checkbox" value="block" type="checkbox"></th>
            <th><?php echo $this->translate(VIEW) ?><input class="modules_checkbox" value="view" type="checkbox"></th>
            <th><?php echo $this->translate(ADMIN) ?><input class="modules_checkbox" value="admin" type="checkbox"></th>
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
                <td><input class="modules_radio" ref="<?php echo $data["id"] ?>" type="radio" <?php echo $access[$model->group] == "block" ? "checked" : "" ?> value="block" class="accessradio" name="<?php echo $data["module"] ?>"></td>
                <td><input class="modules_radio" ref="<?php echo $data["id"] ?>" type="radio" <?php echo $access[$model->group] == "view" ? "checked" : "" ?> value="view" class="accessradio" name="<?php echo $data["module"] ?>"></td>
                <td><input class="modules_radio" ref="<?php echo $data["id"] ?>" type="radio" <?php echo $access[$model->group] == "admin" ? "checked" : "" ?> value="admin" class="accessradio" name="<?php echo $data["module"] ?>"></td>
            </tr>
<?php endforeach; ?>
    </tbody>
</table>

<BR><BR>
<script>
    $(document).ready(function() {
        $('#modules').dataTable();
        $(".modules_checkbox").live("click",function(){
            var obj = $(this);
            $(".modules_radio").each(function(){ 
                if ($(this).val() == obj.val()) {
                    $(this).attr("checked",true);
                } 
            })
            data.group = "<?php echo $model->group ?>";
            data.access = $(this).val();
            data.type = "Accessmodule";
            ProgressBar.displayProgressBar();
            $.post("<?php echo Yii::app()->params['mainurl'] ?>settings/usergroups/ajaxaccesssave",data,function(result){
                ProgressBar.hideProgressBar();
                loadUi();
                location.reload();
            }) 
        })        
        $(".modules_radio").live("click",function(){
            var data = {}
            data.id = $(this).attr("ref");
            data.group = "<?php echo $model->group?>";
            data.access = $(this).val();
            data.type = "Accessmodule";
            ProgressBar.displayProgressBar();
            $.post("<?php echo Yii::app()->params['mainurl'] ?>settings/usergroups/ajaxaccesssave",data,function(result){
                ProgressBar.hideProgressBar();
                
                loadUi();
            }) 
        })   
    });

</script>