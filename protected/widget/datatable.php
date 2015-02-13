<?php
$attributes['class'] = 'display';
$attributes['id'] = $this->dataTableId;
$attributes['tableName'] = $this->tableName;
$params['sAjaxSource'] = "'" . Yii::app()->params['mainurl'] . $this->sAjaxSource."'";
$params['sServerMethod'] = "'POST'";
$params['sPaginationType'] = "'full_numbers'";

$params['aLengthMenu'] = "[[100 , 150, 200, -1], [100 , 150, 200, 'All']]";
$params['iDisplayLength'] = '100';
$params['bPaginate'] = $this->bPaginate;
$params['bFilter'] = $this->bFilter;

$params["fnInitComplete"] = $this->fnInitComplete;
$params["bAutoWidth"] = "false";



$params['bInfo'] = $this->bInfo;
$params['bRetrieve'] = "'true'";
$params['aaSorting'] = $this->aaSorting;
if (count($this->aoColumns)) $params['aoColumns'] = json_encode ($this->aoColumns);




$buttons['bAddnewpos'] = $this->bAddnewpos;
$buttons["showSave"] = $this->showSave;
$buttons["showDelete"] = $this->showDelete;
$buttons["showCancel"]  = $this->showCancel;
$buttons["showExport"]  = $this->showExport;


$params['bProcessing'] = "true";

$buttons["labels"] =  $this->btnTitles;

if ($this->useServerSide == true) {
    $params['bServerSide'] = "true";

}


$fieldAttrs[] = array('width' => 20);
$ajaxurls["ajaxformtitle"] = Yii::app()->params['mainurl'] . $this->ajaxformtitle;;
$ajaxurls["ajaxform"] = Yii::app()->params['mainurl'] . $this->ajaxform;
$ajaxurls["ajaxformsave"] = Yii::app()->params['mainurl'] . $this->ajaxformsave;
$ajaxurls["ajaxdelete"] = Yii::app()->params['mainurl'] . $this->ajaxdelete;
$ajaxurls["ajaxpage"] = Yii::app()->params['mainurl'] . $this->ajaxpage;
$callback = $this->ajaxcallback;


echo HtmlWidget::dataTable($this->columns, $fieldAttrs, $attributes, $params, $ajaxurls,$this->sfields,$buttons,$callback);
?>
<?php if ($this->ajaxnew != ""):?>
<?php 

?>
<script>
    $("#addnew_<?php echo $attributes["id"] ?>").live('click', function() {
        
        location.href='<?php echo Yii::app()->params['mainurl'] . $this->ajaxnew?>'
    })
</script>
<?php endif;?>