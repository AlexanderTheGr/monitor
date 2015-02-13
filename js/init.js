/*
 *	Code design and implementation of the current file was done by Paris Giannoukos (p.giannoukos@gmail.com).
 *	Copyright (c) 2011. All rights reserved.
 */

/*system object*/
var System={
    update:1,
    save:2,
    emptyValue:'-1',
    getJson:function(unformatted) {
        return jQuery.parseJSON(unformatted)
    },
    sendRequest:function(url,data,callback,progress_bar) {
        if (progress_bar) { 
            ProgressBar.displayProgressBar();
        }
		
        $.post(url,data,function(response){
            if (progress_bar) { 
                ProgressBar.hideProgressBar();
            }
			
            callback(response);
        });
    },
    getResponse:function(response,execute) {
        var response=jQuery.parseJSON(response);
		
        if (execute) {
            if (response.code==RESPONSE_CODE_OK && execute.onSuccess) {
                execute.onSuccess(response.code,response.data);
            } else if (response.code==RESPONSE_CODE_ERROR && execute.onError) {
                execute.onError(response.code,response.data);
            }
        }
		
        return response.data;
    },
    alignCenterElement:function(element) {
        
        $(element).css("left",($(window).width()/2)-($(element).width()/2) );
        $(element).css("top",($(window).height()/2)-($(element).height()/2) );
    },
    arrayValueExists:function(value,array) {
        for(var i=0; i<array.length && array[i]!=value; i++);
        return i<array.length;
    },
    arrayHasDuplicatedValues:function(array) {
        var dups=[];
        for(var i=0; i<array.length; i++) {
            for(var j=i+1; j<array.length && array[i]!=array[j]; j++);
			
            if (j<array.length) {
                dups.push(array[j]);
            }
        }
        return dups;
    },
    redirect:function(url) {
        window.location=url;
    },
    toInt:function(string) {
        return parseInt(string);
    },
    toFloat:function(string) {
        return parseFloat(string);
    },
    areAllSet:function(array) {
        for(var i in array) {
            if (array[i]=='' || array[i]==null) return false;
        }
		
        return true;
    },
    pageReload: function() {
        location.reload();
    },
    objectLength: function(object) {
        var count=0;
        for( var i in object) count++;
        return count;
    },
    stripslashes: function(str) {
        str=str.replace(/\\'/g,'\'');
        str=str.replace(/\\"/g,'"');
        str=str.replace(/\\0/g,'\0');
        str=str.replace(/\\\\/g,'\\');
        return str;
    },
    removeDialogBoxTitle: function(id) {
        $("#"+id).parent().addClass(id);
        $("."+id+" .ui-dialog-titlebar").remove();
    },
    removeDialogBoxButtons: function(id) {
        $("#"+id).parent().addClass(id);
        $("."+id+" .ui-dialog-buttonpane").remove();
    },
    removeArrayDups:function(array) {			
        for (var i=0; i < array.length; i++) {
            for (var j=i+1; j < array.length;) {
                if (array[i] == array[j]) {
                    array.splice(j,j);
                } else {
                    j++
                }
            }
        }
    },
    debug:function(html) {
        $("#debug").html(html);
    },
    getWindowHeight:function() {
        return window.innerHeight;
    },
    getWindowWidth:function() {
        return window.innerWidth;		
    },
    toModal:function() {
        $('.modal_window').show();
    },
    removeModal:function() {
        $('.modal_window').hide();
    },
    getURLParameter:function(url,param) {
        param=param.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	
        var regexS = "[\\?&]"+param+"=([^&#]*)";
        var regex = new RegExp(regexS);
        var results = regex.exec(url);
		
        if( results == null )
            return "";
        else
            return results[1];
    }
};

/*Progress Bar*/

var ProgressBar={
    initProgressBar:function() {
        System.alignCenterElement($(".ui-progressbar"));


        
       $(".progressbar .ui-progressbar-value").css('background-image','url('+CALLBACK_URL+'/images/loading1.gif)');
    },
    displayProgressBar:function() {
        System.alignCenterElement($("#progressbar"));
        $( "#progressbar" ).show();
        $( "#progressbar" ).progressbar({
            value: 59
        });
        $('.modal_window').show();		
    },
    hideProgressBar:function() {
        System.alignCenterElement($("#progressbar"));
        $( "#progressbar" ).hide();
        $( "#progressbar" ).progressbar( "destroy" );
        $('.modal_window').hide();
    }	
}

/*ButtonProcess*/
var ButtonProcess={
    defineButtons:function() {
        $('.ui_button').button();
    }
}

/*Form Process*/
var FormProcess={
    hasRequired:function(form) {
        /*ment to be deleted*/
        var values={};
		
        form.find('.field').each(function() {
            var value=$(this).val().split(' ').join('');
			
            if ($(this).hasClass('required') && value=='') {
                $(this).addClass('error');
                $(this).val('');
				
                values=false;
            } else if (values) {
                values[$(this).attr('id')]=value;
				
                $(this).removeClass('error');
            }
        });
								
        return values;
    },
    getFields:function(form) {
        var values={},value;
		
        form.find('.field').each(function() {
            if ($(this).hasClass('ui_selectable')) {
                value=[];
                $(this).find('.ui-widget-content.ui-selected').each(function() {
                    value.push({
                        id:$(this).attr('id')
                    })
                });
            } else {
                value=$(this).val().split(' ').join('')==''?'':$(this).val();
            }
			
            if ($(this).hasClass('required') && value=='') {
                $(this).addClass('error');
                $(this).val('');

                values=false;
            } else if (values) {
                values[$(this).attr('id')]=value;
			
                $(this).removeClass('error');
            }
        });
								
        return values;
    },
    clearForm:function(form) {
        form.find('.field').each(function(){
            $(this).val('');
            $(this).attr('disabled','');
        });
    },
    setFieldValues:function(form,fields) {
        for(var i in fields) {
            form.find('#'+i).val(fields[i]);
        }
    },
    setFieldValue:function(form,field,value) {
        form.find('#'+field).val(value);
    },
    getFieldValue:function(form,field) {
        return form.find('#'+field).val();
    },
    doUpdate:function(form) {
        return form.hasClass('update');
    },
    disableField:function(form,field) {
        form.find('#'+field).attr('disabled','disabled');
    },
    enableField:function(form,field) {
        form.find('#'+field).attr('disabled','');
    }	
}

/*Statis Dialogs*/
var StaticDialogs={
    createDialog:function(params) {
        if (!params) return false;
		
        var info={
            title:params.title
        }

        info.modal=(typeof params.modal != 'undefined')?params.modal:true;
		
        info.autoOpen=(typeof params.autoOpen != 'undefined')?params.autoOpen:false;
		
        info.resizable=(typeof params.resizable != 'undefined')?params.resizable:false;
        info.draggable=(typeof params.draggable != 'undefined')?params.draggable:false;
		
        info.width=(typeof params.width != 'undefined')?params.width:"auto";
        info.height=(typeof params.height != 'undefined')?params.height:"auto";
						
        if (typeof params.buttons != 'undefined') {
            info.buttons=params.buttons;
        }
		
        if (typeof params.onOpen != 'undefined') {
            info.open=params.onOpen;
        }
		
        if (typeof params.onClose != 'undefined') {
            info.close=params.onClose;
        }
										
        $("#"+params.id).dialog(info);
    },
    setOption:function(id,option,value) {
        $("#"+id).dialog('option',option,value);
    },
    setButtons:function(id,buttons) {
        $("#"+id).dialog("option","buttons",buttons);
    },
    open:function(id) {
        $("#"+id).dialog('open');
    },
    close:function(id) {
        $("#"+id).dialog('close');		
    },
    moveTo:function(id,position) {
        $("#"+id).dialog({
            position:[position.left,position.top]
        });
    },
    setDimensions:function(id,dimensions) {
        if (dimensions.height) $("#"+id).dialog("option","height",dimensions.height);
        if (dimensions.width)  $("#"+id).dialog("option","width",dimensions.width);
    },
    areYouSure:function(title,callback,message) {
        if (message) {
            $("#areyousure .message").text(message);
        }
		
        StaticDialogs.createDialog({
            id:'areyousure',
            title:title,
            autoOpen:true,
            buttons:[
            {
                text:"Cancel",
                click:function() {
                    $(this).dialog('close');
                }
            },
            {
                text:"Ok",
                click:function() {
                    callback();
                    $(this).dialog('close');
                }
            }
            ]
        });
    },
    edit:function(id,fields) {
        FormProcess.clearForm($('#'+id));	
        FormProcess.setFieldValues($('#'+id),fields);
		
        StaticDialogs.open(id);
    },
    defineDialog:function(id) {
        var element=$('#'+id);
				
        StaticDialogs.createDialog({
            id:id,
            title:element.find('.title').html(),
            modal:element.hasClass('modal'),
            autoOpen:element.hasClass('auto_open'),
            resizable:element.hasClass('resizable'),
            draggable:element.hasClass('draggable')
        });
    },
    removeBoxTitle: function(id) {
        $("#"+id).parent().addClass(id);
        $("."+id+" .ui-dialog-titlebar").remove();
    },
    removeBoxButtons: function(id) {
        $("#"+id).parent().addClass(id);
        $("."+id+" .ui-dialog-buttonpane").remove();
    },
    searchResults:function() {
        StaticDialogs.createDialog({
            id:'search_results_dialog',
            title:"Search Results",
            buttons:[
            {
                text:"Close",
                click:function() {
                    $(this).dialog('close');
                }
            }
            ]
        });
    },
    edit:function(id,fields) {
        FormProcess.clearForm($('#'+id));	
        FormProcess.setFieldValues($('#'+id),fields);
		
        StaticDialogs.open(id);
    }
}

/*Tabular*/
var Tabular={
    defineTabular:function(id,events) {
        var element=$('#'+id);

        Tabular.setClickEvent(element,events);
        Tabular.defineSort(element,events);
        Tabular.fixSearch(element,events);
        Tabular.fixPageArrows(element);
        Tabular.autoTriggerSearch(element);
    },
    setContent:function(element,content,events) {
        element.find('.tabular_body').html(content);
        element.attr('totalrows',element.find('.tabular_body .row.with_values').length);
		
        Tabular.defineTabular(element.attr('id'),events);
    },
    setClickEvent:function(element,events) {
        element.find('.tabular_body .row.with_values').unbind('click').click(function(){
            var url=$(this).attr('url');
						
            if (url) {
                System.redirect(url);
            } else {
                element.find('.tabular_body .row.with_values.selected').removeClass('selected');
				
                $(this).addClass('selected');
								
                $(this).find('td .element').addClass('colored');
								
                if (events && events.onClick) events.onClick();
            }
        });
    },
    defineSort:function(element,events) {		
        element.find('.tabular_header .row .column').unbind('click').click(function(){
            var type='';
            var body=$(this).parents().find('#'+element.attr('id')+' .tabular_body');
						
            if (type=$(this).attr('index')) {
                var rows=[];
				
                element.find('.tabular_body .row.with_values .column.'+type).each(function(){
                    rows.push({
                        id:$(this).parent().attr('id'),
                        value:$(this).attr('value')
                    });
                });
								
                if (rows.length > 0) {							
                    var do_sort=body.attr('sort');
							
                    rows.sort(function(element1,element2) {
                        element1.value=System.toInt(element1.value)?System.toInt(element1.value):element1.value
                        element2.value=System.toInt(element2.value)?System.toInt(element2.value):element2.value
						
                        if (do_sort == 'sort') {
                            return element1.value < element2.value;
                        } else {
                            return element1.value > element2.value;
                        }
                    });
					
                    body.attr('sort',(do_sort=='sort')?'reverse':'sort');
										
                    var new_html='';
					
                    body.each(function() {
                        var row=null;
						
                        for(var i in rows) {
                            row=$(this).find('#'+rows[i].id);
														
                            new_html+='<tr id="'+rows[i].id+'" class="'+row.attr('class')+'" '+(row.attr('url')?'url="'+row.attr('url')+'"':'')+'>';
                            new_html+=row.html();
                            new_html+='</tr>';
                        }
                    });
															
                    body.html(new_html);
																	
                    if (events && events.onSort) events.onSort(); 
					
                    Tabular.setClickEvent(element,events);
                }
            }
        });
    },
    fixPageArrows:function(element) {
        var maxrows=System.toInt(Tabular.getMaxRowsDisplay(element));
        var totalrows=System.toInt(Tabular.getTotalRows(element));
		
        if (maxrows < totalrows) {
            Tabular.filterRows(element,0,System.toInt(maxrows));
							
            element.find('.arrow').click(function() {
                var maxrows=System.toInt(Tabular.getMaxRowsDisplay(element));
                var totalrows=System.toInt(Tabular.getTotalRows(element));
								
                var from,to,startrow=element.attr('startrow');
					 																		
                if ($(this).hasClass('left')) {
                    from=System.toInt(startrow)-System.toInt(maxrows);
                    to=System.toInt(startrow);
                } else if ($(this).hasClass('right')) {
                    from=System.toInt(startrow)+System.toInt(maxrows);
                    to=System.toInt(from)+System.toInt(maxrows);	
                } else if ($(this).hasClass('first')) {
                    from=0;
                    to=maxrows;
                } else if ($(this).hasClass('last')) {
                    from=System.toInt(totalrows/maxrows)*maxrows;
                    to=totalrows;
                }
																
                if (from >= 0 && from < totalrows) {
                    Tabular.filterRows(element,from,to);
                }
            });
        }
		
        element.find('.pages_amount_views').change(function() {
            var maxrows=System.toInt($(this).val());
            var totalrows=System.toInt(Tabular.getTotalRows(element));
			
            Tabular.setMaxRowsDisplay(element,maxrows);			
            Tabular.filterRows(element,0,maxrows);
			
            element.find('.top_pages').html(Math.ceil(totalrows / maxrows));
        });
    },
    filterRows:function(element,from,to) {					
        element.find('.tabular_body .row.with_values').each(function() {
            var row=$(this).attr('row');
						
            if (row >= from && row < to) {
                $(this).removeClass('remove');
            } else {
                $(this).addClass('remove');
            }
        });
		
        var maxrows=System.toInt(Tabular.getMaxRowsDisplay(element));
        var totalrows=System.toInt(Tabular.getTotalRows(element));
		
        element.attr('startrow',from);
        element.find('.current').html((from+maxrows)/maxrows);
    },
    fixSearch:function(element,events) {
        element.find('.search .do_search').click(function() {
            var search_value=element.find('.search_value').val();
            var search_column=element.find('.search_column').val();
				
            if (search_value) {
                reg_search_value=new RegExp(search_value,"i");

                var found=false;

                element.find('.tabular_body .row.with_values .column.'+search_column).each(function(){
                    var row=$(this);
																			
                    if (row.attr('value') == search_value || row.attr('value').match(reg_search_value)) {
                        row.parent().removeClass('remove');
                        found=true;
                    } else {
                        row.parent().addClass('remove');
                    }
                });

                if (events && events.onSearchEnd) events.onSearchEnd(found);
            } else {
                var to=System.toInt(Tabular.getMaxRowsDisplay(element));
																	
                Tabular.filterRows(element,0,to);
            }
        });
    },
    autoTriggerSearch:function(element) {
        element.find('.search .do_search').click();
    },
    getMaxRowsDisplay:function(element) {
        return element.attr('maxrows');
    },
    setMaxRowsDisplay:function(element,maxrows) {
        element.attr('maxrows',maxrows);
    },
    getSearchValue:function(element) {
        return element.find('.search_value').val();
    },
    getSearchColumn:function(element) {
        return element.find('.search_column').val();
    },
    getTotalRows:function(element) {
        return element.attr('totalrows');
    },
    hasValues:function(element) {
        return element.find('.tabular_body .row.with_values').length > 0;		
    },
    getRowId:function(id) {
        return $('#'+id).find('.tabular_body .row.selected').attr('id');
    },
    getRowValue:function(row,column) {
        return row.find('.'+column).attr('value');
    },
    getSelectedRowValue:function(id,column) {
        return $('#'+id).find('.tabular_body .row.selected .'+column).attr('value');
    },
    setSelectedRowValue:function(id,column,value) {
        return $('#'+id).find('.tabular_body .row.selected .'+column).attr('value',value);
    },
    setSelectedRowLink:function(id,column,link) {
        return $('#'+id).find('.tabular_body .row.selected .'+column+' a').attr('href',link);
    },
    changeSelectedRowLabel:function(id,column,label) {
        $('#'+id).find('.tabular_body .row.selected .'+column+' span').html(label);
    }
}

var SearchResults={
    showResults:function(results_html) {
        $('#search_results_dialog .results').html(results_html);
        $('#seach_results_accordion').accordion({
            animated:(IS_TABLET)?false:true
        });
		
        $('#seach_results_accordion #pages .row.with_values').each(function(){
            $(this).attr('href',PAGE_CONTROLLER+'management?page_id='+$(this).attr('id'));
        });
		
        $('#seach_results_accordion #documents .row.with_values').each(function(){
            $(this).attr('href',DOCUMENT_CONTROLLER+'?document_id='+$(this).attr('id'));
        });
		
        $('#seach_results_accordion #projects .row.with_values').each(function(){
            $(this).attr('href',PROJECT_CONTROLLER+'management?project_id='+$(this).attr('id'));
        });
		
        $('#pages_list').bind('touchmove',function(event){
            event.preventDefault();
        });
		
        //new iScroll('pages_list');
					
        StaticDialogs.open('search_results_dialog');
    }
}

var Accordion={
    openAll:function(id) {
        $('#'+id+' h1').addClass('ui-state-active');
        $('#'+id+' h1').addClass('ui-corner-top');
        $('#'+id+' h1').removeClass('ui-corner-all');
		
        $('#'+id+' h1 span').remove();
		
        $('#'+id+' .ui-accordion-content').addClass('ui-accordion-content-active');
        $('#'+id+' .ui-accordion-content').show();
    },
    closeAll:function(id) {
        $('#'+id).accordion('option','active',false);
    }
}

var Draggable={
    defineDraggable:function(element) {
        $(element).draggable({
            revert:$(element).hasClass('ui_drag_revert'),
            revertDuration:0
        });
    }
}

var Resizable={
    defineResizable:function(element,events) {
        if (events) {
            $(element).resizable(events);
        } else {
            $(element).resizable();
        }
    }
}

/*pop up*/
var Popup={
    open:function(id) {
        $("#"+id).show();
    },
    close:function(id) {
        $("#"+id).hide();
    },
    closeAll:function(selector) {
        $(selector).hide();
    }
}

/*date process*/
var DateProcess={
    init:function() {
        return new Date();
    },
    getDate:function(date) {
        if (!date) {
            date = new Date();
        }
		
        var month=date.getMonth()+1;
		
        return date.getFullYear()+'-'+((month < 10)?0+''+month:month)+'-'+((date.getDate() < 10)?0+''+date.getDate():date.getDate());
    },
    getNextDate:function(date) {
        if (!date) {
            date = new Date();
        }
		
        date.setDate(date.getDate()+1);
		
        return this.getDate(date);
    },
    getPreviousDate:function(date,ago) {
        if (!date) {
            date = new Date();
        }
		
        date.setDate(date.getDate()-1);
						
        return this.getDate(date);
    },
    getPreviousDates:function(ago) {
        date = new Date();
		
        date.setDate(date.getDate()-ago);
		
        return this.getDate(date);
    },
    getDateTime:function(date) {
        if (!date) {
            date = new Date();
        }
		
        return this.getDate(date)+' '+date.getHours()+':'+((date.getMinutes() < 10)?0+''+date.getMinutes():date.getMinutes());
    }
}

/*error handler object*/
var ErrorHandler={
    setError:function(error_id) {
        this.error=error_id;
    },
    getError:function() {
        return this.error;
    },
    removeError:function() {
        this.error=null;
    },
    hasError:function() {
        return this.error
    }
}