<?php
$articlesIds = $this->getArticlesIds();
//print_r($articlesIds);
?>
<h4><?php echo $brandModelType->getFullTitle()?></h4>
<div style="float:left; width:100%" id="accordion">
    <?php foreach ($categories as $category): ?>
        <?php $shownmain = 0 ?>
        <?php foreach ((array) $category->_childs_ as $subcategory): ?>
            <?php if ($tecdocdata[$subcategory->id]->articles_count > 0): ?>
                <?php //$shownmain += $tecdocdata[$subcategory->id]->articles_count?>
                <?php
                $subcategoriesarticles[$subcategory->id] = array();
                
                //$this->getArticlesIds2($tecdocdata[$subcategory->id]->articleIds);
                
                
                foreach ((array)$this->getArticlesIds2($tecdocdata[$subcategory->id]->articleIds) as $data) {
                    //if (in_array($articleId, $articlesIds)) {
                        //print_r($data);
                        $shownmain++;
                        $subcategoriesarticles[$subcategory->id][] = $data["article_id"];
                    //}
                }

                
                ?>
            <?php endif; ?>
        <?php endforeach; ?>   
        <?php if ($shownmain > 0): ?>
            <h3><?php echo $category->_categoryLangs_[$this->settings["language"]]->name ?> (<?php echo $shownmain; ?>)</h3>
            <div class="subcategoriesul">
                <!--h3><?php echo $category->_categoryLangs_[$this->settings["language"]]->name; ?></h3-->
                <ul>
                    <?php foreach ((array) $category->_childs_ as $subcategory): ?>
                        <?php if (count($subcategoriesarticles[$subcategory->id]) > 0): ?>
                            <li ref="<?php echo $subcategory->id ?>" class="subcategoriesli">
                                <?php echo $subcategory->_categoryLangs_[$this->settings["language"]]->name ?>
                                (<?php echo count($subcategoriesarticles[$subcategory->id]); ?>)
                                <div style="display:none;" class="articleIds<?php echo $subcategory->id ?>"><?php echo serialize($subcategoriesarticles[$subcategory->id]); ?></div>
                            </li>
                        <?php endif; ?>  
                    <?php endforeach; ?>
                </ul>                
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>


<script>
    /*
     jQuery(".categoriesli").click(function () {
     jQuery(".subcategories").html(jQuery(this).find("div.subcategoriesul").html());
     })    
     */
    /*
    jQuery(".categoriesli").click(function () {
        jQuery("div.subcategoriesul").hide();
        jQuery(this).find("div.subcategoriesul").show();
    })
    */
    $( "#accordion" ).accordion({collapsible: true,active: false});
    if (bind == 0) {
        bind = 1

        jQuery(".subcategoriesli").live("click", function () {
            var ref = jQuery(this).attr("ref");
            var articleIds = jQuery(".articleIds" + ref).text();


            var data = {};
            data.articleIds = articleIds;
            data.order = '<?php echo $order->id ?>'
            data.order = '<?php echo $order->id ?>'

            ProgressBar.displayProgressBar();
            $.post("<?php echo Yii::app()->request->baseUrl ?>/product/product/fororderajaxjson", data, function (result) {
                $fororderajaxjson = $("<div></div>")
                        .dialog({
                            autoOpen: false,
                            resizable: false,
                            draggable: false,
                            width: 1600,
                            height:900,
                            modal: true,
                            title: "Αποτελέσματα αναζήτησης",
                            close: function(ev, ui) { 
                                $(".productitem").val('')
                                $("#productitem").focus()
                            }
                        });
                $fororderajaxjson.html(result);
                $fororderajaxjson.dialog("open")
                $('.fororder').dataTable();
                $(".first").focus();
                ProgressBar.hideProgressBar();
            })

            /*
             oTable.product.fnDestroy();
             oTable.product = $('#product').dataTable({
             "sAjaxSource": 'http://192.168.1.105/developing/monitor/product/product/ajaxjson',
             "sServerMethod": 'POST',
             "sPaginationType": 'full_numbers',
             "fnServerParams": function (aoData) {
             aoData.push({"name": "articleIds", "value": articleIds});
             },
             "aLengthMenu": [[100, 150, 200, -1], [100, 150, 200, 'All']],
             "iDisplayLength": 100, "bPaginate": true,
             "bFilter": true, "fnInitComplete": function () {
             loadUi()
             }, "bAutoWidth": false, "bInfo": true, "bRetrieve": 'true', "aaSorting": [[0, 'desc']], "aoColumns": [null, null, null, null, null, null, null, null], "bProcessing": true, });
             */
        })

    }
</script>