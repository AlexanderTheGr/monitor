<?php require Yii::app()->params['widget'] . "datatable.php"; ?>
<script>
   
    $(".additem").live("click", function(){
        var data = {}
        var obj = this;
        data['eav_model'] = $(this).attr('ref')
        data['attr'] = $(".attrlist").val()
        $( "#progressbar" ).show();
        $( "#progressbar" ).progressbar({
            value: 59
        });
        $.post('<?php echo Yii::app()->params['mainurl'] . "settings/eavmodel/ajaxaddattr/" ?>',data,function(){
            $( "#progressbar" ).hide();
            $( "#progressbar" ).progressbar( "destroy" );
            $('.eavmodel').each(function(){
                if (getId($(this).attr('id')) == $(obj).attr('refid')) {
                    $(this).click();
                }
            })
        })
    })
    
    
    $(".softone").live("click", function(){
        var data = {}
        data.id = $(this).attr('ref');
        $.post('<?php echo Yii::app()->params['mainurl'] . "settings/eavmodel/softone/" ?>',data,function(){
            
        })        
    })
    
    $(".accessitem").live("click", function(){
        $dialog.accessitem = $("<div></div>")
        .dialog({
            autoOpen: false,
            resizable:false,
            draggable:false,
            width:'auto',
            modal: true
        });
        var data = {}
        data.id = $(this).attr('ref')
        $.post('<?php echo Yii::app()->params['mainurl'] . "settings/default/accessform/" ?>',data,function(result){
            var arr = [];
            
            $dialog.accessitem.dialog({
                title:'<?php echo $this->translate(CHANGE_STATUS); ?>: '+status,
                buttons: {
                    '<?php echo $this->translate(SAVE); ?>': function() { 
                        $(".accessradio").each(function(){
                            if ($(this).attr("checked")) {
                                $(this).attr("name")    
                                arr[$(this).attr("name")] = $(this).val();
                                
                            }                            
                        })
                        data.access = serialize(arr)
                        $.post('<?php echo Yii::app()->params['mainurl'] . "settings/default/accessformsave/" ?>',data,function(result){
                            $dialog.accessitem.dialog( "close" );
                        })              
                    },
                    '<?php echo $this->translate(CANCEL); ?>': function() {
                        $dialog.accessitem.dialog( "close" );
                    }
                }
            })        
            $dialog.accessitem.html(result);       
            $dialog.accessitem.dialog("close");
            $dialog.accessitem.dialog("open");   
            loadUi();
        })
    })
    
    $(".removeitem").live("click", function(){
        var data = {}
        var obj = this;
        data['id'] = $(this).attr('ref')
        $( "#progressbar" ).show();
        $( "#progressbar" ).progressbar({
            value: 59
        });
        $.post('<?php echo Yii::app()->params['mainurl'] . "settings/eavmodel/ajaxremoveitem/" ?>',data,function(){
            $( "#progressbar" ).hide();
            $( "#progressbar" ).progressbar( "destroy" );
            $('.eavmodel').each(function(){
                if (getId($(this).attr('id')) == $(obj).attr('refid')) {
                    $(this).click();
                }
            })            
        })    
    })    
    $(".setrequired").live("click", function(){
        var data = {}
        var obj = this;
        data['id'] = $(this).attr('ref')
        $( "#progressbar" ).show();
        $( "#progressbar" ).progressbar({
            value: 59
        });
        $.post('<?php echo Yii::app()->params['mainurl'] . "settings/eavmodel/ajaxsetrequired/" ?>',data,function(){
            $( "#progressbar" ).hide();
            $( "#progressbar" ).progressbar( "destroy" );
        })    
    })
    $(".setunique").live("click", function(){
        var data = {}
        var obj = this;
        data['id'] = $(this).attr('ref')
        $( "#progressbar" ).show();
        $( "#progressbar" ).progressbar({
            value: 59
        });
        $.post('<?php echo Yii::app()->params['mainurl'] . "settings/eavmodel/ajaxsetunique/" ?>',data,function(){
            $( "#progressbar" ).hide();
            $( "#progressbar" ).progressbar( "destroy" );
        })    
    })    
    $(".setvisible").live("click", function(){
        var data = {}
        var obj = this;
        data['id'] = $(this).attr('ref')
        $( "#progressbar" ).show();
        $( "#progressbar" ).progressbar({
            value: 59
        });
        $.post('<?php echo Yii::app()->params['mainurl'] . "settings/eavmodel/ajaxsetvisible/" ?>',data,function(){
            $( "#progressbar" ).hide();
            $( "#progressbar" ).progressbar( "destroy" );
        })    
    }) 
    $(".setliststyle").live('change', function(){
        var data = {}
        var obj = this;
        data['id'] = $(this).attr('ref')
        data['value'] = $(this).val()
        $( "#progressbar" ).show();
        $( "#progressbar" ).progressbar({
            value: 59
        });
        $.post('<?php echo Yii::app()->params['mainurl'] . "settings/eavmodel/ajaxsetliststyle/" ?>',data,function(){
            $( "#progressbar" ).hide();
            $( "#progressbar" ).progressbar( "destroy" );
        })          
    })    
    $(".attrgroup").live('change', function(){
        var data = {}
        var obj = this;
        data['id'] = $(this).attr('ref')
        data['value'] = $(this).val()
        $( "#progressbar" ).show();
        $( "#progressbar" ).progressbar({
            value: 59
        });
        $.post('<?php echo Yii::app()->params['mainurl'] . "settings/eavmodel/ajaxsetgroup/" ?>',data,function(){
            $( "#progressbar" ).hide();
            $( "#progressbar" ).progressbar( "destroy" );
        })          
    })
    $(".setcolumn").live('change', function(){
        var data = {}
        var obj = this;
        data['id'] = $(this).attr('ref')
        data['value'] = $(this).val()
        $( "#progressbar" ).show();
        $( "#progressbar" ).progressbar({
            value: 59
        });
        $.post('<?php echo Yii::app()->params['mainurl'] . "settings/eavmodel/ajaxsetcolumn/" ?>',data,function(){
            $( "#progressbar" ).hide();
            $( "#progressbar" ).progressbar( "destroy" );
        })          
    })        
    $(".setsort").live('change', function(){
        var data = {}
        var obj = this;
        data['id'] = $(this).attr('ref')
        data['value'] = $(this).val()
        $( "#progressbar" ).show();
        $( "#progressbar" ).progressbar({
            value: 59
        });
        $.post('<?php echo Yii::app()->params['mainurl'] . "settings/eavmodel/ajaxsetsort/" ?>',data,function(){
            $( "#progressbar" ).hide();
            $( "#progressbar" ).progressbar( "destroy" );
        })          
    })    
    $(".settitle").live('change', function(){
        var data = {}
        var obj = this;
        data['id'] = $(this).attr('ref')
        data['value'] = $(this).val()
        $( "#progressbar" ).show();
        $( "#progressbar" ).progressbar({
            value: 59
        });
        $.post('<?php echo Yii::app()->params['mainurl'] . "settings/eavmodel/ajaxsettitle/" ?>',data,function(){
            $( "#progressbar" ).hide();
            $( "#progressbar" ).progressbar( "destroy" );
        })          
    })      
    $(".setcss").live('change', function(){
        var data = {}
        var obj = this;
        data['id'] = $(this).attr('ref')
        data['value'] = $(this).val()
        $( "#progressbar" ).show();
        $( "#progressbar" ).progressbar({
            value: 59
        });
        $.post('<?php echo Yii::app()->params['mainurl'] . "settings/eavmodel/ajaxsetcss/" ?>',data,function(){
            $( "#progressbar" ).hide();
            $( "#progressbar" ).progressbar( "destroy" );
        })          
    })      
</script>
