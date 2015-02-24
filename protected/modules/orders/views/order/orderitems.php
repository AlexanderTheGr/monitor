<div style="min-height:1000px">
<?php if ($model->fullytrans == 0):?>  
<div class="span3">
    Αναζήτηση με Κωδικό<BR>
    
    <input id="productitem" class='productitem' type="text"><!--button style='margin-top: -10px' class="btn savesoftone">Αναζήτηση</button-->
    <div class="order">
    <?php $this->vehiclesBlock($model);?>    
    </div>
    <div class="categories order">
    <?php //$this->categoriesBlock();?>    
    </div>
    <div class="subcategories">
    <?php ?>    
    </div>
</div>
<?php endif; ?>
<div class="span9">
    <?php require Yii::app()->params['widget']."datatable.php";?>  
</div>    
    
    
</div>





<script>
  $(function() {
    function log( message ) {
      $( "<div>" ).text( message ).prependTo( "#log" );
      $( "#log" ).scrollTop( 0 );
    }
 
    $(".orderitem").live("change",function() {
        var data = {}
        if ($(this).hasClass("chk")) {
            $(this).val($(this).prop('checked') ? 1 : 0);            
        }
        data.id = $(this).attr("ref");
        data.field = $(this).attr("field");
        data.value = $(this).val();
        
        $.post("<?php echo Yii::app()->request->baseUrl?>/orders/order/editorderitem",data,function() {
            callback.orderitem();
        })                  
    })
    
    $(".isc1prc").live("change",function() {
        var data = {}
        data.id = $(this).attr("ref");
        data.field = $(this).attr("field");
        data.value = $(this).val();
        $.post("<?php echo Yii::app()->request->baseUrl?>/orders/order/editorder",data,function() {
            callback.orderitem();
        })                  
    }) 
    
    $(".productitem").focus();
    $(".delete_model").live("click",function() {
        data.id = $(this).attr("ref");
        $.post("<?php echo Yii::app()->request->baseUrl?>/orders/order/orderitemsajaxdelete",data,function() {
            callback.orderitem();
        })            
    })
    $(".sendtoorder").live("click",function() {
        data.id = $(this).attr("ref");
        data.order = $("#sendtoorderlist").val();
        $.post("<?php echo Yii::app()->request->baseUrl?>/orders/order/sendtoorder",data,function(result) {
            location.href = "<?php echo Yii::app()->request->baseUrl?>/orders/order/edit/"+result;
        })            
    })    
    /*
    $( "#productitem" ).autocomplete({
      source: "<?php echo Yii::app()->request->baseUrl?>/product/product/search",
      minLength: 2,
      select: function( event, ui ) {
        var data = {}
        data.order = '<?php echo (int)$order ?>'
        data.product = ui.item.id;
       
        $.post("<?php echo Yii::app()->request->baseUrl?>/orders/order/addorderitem",data,function() {
            $( "#productitem" ).val("");
            callback.orderitem();
        })  
      }
    });
    */
  });
</script>