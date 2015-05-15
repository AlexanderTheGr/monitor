<div style="min-height:1000px">
    <?php if ($model->fullytrans == 0): ?> 

        <div class="asdfg asdf1" style="padding: 5px; border:1px #999 solid; width:100px; overflow: hidden; position: absolute; z-index: 10; background: #fff; margin-left: -10px;">
            <div class="asdfg asdf2" style="width: 100px; overflow: hidden;float: left; margin-left: -110px;">              
                <div style="width:530px;">
                    Αναζήτηση με Κωδικό<BR>
                    <input id="productitem" class='productitem' type="text"><!--button style='margin-top: -10px' class="btn savesoftone">Αναζήτηση</button-->
                    <div class="order">
                        <?php $this->vehiclesBlock($model); ?>    
                    </div>
                    <div class="categories order">
                        <?php //$this->categoriesBlock();?>    
                    </div>
                    <div class="subcategories">
                        <?php ?>    
                    </div> 
                </div>
            </div> 
            <div id="gogogo" style="width:100px; float: left; font-size:16px; cursor: pointer"> 
                <img width="100%" class="search-open" src="<?php echo Yii::app()->request->baseUrl?>/images/search-open.png"/>
                <img width="100%" class="search-close" style="display:none" src="<?php echo Yii::app()->request->baseUrl?>/images/search-close.png"/>
            </div>    
        </div>

    <?php endif; ?>
    <div class="span11" style="float:right;  width: 93.452991%; margin: 0; padding: 0">
        <?php require Yii::app()->params['widget'] . "datatable.php"; ?>  
    </div>    
</div>





<script>
    var gogogo = 0;
    $(function () {
        var fgh=0
        
        function log(message) {
            $("<div>").text(message).prependTo("#log");
            $("#log").scrollTop(0);
        }
        
        $('#productitem').keyup(function(e){
            if(e.which == 27 && gogogo == 0){
               $("#gogogo").click();
            }
        });
        
        
        $("#gogogo").live("click", function () {
            $("#productitem").focus()
            if (fgh == 0) {
                $(".asdfg").animate({width:630, marginLeft:0});
                $(".asdf2").animate({width:530, marginLeft:0},1);
                $(".search-open").hide();
                $(".search-close").show();
                fgh=1;
            } else if (fgh == 1) {
                $(".asdfg").animate({width:100, marginLeft:-10});
                $(".asdf2").animate({marginLeft:-110},1);
                $(".search-open").show();
                $(".search-close").hide();                
                fgh=0;
            }
        })

        $(".orderitem").live("change", function () {
            var data = {}
            if ($(this).hasClass("chk")) {
                $(this).val($(this).prop('checked') ? 1 : 0);
            }
            data.id = $(this).attr("ref");
            data.field = $(this).attr("field");
            data.value = $(this).val();

            $.post("<?php echo Yii::app()->request->baseUrl ?>/orders/order/editorderitem", data, function () {
                callback.orderitem();
            })
        })

        $(".isc1prc").live("change", function () {
            var data = {}
            data.id = $(this).attr("ref");
            data.field = $(this).attr("field");
            data.value = $(this).val();
            $.post("<?php echo Yii::app()->request->baseUrl ?>/orders/order/editorder", data, function () {
                callback.orderitem();
            })
        })

        $(".product_info").live("click", function () {
            var data = {}
            data.id = $(this).attr("ref");
            ProgressBar.displayProgressBar();
            $.post("<?php echo Yii::app()->request->baseUrl ?>/product/product/getProductInfo", data, function (result) {

                $productInfo = $("<div></div>")
                        .dialog({
                            autoOpen: false,
                            resizable: false,
                            draggable: false,
                            width: 1200,
                            height: 600,
                            modal: true,
                            close: function (ev, ui) {
                                $(".productitem").val('')
                                $("#productitem").focus()
                            }
                        });
                $productInfo.html(result);
                $productInfo.dialog("open");
                ProgressBar.hideProgressBar();
            })
        })

        $(".productitem").focus();
        $(".delete_model").live("click", function () {
            data.id = $(this).attr("ref");
            $.post("<?php echo Yii::app()->request->baseUrl ?>/orders/order/orderitemsajaxdelete", data, function () {
                callback.orderitem();
            })
        })
        $(".sendtoorder").live("click", function () {
            data.id = $(this).attr("ref");
            data.order = $("#sendtoorderlist").val();
            $.post("<?php echo Yii::app()->request->baseUrl ?>/orders/order/sendtoorder", data, function (result) {
                location.href = "<?php echo Yii::app()->request->baseUrl ?>/orders/order/edit/" + result;
            })
        })
        /*
         $( "#productitem" ).autocomplete({
         source: "<?php echo Yii::app()->request->baseUrl ?>/product/product/search",
         minLength: 2,
         select: function( event, ui ) {
         var data = {}
         data.order = '<?php echo (int) $order ?>'
         data.product = ui.item.id;
         
         $.post("<?php echo Yii::app()->request->baseUrl ?>/orders/order/addorderitem",data,function() {
         $( "#productitem" ).val("");
         callback.orderitem();
         })  
         }
         });
         */
    });
</script>