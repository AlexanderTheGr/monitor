<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div id="printarea" style="zoom: 80%">
    <div style="font-size: 20px">Κωδικός: <?php echo $model->fincode ?></div>
    <div style="font-size: 20px">Πελάτης: <?php echo $model->_customer_->customer_name ?></div>
    <?php require Yii::app()->params['widget'] . "datatable.php"; ?>
</div>

</div>
<style>

</style>
<script>
    setTimeout(function(){
        $("#orderitem_filter").remove();
        $("#orderitem_length").remove();
        $("#orderitem_info").remove();
        $("#orderitem_paginate").remove();
        
        $(".sright").css("textAlign","right");
        $(".scenter").css("textAlign","center");
        
        $('#printarea').printElement();
    },1000)
</script>