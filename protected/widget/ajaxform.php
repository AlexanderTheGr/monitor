
<?php if ($model->getModelViewStyle() == "tab" AND $model->viewstyle == 'tab'): ?>
    <div id="tab<?php echo $model->tableName ?>">
        <ul>
            <?php foreach ($model->groups as $id => $value): ?>
                <?php $access = unserialize($model->groupObjs[$id]->access) ?>
                <?php
                if ($access[$this->userrole] == "block")
                    continue;
                ?>
                <li id="l_tab<?php echo $model->tableName ?>-<?php echo $id ?>"><a href="#tab<?php echo $model->tableName ?>-<?php echo $id ?>">
                        <?php echo $this->translate($value); ?></a><span class="rea"></span></li>

            <?php endforeach; ?>
            <?php foreach ((array) $tabs as $id => $value): ?>
                <li><a href="#tab<?php echo $model->tableName ?>-<?php echo $id + 1000 ?>"><?php echo $value["title"]; ?></a></li>
            <?php endforeach; ?>                
        </ul>
        <?php foreach ($model->groups as $id => $value): ?>
            <?php $access = unserialize($model->groupObjs[$id]->access) ?>
            <?php
            if ($access[$this->userrole] == "block")
                continue;
            ?>
            <div id="tab<?php echo $model->tableName ?>-<?php echo $id ?>">
                <table>
                    <?php if ($id == 1): ?> 
                        <?php foreach ($this->formFields as $data): ?>
                            <?php echo $this->modelAttributeFormRow($model, $data); ?>
                        <?php endforeach; ?>
                    <?php endif ?>
                    <?php echo $this->attributeList($model, $model->tableName . "[" . $model->id . "]", $id) ?>
                </table> 
            </div>
        <?php endforeach; ?>
        <?php foreach ((array) $tabs as $id => $value): ?>
            <div id="tab<?php echo $model->tableName ?>-<?php echo $id + 1000 ?>">

            </div>
        <?php endforeach; ?>        

    </div>
    <script>
        var data = {}
        $(document).ready(function() {
            $("#tab<?php echo $model->tableName ?>").tabs();
        })
    <?php foreach ((array) $tabs as $id => $value): ?>
            $.post('<?php echo Yii::app()->params['mainurl'] . $value["reg"] ?>', data, function(result) {
                $("#tab<?php echo $model->tableName ?>-<?php echo $id + 1000 ?>").html(result)
                loadUi();
            })
    <?php endforeach; ?>
    </script>
<?php elseif ($model->getModelViewStyle() == "accordion"): ?>
    <div id="accordion">
        <?php foreach ($model->groups as $id => $value): ?>
            <?php $access = unserialize($model->groupObjs[$id]->access) ?>
            <?php
            if ($access[$this->userrole] == "block")
                continue;
            ?>
            <h3><?php echo $value; ?></h3>
            <div>
                <table>
                    <?php if ($id == 1): ?>  
                        <?php foreach ($this->formFields as $data): ?>
                            <?php echo $this->modelAttributeFormRow($model, $data); ?>
                        <?php endforeach; ?>
                    <?php endif ?>
                    <?php echo $this->attributeList($model, $model->tableName . "[" . $model->id . "]", $id) ?>
                </table> 
            </div>
        <?php endforeach; ?>
    </div>
    <script>
        $(document).ready(function() {
            $("#accordion").accordion({
                autoHeight: false,
                navigation: true,
                icons: false
            });
        })
    </script>
<?php else: ?>
    <?php foreach ($model->groups as $id => $value): ?>
        <?php $access = unserialize($model->groupObjs[$id]->access) ?>
        <?php
        if ($access[$this->userrole] == "block")
            continue;
        ?>
        <table group="<?php echo $value; ?>" id="tbl<?php echo $model->tableName ?>">
            <?php foreach ($this->formFields as $data): ?>
                <?php echo $this->modelAttributeFormRow($model, $data); ?>
            <?php endforeach; ?>
            <?php echo $this->attributeList($model, $model->tableName . "[" . $model->id . "]", $id) ?>
        </table> 
    <?php endforeach; ?>
<?php endif; ?>

<script>

    $(".save_model_<?php echo $model->className() ?>").live("click", function() {
        savemodel(<?php echo (int) $model->id ?>, '<?php echo $model->tableName ?>', '<?php echo Yii::app()->params['mainurl'] . $this->ajaxformsave ?>', returnaftersave);
    })
    $(".delete_model_<?php echo $model->className() ?>").live("click", function() {
        deletemodel(<?php echo (int) $model->id ?>, '<?php echo Yii::app()->params['mainurl'] . $this->ajaxdelete ?>', returntonmain())
    })
    $(".return_to_main_<?php echo $model->className() ?>").live('click', function() {
        returntonmain()
    })
    function returntonmain() {
        setTimeout(function() {
            location.href = '<?php echo Yii::app()->params['mainurl'] . $this->returntomain ?>'
        }, 200)

    }
    function returnaftersave(result) {
<?php if ($model->id > 0): ?>
            //alert("Data Saved")
            location.reload();
<?php else: ?>
            //alert("Data Saved")
            location.href = '<?php echo Yii::app()->params['mainurl'] . $this->returnaftersafe ?>' + result
<?php endif ?>
    }

</script>