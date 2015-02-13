<?php

class HtmlWidget {

    static function accordioner($data = array(), $attributes = array(), $useajax = true) {
        $ls = Langtranslater::getSingleton();
        $attrArr = array();
        $j = rand();
        foreach ((array) $attributes as $attribute => $value) {
            $attrArr[] = $attribute . '="' . $value . '"';
        }
        $html = '<div class="accordion" ' . implode(" ", $attrArr) . '>';

        $i = $j;
        foreach ((array) $data as $title => $req) {
            $html .= '<h3>' . self::translate($title) . '</h3>';
            $html .= '<div id="accordion-' . $i++ . '">' . (!$useajax ? $req : "") . '</div>';
        }
        $html .= '</div>';
        $html .= "<script>
            var data = {}
            ";
        $i = $j;
        if ($useajax)
            foreach ((array) $data as $title => $req) {
                $html .="$.post('" . Yii::app()->params['mainurl'] . $req . "',data,function(result){
                    $('#accordion-" . $i++ . "').html(result)
                    loadUi();
            })
            ";
            }
        $html .= "</script>";
        return $html;
    }

    static function tabber($data = array(), $attributes = array(), $useajax = true) {
        //$ls = Langtranslater::getSingleton();
        $attrArr = array();
        $j = rand();
        foreach ((array) $attributes as $attribute => $value) {
            $attrArr[] = $attribute . '="' . $value . '"';
        }
        $html = '<div class="tabs" ' . implode(" ", $attrArr) . '>';
        $html .= '<ul>';
        $i = $j;
        foreach ((array) $data as $title => $req) {
            $html .= '<li><a href="#tabs-' . $i++ . '">' . self::translate($title) . '</a></li>';
        }
        $html .= '</ul>';
        $i = $j;
        foreach ((array) $data as $title => $req) {
            $html .= '<div id="tabs-' . $i++ . '">' . (!$useajax ? $req : "") . '</div>';
        }
        $html .= '</div>';
        $html .= "<script>
            var data = {}
            ";
        $i = $j;
        if ($useajax)
            foreach ((array) $data as $title => $req) {
                $html .="$.post('" . Yii::app()->params['mainurl'] . $req . "',data,function(result){
                    $('#tabs-" . $i++ . "').html(result)
                    loadUi();
            })
            ";
            }
        $html .= "</script>";
        return $html;
    }

    function curlIt($theURL, $postdata = "") {

        //echo("In curl:<br>".$theURL." POST DATA:".$postdata."<br>");
        $ch = curl_init($theURL);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        //        decho("OUT curl".$result);
        return $result;
    }

    static function translate($key) {
        return $key;
    }

    static function dataTable($columns, $fieldAttrs = array(), $attributes = array(), $params = array(), $ajaxurls = array(), $sfields = false, $buttons, $callback = false) {


        $attrArr = array();
        //$ls = Langtranslater::create(array('page_key' => 'main', 'lang' => 'el', 'create' => true));


        foreach ((array) $attributes as $attribute => $value) {
            $attrArr[] = $attribute . '="' . $value . '"';
        }
        foreach ((array) $fieldAttrs as $attribute => $value) {
            $fieldAttrArr[] = $attribute . '="' . $value . '"';
        }




        if ($buttons['bAddnewpos'] == "'top-left'")
            $html .= "<button class='btn btn-primary addnew left' id='addnew_" . $attributes['id'] . "'>" . self::translate($buttons['labels']["add_new"]) . "</button>";
        if ($buttons['bAddnewpos'] == "'top-right'")
            $html .= "<button class='btn btn-primary addnew right' id='addnew_" . $attributes['id'] . "'>" . self::translate($buttons['labels']["add_new"]) . "</button>";



        $html .= '<div class="datatable"  style="width:100%"><table cellpadding="0" cellspacing="0" width=100% border="0" ' . implode(" ", $attrArr) . '><thead>';
        if ($sfields) {
            $html .= '<tr class="datasearch_' . $attributes['id'] . '">';
            foreach ((array) $columns as $w => $column) {
                //echo $column["label"]." ".$column["type"];
                switch ($column["type"]) {
                    case "text":
                        $html .= '<th><input style="' . ($column["label"] == '' ? "display:none" : "") . '" size=10 type="text" name="search_' . $column["label"] . '"  class="search_init" /></th>';
                        break;
                    case "none":
                        $html .= '<th></th>';
                        break;
                    case "select":
                        $html .= "<th>" . CHtml::dropDownList('search_' . $column["label"], "", $column["select_data"], array('empty' => '', 'class' => 'search_init')) . "</th>";
                        break;
                    default:
                        $html .= '<th></th>';
                        break;
                }
            }
            $html .='</tr>';
        }
        $html .= '<tr>';
        foreach ((array) $columns as $w => $column) {
            $html .= '<th>' . $column["label"] . '</th>';
        }
        $html .='</tr>';
        $html .='</thead>';


        $html .= '<tbody></tbody>';

        $html .= '<tfoot><tr>';
        foreach ((array) $columns as $w => $column) {
            $html .= $column["sum"] ? '<th class="' . $attributes['id'] . '_sum_' . $w . '"></th>' : '<th></th>';
            if ($column["sum"])
                $sum = true;
        }
        $html .= '</tr></tfoot>';

        $html .= '</table></div><BR><BR>';


        if ($buttons['bAddnewpos'] == "'bottom-left'")
            $html .= "<button class='btn btn-primary addnew left' id='addnew_" . $attributes['id'] . "'>" . self::translate($buttons['labels']["add_new"]) . "</button>";
        if ($buttons['bAddnewpos'] == "'bottom-right'")
            $html .= "<button class='btn btn-primary addnew right' id='addnew_" . $attributes['id'] . "'>" . self::translate($buttons['labels']["add_new"]) . "</button>";

        $dataTableLang .= '"oLanguage":{
            
        

                    "sProcessing":   "Επεξεργασία...",
                    "sLengthMenu":   "Προβολή _MENU_ αποτελέσματα",
                    "sZeroRecords":  "Δεν βρέθηκαν αποτελέσματα",
                    "sInfo":         "Προβολή _START_ μέχρι _END_ από _TOTAL_ αποτελέσματα",
                    "sInfoEmpty":    "Προβολή 0 μέχρι 0 από 0 αποτελέμστα",
                    "sInfoFiltered": "(filtered from _MAX_ total entries)",
                    "sInfoPostFix":  "",
                    "sSearch":       "Αναζήτηση:",
                    "sUrl":          "",
                    


                    "oPaginate": {
                        "sFirst":    "Πρώτο",
                        "sPrevious": "Προηγούμενο",
                        "sNext":     "Επόμενο",
                        "sLast":     "Τελευταίο"
                    }
                }';

        $html .= '<script>                                                                                                                                                                                                     
         

         
        $(document).ready(function () {

            var sum = {}
            var _aiDisplay = [];
            if ( oTable.' . $attributes['id'] . ')
            oTable.' . $attributes['id'] . '  = $(\'#' . $attributes['id'] . '\').dataTable( {bRetrieve:true})
            else {    
            oTable.' . $attributes['id'] . '  = $(\'#' . $attributes['id'] . '\').dataTable( {';
        foreach ((array) $params as $key => $value) {
            $html .= '"' . $key . '": ' . $value . ',';
        }

        if ($buttons["showExport"])
            $html .= '"sDom": \'T<"clear">lfrtip\',
        "oTableTools": {
            "sSwfPath": "' . Yii::app()->request->baseUrl . '/swf/copy_csv_xls_pdf.swf"
        },';
        if ($sum)
            $html .= '"fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {

            var iTotalMarket = 0;
            sum = {}
            _aiDisplay = aiDisplay;
            for (var i = 0; i < aiDisplay.length; i++) {
                for (k in aaData[aiDisplay[i]]) {
                    if (!sum[k]) sum[k] = 0;
                    var number = aaData[aiDisplay[i]][k] * 1;
                    sum[k] += number;
                }
            }
            for(i in  sum) {
                var final = sum[i]
                $(".' . $attributes['id'] . '_sum_"+i).text(final.toFixed(2));
            }
            
        },';

        $html .= self::translate($dataTableLang);
        $html .= '});   
                    }

            $(".datasearch_' . $attributes['id'] . ' .search_init").change( function () {
                /* Filter on the column (the index) of this element */
                sum = {}
                for (var i = 0; i < _aiDisplay.length; i++) {
                    $(".' . $attributes['id'] . '_sum_"+i).text(0);
                }


                oTable.' . $attributes['id'] . '.fnFilter( this.value, $(".datasearch_' . $attributes['id'] . ' .search_init").index(this) );
            });
            
            if (aa.' . $attributes['id'] . ' == undefined) {         
                $dialog.' . $attributes['id'] . ' = $("<div></div>")
                .dialog({
                    autoOpen: false,
                    resizable:false,
                    draggable:false,
                    width:"auto",
                    modal: true
                });        
                aa.' . $attributes['id'] . ' = 1;
                  
                $("#addnew_' . $attributes['id'] . '").live("click", function()  { 
                    popupedit.' . $attributes['id'] . '(0);
                })
                $(".' . $attributes['id'] . '").live("click", function()  {          
                    popupedit.' . $attributes['id'] . '(getId($(this).attr("id")));
                })    
                $(".' . $attributes['id'] . 'page").live("click", function(e)  {
                    
                    if (e.ctrlKey) window.open("' . $ajaxurls['ajaxpage'] . '"+getId($(this).attr("id")))
                    else location.href = "' . $ajaxurls['ajaxpage'] . '"+getId($(this).attr("id"))
                })  
                popupedit.' . $attributes['id'] . ' = function(id,extra) {
                    var data = {id:id}
                    data.popupeditextra = popupeditextra;
                    var fielddata = {}
                    
                    $.post("' . $ajaxurls['ajaxformtitle'] . '",data,function(result){
                        var title = result;  
                        $dialog.' . $attributes['id'] . '.html("");
                        ProgressBar.displayProgressBar();    
                        $.post("' . $ajaxurls['ajaxform'] . '",data,function(result){
                            ProgressBar.hideProgressBar();
                            $dialog.' . $attributes['id'] . '.dialog({
                                title:title,
                                buttons: [';
        if ($buttons["showSave"])
            $html .= '  {
                text: "' . $buttons['labels']["save"] . '",
                "class": "btn btn-success",
                click: function() {
                    savemodel(id,"' . ($attributes['tableName'] != "" ? $attributes['tableName'] : $attributes['id']) . '","' . $ajaxurls['ajaxformsave'] . '",callback.' . $attributes['id'] . ') 
                }
            },';
        if ($buttons["showDelete"])
            $html .= '  {
                text: "' . $buttons['labels']["delete"] . '",
                "class": "btn btn-danger",
                click: function() {
                    deletemodel(id,"' . $ajaxurls['ajaxdelete'] . '",callback.' . $attributes['id'] . ')                }
            },';        
        if ($buttons["showCancel"])
            $html .= '  {
                text: "' . $buttons['labels']["cancel"] . '",
                "class": "btn ",
                click: function() {
                    $dialog.' . $attributes['id'] . '.dialog( "close" );               }
            },';         
        
        
        $html .= ']
            
                            })
                            $dialog.' . $attributes['id'] . '.html(result);
                            loadUi();    
                            $dialog.' . $attributes['id'] . '.dialog("close");
                            $dialog.' . $attributes['id'] . '.dialog("open");
                        })
                    })   
                }
            }

            callback.' . $attributes['id'] . ' = function() {
                    $dialog.' . $attributes['id'] . '.dialog( "close" );
                    oTable.' . $attributes['id'] . '.fnDestroy();
                    oTable.' . $attributes['id'] . '  = $(\'#' . $attributes['id'] . '\').dataTable( {';
        foreach ($params as $key => $value) {
            $html .= '"' . $key . '": ' . $value . ',';
        }
        $html .= '});
            ' . $callback . '
            }
            
        })
        </script>
        ';
        return $html;
    }

    static function boxAccordion($title, $content, $attr, $ls) {
        $html = '<div class="' . $attr["class"] . '">
            <h3><a href="#">' . self::translate($title) . '</a></h3>
            <div>
            <div>';
        if ($attr["edit"])
            $html .='<a class="sinalasomenosviewedit" action="' . $title . '" href="#">Edit</a>';
        $html .= '</div>
            ' . $content . '</div>
            </div>';
        return $html;
    }

    private function getFileContents($req) {
        return file_get_contents(Yii::app()->request->baseUrl . "/index.php/" . $req);
    }

}

?>
