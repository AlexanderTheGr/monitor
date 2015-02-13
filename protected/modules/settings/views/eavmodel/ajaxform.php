<?php require Yii::app()->params['widget']."ajaxform.php";?>
<?php $model->load()?>
<?php if  ($model->softone): ?>
<button class="softone" refid="<?php echo $model->id ?>" ref="<?php echo $model->id ?>">Sync with Softone</button>
<?php endif;?>
<table>
    <tr>
        <th>Title</th>
        <th>Group</th>
        <th>CSS Style</th>
        <th>List Style</th>
        <th>Data</th>
        <th>Required</th>
        <th>Visible</th>
        <th>Unique</th>
        <th>Sort</th>
        <th>Column</th>
        <th></th>
        <th></th>
    </tr>
    <?php $items = array(0); ?>
    <?php foreach ((array) $model->AttributeItems() as $item): ?>
        <?php $items[] = $item->attribute_id ?>
        <tr>
            <td><input type="text" class="settitle" ref="<?php echo $item->id ?>" value='<?php echo $item->title == '' ? $item->attribute()->title : $item->title ?>'></td>
            <td><?php echo CHtml::dropDownList('group_id', $item->group_id, $this->getAttributeGroupsFormData(), array("class" => 'attrgroup', "ref" => $item->id)); ?></td>
            <td><input type="text" class="setcss" ref="<?php echo $item->id ?>" name="css[<?php echo $item->id ?>]" value='<?php echo $item->css ?>'></td>
            <td><?php echo CHtml::dropDownList('list_style', $item->list_style, $this->enumArray["list_style"], array("class" => 'setliststyle', "ref" => $item->id)); ?></td>
            <td><input type="text" name="select_data[<?php echo $item->id ?>]" value='<?php echo $item->select_data ?>'></td>                   
            <td><input type="checkbox" value=1 <?php echo $item->required == 1 ? 'checked' : '' ?> class="setrequired" ref="<?php echo $item->id ?>"  ></td>
            <td><input type="checkbox" value=1 <?php echo $item->visible == 1 ? 'checked' : '' ?> class="setvisible" ref="<?php echo $item->id ?>" ></td>
            <td><input type="checkbox" value=1 <?php echo $item->unique == 1 ? 'checked' : '' ?> class="setunique" ref="<?php echo $item->id ?>" ></td>
            <td><input type="text" size="2" class="setsort" ref="<?php echo $item->id ?>"  value='<?php echo $item->sort ?>'></td>
            <td><input type="text" size="2" class="setcolumn" ref="<?php echo $item->id ?>"  value='<?php echo $item->column ?>'></td>
            <td><button class="removeitem" refid="<?php echo $model->id ?>" ref="<?php echo $item->id ?>">Remove</button></td>
            <td><button class="accessitem" refid="<?php echo $model->id ?>" ref="<?php echo $item->id ?>">Permitions</button></td>
        </tr>
    <?php endforeach; ?>
</table>
<table>
    <tr>
        <td><?php echo CHtml::dropDownList('attrlist', "", $this->getAttributesFormData($items), array("class" => "attrlist")); ?></td>
        <td><button class="additem" refid="<?php echo $model->id ?>" ref="<?php echo $model->eav_model ?>">Add Attribute</button></td>
    </tr>
</table>




