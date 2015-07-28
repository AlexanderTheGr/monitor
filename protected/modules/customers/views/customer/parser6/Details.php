<?php

/**
 * Alexander Dimeas
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to a.dimeas@gmail.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category   Autoparts
 * @package    Autoparts_Tecdoc
 * @copyright  Copyright (c) 2014 Alexander Dimeas (http://www.alexanderdimeas.gr)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Alexander Dimeas <a.dimeas@gmail.com>
 */
class Autoparts_Tecdoc_Block_Adminhtml_Brand_Details extends Mage_Adminhtml_Block_Widget_Container {

    public function __construct() {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'tecdoc';
        $this->_controller = 'adminhtml_brandmodel';
        $this->setTemplate('widget/view/container.phtml');
    }

    public function getViewHtml() {
        $brand = Mage::getModel('tecdoc/brand')->load($this->getRequest()->getParam('id'));
        $html .= "<ul>";
        if (file_exists(Mage::getBaseDir('media') . "/tecdoc/manufacturers/" . $brand->getId() . ".jpg"))
            $html .= "<div><img style='width:200px' src='" . Mage::getBaseUrl('media') . "/tecdoc/manufacturers/" . $brand->getId() . ".jpg'></div>";
        return $html;
    }

    public function getHeaderText() {
        //return 'Edit Brand Model';
    }

}

?>