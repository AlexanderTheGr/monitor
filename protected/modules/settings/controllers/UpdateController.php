<?php

class UpdateController extends Controller {

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /*
      public function accessRules() {
      return array(
      array('allow', // allow admin user to perform 'admin' and 'delete' actions
      'users' => array('@'),
      ),
      array('deny', // deny all users
      'users' => array('*'),
      ),
      );
      }
     */

    public function actionIndex() {
        return;
        $sql[1][] = 'CREATE TABLE IF NOT EXISTS `agreementcategory` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `parent` int(11) NOT NULL,
                    `title` varchar(255) NOT NULL,
                    `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    `actioneer` int(11) NOT NULL,
                    `created` datetime NOT NULL,
                    `modified` datetime NOT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `parent` (`parent`),
                    KEY `actioneer` (`actioneer`)
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';
        
        $sql[1][] = 'ALTER TABLE `agreementcategory`
                    ADD CONSTRAINT `agreementcategory_ibfk_2` FOREIGN KEY (`actioneer`) REFERENCES `user` (`id`),
                    ADD CONSTRAINT `agreementcategory_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `agreementcategory` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;';
    
        $sql[1][] = 'CREATE TABLE IF NOT EXISTS `agreement2category` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `agreement_id` int(11) NOT NULL,
                `category_id` int(11) NOT NULL,
                PRIMARY KEY (`id`),
                KEY `agreement_id` (`agreement_id`),
                KEY `category_id` (`category_id`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';
        
        $sql[1][] = 'ALTER TABLE `agreement2category`
                ADD CONSTRAINT `agreement2category_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `agreementcategory` (`id`) ON DELETE CASCADE,
                ADD CONSTRAINT `agreement2category_ibfk_1` FOREIGN KEY (`agreement_id`) REFERENCES `agreement` (`id`) ON DELETE CASCADE;'; 
        
        $sql[1][] = "ALTER TABLE  `agreementcategory` CHANGE  `parent`  `parent` INT( 11 ) NULL";
        $sql[1][] = "ALTER TABLE  `cashflow_3`.`agreementcategory` DROP INDEX  `parent` , ADD INDEX  `parent` (  `parent` )";
        
        $sql[1][] = "INSERT agreementcategory set title = 'Root'";
                
    }

}

?>
