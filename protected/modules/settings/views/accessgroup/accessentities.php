<?php $data = unserialize($model->data)?>
<div style="width:600px; height:600px; overflow: auto;">
    <table width="100%">
        <tr>
            <td style="vertical-align: top">
                <b>Modules</b>
                <table width="100%">
                    <?php foreach ($datas["Accessmodule"] as $accessmodule): ?>
                        <?php if (trim($accessmodule["module"]) == "")
                            continue; ?>
                        <tr id="Accessmodule_<?php echo $accessmodule["id"]?>" <?php echo $data["Accessmodule"][$accessmodule["id"]] == 1 ? "style='background:#ff4444; color:#ffffff'" : ""?>>
                            <td width="1"><input <?php echo $data["Accessmodule"][$accessmodule["id"]] == 1 ? "checked" : ""?> type="checkbox" entity="Accessmodule" ref="<?php echo $accessmodule["id"] ?>" class="accesscheckbox"  ></td>
                            <td><?php echo $accessmodule["module"] ?></td>
                        </tr>
                    <?php endforeach; ?> 
                </table>

                <b>Modules Controlers</b>
                <table width="100%">
                    <?php foreach ($datas["Accessmodulecontroller"] as $accessmodulecontroller): ?>
                        <?php if (trim($accessmodulecontroller["module"]) == "")
                            continue; ?>
                        <?php if (trim($accessmodulecontroller["controller"]) == "")
                            continue; ?>
                        <tr id="Accessmodulecontroller_<?php echo $accessmodulecontroller["id"]?>" <?php echo $data["Accessmodulecontroller"][$accessmodulecontroller["id"]] == 1 ? "style='background:#ff4444; color:#ffffff'" : ""?>>
                            <td width="1"><input <?php echo $data["Accessmodulecontroller"][$accessmodulecontroller["id"]] == 1 ? "checked" : ""?> type="checkbox" entity="Accessmodulecontroller" ref="<?php echo $accessmodulecontroller["id"] ?>" class="accesscheckbox"  ></td>
                            <td><?php echo $accessmodulecontroller["module"] ?>-><?php echo $accessmodulecontroller["controller"] ?></td>
                        </tr>
                    <?php endforeach; ?> 
                </table> 
                <b>Models</b>
                <table width="100%">
                    <?php foreach ($datas["Accessmodel"] as $accessmodel): ?>
                        <?php if (trim($accessmodel["model"]) == "")
                            continue; ?>
                        <tr id="Accessmodel_<?php echo $accessmodel["id"]?>" <?php echo $data["Accessmodel"][$accessmodel["id"]] == 1 ? "style='background:#ff4444; color:#ffffff'" : ""?>>
                            <td width="1"><input <?php echo $data["Accessmodel"][$accessmodel["id"]] == 1 ? "checked" : ""?> ref="<?php echo $accessmodel["id"] ?>" entity="Accessmodel"  type="checkbox" class="accesscheckbox"  ></td>
                            <td><?php echo $accessmodel["model"] ?></td>
                        </tr>
                    <?php endforeach; ?> 
                </table> 
                <b>Modules Controlers Action</b>
                <table width="100%">
                    <?php foreach ($datas["Accessmodulecontrolleraction"] as $accessmodulecontrolleraction): ?>
                        <?php if (trim($accessmodulecontrolleraction["module"]) == "")
                            continue; ?>
                        <?php if (trim($accessmodulecontrolleraction["controller"]) == "")
                            continue; ?>
                        <tr id="Accessmodulecontrolleraction_<?php echo $accessmodulecontrolleraction["id"]?>" <?php echo $data["Accessmodulecontrolleraction"][$accessmodulecontrolleraction["id"]] == 1 ? "style='background:#ff4444; color:#ffffff'" : ""?>>
                            <td width="1"><input  <?php echo $data["Accessmodulecontrolleraction"][$accessmodulecontrolleraction["id"]] == 1 ? "checked" : ""?> type="checkbox" entity="Accessmodulecontrolleraction" ref="<?php echo $accessmodulecontrolleraction["id"] ?>" class="accesscheckbox"  ></td>
                            <td><?php echo $accessmodulecontrolleraction["module"] ?>-><?php echo $accessmodulecontrolleraction["controller"] ?>-><?php echo $accessmodulecontrolleraction["action"] ?></td>
                        </tr>
                    <?php endforeach; ?> 
                </table>          
            </td>
            <td style="vertical-align: top">
                <b>Model Fields</b>
                <table width="100%">
                    <?php foreach ($datas["Accessmodelfield"] as $accessmodelfield): ?>
                        <?php if (trim($accessmodelfield["model"]) == "")
                            continue; ?>
                        <tr id="Accessmodelfield_<?php echo $accessmodelfield["id"]?>" <?php echo $data["Accessmodelfield"][$accessmodelfield["id"]] == 1 ? "style='background:#ff4444; color:#ffffff'" : ""?>>
                            <td width="1"><input <?php echo $data["Accessmodelfield"][$accessmodelfield["id"]] == 1 ? "checked" : ""?> type="checkbox" entity="Accessmodelfield" ref="<?php echo $accessmodelfield["id"] ?>" class="accesscheckbox"  ></td>
                            <td><?php echo $accessmodelfield["model"] ?>-><?php echo $accessmodelfield["field"] ?></td>
                        </tr>
                    <?php endforeach; ?> 
                </table>  
                <b>Model EAV Fields</b>
                <table width="100%">
                    <?php foreach ($datas["AttributeItems"] as $attributeitem): ?>
                        
                            <tr id="AttributeItems_<?php echo $attributeitem["id"]?>" <?php echo $data["AttributeItems"][$attributeitem["id"]] == 1 ? "style='background:#ff4444; color:#ffffff'" : ""?>>
                            <td width="1"><input <?php echo $data["AttributeItems"][$attributeitem["id"]] == 1 ? "checked" : ""?> type="checkbox" entity="AttributeItems" ref="<?php echo $attributeitem["id"] ?>" class="accesscheckbox"  ></td>
                            <td><?php echo $attributeitem["eav_model"] ?>-><?php echo $attributeitem["title"] ?></td>
                        </tr>
                    <?php endforeach; ?> 
                </table>                
                <b>Model EAV Groups</b>
                <table width="100%">
                    <?php foreach ($datas["AttributeGroups"] as $attributegroup): ?>
                            <tr id="AttributeGroups_<?php echo $attributegroup["id"]?>" <?php echo $data["AttributeGroups"][$attributegroup["id"]] == 1 ? "style='background:#ff4444; color:#ffffff'" : ""?>>
                            <td width="1"><input <?php echo $data["AttributeGroups"][$attributegroup["id"]] == 1 ? "checked" : ""?> type="checkbox" entity="AttributeGroups" ref="<?php echo $attributegroup["id"] ?>" class="accesscheckbox"  ></td>
                            <td><?php echo $attributegroup["group"] ?></td>
                        </tr>
                    <?php endforeach; ?> 
                </table>                  
                
            </td>
        </tr>
    </table>    
</div>
<script>
    $(document).ready(function() {
        $(".accesscheckbox").click(function(){
            var data = {}
            data.entity = $(this).attr("entity");
            data.id = <?php echo $model->id; ?>;
            
            data.ref =  $(this).attr("ref");
            data.check = $(this).is(':checked') ? 1 : 0;
            
            if ($(this).is(':checked')) {
                $("#"+data.entity+"_"+data.ref).css("background","#ff4444");
                $("#"+data.entity+"_"+data.ref).css("color","#ffffff");
            } else {
                $("#"+data.entity+"_"+data.ref).css("background","none");
                $("#"+data.entity+"_"+data.ref).css("color","#312E25");                
            }
            ProgressBar.displayProgressBar();
            $.post("<?php echo Yii::app()->params['mainurl'] ?>settings/accessgroup/accessssave",data,function(result){
                ProgressBar.hideProgressBar();

            }) 
        }) 
    })
</script>




