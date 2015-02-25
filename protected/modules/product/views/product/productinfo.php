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
        <div style="height:300px;">
            <div style="float:left; height:300px">
                <?php echo "<img width=300 src='" . $model->media() . "' />"?>
            </div>
            <div style="float:left">
                <?php echo $info["articleAttributes"];?>
            </div>            
        </div>   
    </div>
    <div id="tabs-2">
    </div>
    <div id="tabs-3">
        <?php echo $info["originals"]?>
    </div>
    <div id="tabs-4">
        <?php print_r($out["antistixies"]); ?>
    </div>    
</div>
<script>
    jQuery( ".productinfotabs" ).tabs({collapsible: true});
</script>