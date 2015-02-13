<div class="search-form" style="margin-bottom:20px; float: left">
    <h3>Αναζήτηση <span style="font-weight:bold">με Όχημα</span></h3>
    <div>
        <div class="block-content">
            <div style="float:left; padding:0 5px; width:100%" class="block-content">			
                <div class='plaisio'>
                    <select style="width:100%" name="brand_id" class="brand-select" class="brand-select" title="" onchange="">
                        <option value="0"><?php echo $this->translate("Select Brand"); ?></option>
                        <?php foreach ($this->getBrands() as $brand): ?>
                            <option value="<?php echo $brand->id ?>"><?php echo $brand->brand ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class='plaisio'>

                    <select style="width:100%" name="brand_model_id" class="brand_model-select inactive" class="brand_model-select" title="" onchange="">

                    </select>   
                </div>

                <div class='plaisio'>

                    <select  style="width:100%" name="brand_model_type_id" class="brand_model_type-select inactive" class="brand_model-select" title="" onchange="">

                    </select>  
                </div>				
            </div>        
        </div>
        <div class="actions" style="margin-left:10px;">
            <button type="button" id="gogo" title="Αναζήτηση" class="gogo button"><span><span><?php echo $this->translate('Search') ?></span></span></button>    
        </div>
    </div>
    <!--h3>Αναζήτηση <span style="font-weight:bold">με Κωδικό</span></h3>
    <div>
        <form action="<?php //echo  Yii::app()->request->baseUrl; . "antallaktika/search"     ?>" method="get">
            <div class="form-search">
                <div class="block-content">
                    Παρακαλώ πληκτρολογήστε τον κωδικό του ανταλλακτικού<BR><BR>
                    Π.χ. W71295<BR>
                    <input id="search" placeholder="Γράψτε τον Κωδικό" type="text" name="s" value="<?php //echo $this->getRequest()->getParam('s')     ?>" class="input-text" maxlength="<?php //echo $catalogSearchHelper->getMaxQueryLength();     ?>" />
                </div>
                <div class="actions" style="margin-left:10px;">
                    <button type="submit" title="<?php echo $this->translate('Search') ?>" class="button"><span class='active'><span><?php echo $this->translate('Search') ?></span></span></button>
                </div>

                <div id="search_autocomplete" class="search-autocomplete"></div>
            </div>
        </form>
    </div-->
    <h3>Αναζήτηση <span style="font-weight:bold">με Κινητήρα</span></h3>
    <div>
        <div class="block-content">
            Παρακαλώ πληκτρολογήστε τον κωδικό του Κινητήρα<BR>
            Π.χ. AR32310<BR>
            <input placeholder="Γράψτε τον Κωδικό" id="searchmotor" type="text" name="s" value="<?php //echo $this->getRequest()->getParam('s')     ?>" class="searchmotor input-text" maxlength="<?php //echo $catalogSearchHelper->getMaxQueryLength();     ?>" />
        </div>
    </div>   
</div>



<script>

    jQuery(".searchmotor").autocomplete({
        source: "<?php echo Yii::app()->request->baseUrl; ?>/product/product/motorsearch",
        minLength: 2,
        select: function (event, ui) {
            var data = {}
            data.car = ui.item.id;
            if (data.car > 0) {
                jQuery.post("antallaktika/init/getLinkingTargetUrl", data, function (result) {
                    location.href = result
                })
            }
        }
    });
    jQuery(".search-form").accordion({
        heightStyle: "content",
        collapsible: true,
    });
    jQuery('.brand_model-select').append(jQuery('<option>').text("<?php echo $this->translate("Select Model"); ?>").attr('value', 0));
    jQuery('.brand_model_type-select').append(jQuery('<option>').text("<?php echo $this->translate("Select Engine"); ?>").attr('value', 0));

    jQuery(".brand-select").change(function () {
        var data = {}
        data.brand = jQuery(this).val();
        var group_name = "";
        jQuery('.brand_model_type-select').append(jQuery('<option>').text("<?php echo $this->translate("Select Engine"); ?>").attr('value', 0));
        jQuery('.brand_model-select').addClass("inactive");
        jQuery('.brand_model_type-select').addClass("inactive");
        jQuery('.search-form button.button span').removeClass("active");
        jQuery.post("<?php echo Yii::app()->request->baseUrl; ?>/product/product/getmodels", data, function (result) {
            var json = jQuery.parseJSON(result)
            if (json) {
                jQuery('.brand_model-select').removeClass("inactive");
            }
            jQuery('.brand_model-select').text("");
            jQuery('.brand_model_type-select').text("");
            jQuery('.brand_model-select').append(jQuery('<option>').text("<?php echo $this->translate("Select Model"); ?>").attr('value', 0));
            jQuery('.brand_model_type-select').append(jQuery('<option>').text("<?php echo $this->translate("Select Engine"); ?>").attr('value', 0));
            var sop = "";
            var a = 0;
            jQuery.each(json, function (i, optionHtml) {

                jQuery('.brand_model-select').append(jQuery('<option>').text(optionHtml.name).attr('value', optionHtml.id));
                /*
                 var opt = jQuery('<option>').text(optionHtml.name).attr('value', optionHtml.id);
                 if (optionHtml.group_name != group_name) {
                 a = a + 1;
                 var optgroup = jQuery('<optgroup class="c_' + a + '">').attr('label', optionHtml.group_name);
                 jQuery('.brand_model-select').append(optgroup);
                 group_name = optionHtml.group_name;
                 }
                 jQuery(".c_" + a).append(opt);
                 */

            });
        });
    })
    jQuery(".brand_model-select").change(function () {
        var data = {}
        data.brand = jQuery(".brand-select").val();
        data.model = jQuery(this).val();
        var motor_type = "";
        var sop = "";
        jQuery('.brand_model_type-select').addClass("inactive");
        jQuery('.search-form button.button span').removeClass("active");
        jQuery.post("<?php echo Yii::app()->request->baseUrl; ?>/product/product/getmodeltypes", data, function (result) {
            var json = jQuery.parseJSON(result)
            if (json) {
                jQuery('.brand_model_type-select').removeClass("inactive");
            }
            jQuery('.brand_model_type-select').text("");
            jQuery('.brand_model_type-select').append(jQuery('<option>').text("<?php echo $this->translate("Select Engine"); ?>").attr('value', 0));
            var a = 0;
            jQuery.each(json, function (i, optionHtml) {

                var opt = jQuery('<option>').text(optionHtml.name).attr('value', optionHtml.id);

                if (optionHtml.motor_type != motor_type) {
                    a = a + 1;
                    var optgroup = jQuery('<optgroup class="m_' + a + '">').attr('label', optionHtml.motor_type);
                    sop = jQuery('.brand_model_type-select').append(optgroup);
                    motor_type = optionHtml.motor_type;
                }
                jQuery(".m_" + a).append(opt);

            });
        });
    })
    jQuery(".gogo").click(function () {
        var data = {}
        data.car = jQuery(".brand_model_type-select").val();
        jQuery(".subcategories").html("");
        jQuery(".categories").html("");
        if (data.car > 0) {
            jQuery.post("<?php echo Yii::app()->request->baseUrl; ?>/product/product/getcategories", data, function (result) {
                //location.href = result
                jQuery(".categories").html(result);
                jQuery(".subcategories").html("");
                $(".tabs").tabs({ heightStyle: "auto" });
            })
        }
    })

</script>