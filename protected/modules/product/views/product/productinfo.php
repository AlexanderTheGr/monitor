<?php
//print_r($info);
?>

<div class="productinfotabs">
    <ul>
        <li><a href="#tabs-1">Χαρακτηριστικά</a></li>
        <li><a href="#tabs-2">Εφαρμογές</a></li>
        <li><a href="#tabs-3">Γνήσια</a></li>
        <li><a href="#tabs-4">Αντιστοιχίες</a></li>
    </ul>
    <div id="tabs-1">
        <div style="height:450px;">
            <div style="float:left; height:450px">
                <?php echo "<img width=300 src='" . $model->media() . "' />"?>
            </div>
            <div style="float:left">
                <?php echo $info["articleAttributes"];?>
            </div>            
        </div>   
    </div>
    <div id="tabs-2">
        <ul>
        <?php foreach($info["efarmoges"]  as $efarmogi):?>
            <?php 
            $m = $this->model("BrandModelType",$efarmogi);
            ?>
            <?php if (trim($m->getFullTitle()) != ""):?>
            <li><?php echo $m->getFullTitle();?></li>
            <?php endif;?>
        <?php endforeach;?>
        </ul>    
        
    </div>
    <div id="tabs-3">
        <?php echo $info["originals"]?>
    </div>
    <div id="tabs-4">
        <table class="display dataTable">
            <tr>
                <th>Προγραφή</th>
                <th>Κωδικός</th>
            </tr>
            <?php foreach($info["antistixies"] as $product):?>
            <?php $product = json_decode($product["flat_data"]);?>  
            <tr>
                <td><?php echo $product->item_name;?></td>
                <td><?php echo $product->item_code;?></td>
            </tr>
            <?php endforeach;?>            
        </table>

    </div>    
</div>
<script>
    jQuery( ".productinfotabs" ).tabs({collapsible: true});
</script>