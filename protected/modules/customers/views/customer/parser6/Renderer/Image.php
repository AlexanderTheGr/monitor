<?php

class Autoparts_Tecdoc_Block_Adminhtml_Brandmodel_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        if (file_exists(Mage::getBaseDir('media') . "/tecdoc/cars/" . $row->getId() . "/3.jpg"))
            return "<img widtd='200' style='width:200px' src='" . Mage::getBaseUrl('media') . "tecdoc/cars/" . $row->getId() . "/3.jpg'>";
    }

}

?>
