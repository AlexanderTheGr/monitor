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
</script>