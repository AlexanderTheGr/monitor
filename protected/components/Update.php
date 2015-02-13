<?php

class Update {

    function __construct() {
        
    }

    static function execute() {
        $ver = 1;
        $sqls = self::sqls();
        $sqlupdateindex = self::getSqlupdateindex();

        for ($i = 1; $i <= $ver; $i++) {
            if ($sqlupdateindex > $i)
                continue;
            
            foreach ((array) $sqls[$i] as $key => $sql) {
                if ($sql != "") {
                    Yii::app()->db->createCommand($sql)->execute();
                }
                $updated = true;
            }
            if ($updated)
                self::setSqlupdateindex($sqlupdateindex + 1);
        }
    }

    function getSqlupdateindex() {
        $sql = "Select * from settings where `key` = 'sqlupdateindex'";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        if ($data) {
            $model = Settings::model()->findByPk($data["id"]);
            return (int) $model->value;
        }
    }

    function setSqlupdateindex($value) {
        $sql = "Select * from settings where `key` = 'sqlupdateindex'";
        $data = Yii::app()->db->createCommand($sql)->queryRow();

        if ($data) {
            $model = Settings::model()->findByPk($data["id"]);
            $model->load();
            $model->value = $value;

            return $model->save();
        }
        return false;
    }

    private function createAttributeItem($attribute, $attributegroup, $eavmodel, $sort) {
        $eavmodel = self::loadEavmodel($eavmodel);
        $attributegroup = self::loadAttributeGroup($attributegroup);
        $attribute = self::loadAttribute($attribute);


        $sql = "Select id from attribute_items where attribute_id = '" . $attribute->id . "' AND eav_model = '" . $eavmodel->eav_model . "'";
        $data = Yii::app()->db->createCommand($sql)->queryRow();

        if ($data) {
            $attributeItem = AttributeItems::model()->findByPk($data["id"]);
        } else {
            $attributeItem = new AttributeItems();
        }

        if ($attribute->id > 0 AND $attributegroup->id > 0 AND $eavmodel->id > 0) {

            $sql = "UPDATE attribute_items set sort = sort+1 where sort >= '" . $sort . "' AND eav_model = '" . $eavmodel->eav_model . "'";
            Yii::app()->db->createCommand($sql)->execute();

            $attributeItem->attribute_id = $attribute->id;
            $attributeItem->group_id = $attributegroup->id;
            $attributeItem->eav_model = $eavmodel->eav_model;
            $attributeItem->required = $attribute->required;
            $attributeItem->visible = $attribute->visible;
            $attributeItem->unique = $attribute->unique;
            $attributeItem->select_data = $attribute->select_data;
            $attributeItem->css = $attribute->css;
            $attributeItem->title = $attribute->title;
            $attributeItem->sort = $sort;
            $attributeItem->save();
        }
    }

    private function loadEavmodel($eav_model) {
        $sql = "Select id from eavmodel where eav_model = '" . trim($eav_model) . "'";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        if ($data) {
            $model = Eavmodel::model()->findByPk($data["id"]);
            $model->load();
            return $model;
        }
    }

    private function loadAttributeGroup($identifier) {
        $sql = "Select id from attributegroups where identifier = '" . trim($identifier) . "'";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        if ($data) {
            $model = AttributeGroups::model()->findByPk($data["id"]);
            $model->load();
            return $model;
        }
    }

    private function loadAttribute($identifier) {
        $sql = "Select id from attributes where identifier = '" . trim($identifier) . "'";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        if ($data) {
            $model = Attributes::model()->findByPk($data["id"]);
            $model->load();
            return $model;
        }
    }

    private function updateEavmodel($attributes) {
        $sql = "Select id from eavmodel where eav_model = '" . trim($attributes["eav_model"]) . "'";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        if ($data) {
            $model = Eavmodel::model()->findByPk($data["id"]);
            $model->load();
            $attributes["id"] = $data["id"];
            $model->load();
            $model->attributes = $attributes;
            $model->save();
            return $model;
        } else {
            $model = new Eavmodel();
            $model->load();
            $model->attributes = $attributes;
            $model->save();
            return $model;
        }
    }

    private function updateAttribute($attributes = array()) {
        $sql = "Select id from attributes where identifier = '" . trim($attributes["identifier"]) . "'";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        if ($data) {
            $model = Attributes::model()->findByPk($data["id"]);
            $model->load();
            $attributes["id"] = $data["id"];
            $model->attributes = $attributes;
            $model->save();
            return $model;
        } else {
            $model = new Attributes();
            $model->load();
            $model->attributes = $attributes;
            $model->save();
            return $model;
        }
    }

    private function updateAttributeGroup($attributes) {
        $sql = "Select id from attributegroups where identifier = '" . trim($attributes["identifier"]) . "'";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        if ($data) {
            $model = AttributeGroups::model()->findByPk($data["id"]);
            $model->load();
            $attributes["id"] = $data["id"];
            $model->attributes = $attributes;
            $model->save();
            return $model;
        } else {
            $model = new AttributeGroups();
            $model->load();
            $model->attributes = $attributes;
            $model->save();
            return $model;
        }
    }

    private function sql() {
        $sqls[4] = "ALTER TABLE  `attributegroups` ADD  `identifier` VARCHAR( 100 ) NOT NULL AFTER  `id`";
        return $sqls;
    }

    private function eav() {
        return;

        $key = 6;


        $sqlupdateindex = self::getSqlupdateindex();
        if ($sqlupdateindex >= $key)
            return;

        self::setSqlupdateindex($key);

        $attribute = self::defaultAttribute('test', 'Test');
        self::updateAttribute($attribute);

        self::createAttributeItem('test', 'general', 'sinallasomenos', 14);
    }

    function defaultAttribute($identifier, $title) {
        return array(
            'type' => 'text',
            'identifier' => $identifier,
            'title' => $title,
            'required' => 0,
            'visible' => 1,
            'searchable' => 1,
            'unique' => 0,
            'validation' => 'none',
            'locked' => 0
        );
    }

    function dumpdb() {

        if (Yii::app()->params["subdomain"] != Yii::app()->params['dumpdb']["subdomain"])
            return

                    $dbhost = Yii::app()->params['dumpdb']["dbhost"];
        $dbuser = Yii::app()->params['dumpdb']["dbuser"];
        $dbpwd = Yii::app()->params['dumpdb']["dbpwd"];
        $dbname = Yii::app()->params['dumpdb']["dbname"];
        $dumpfile = Yii::app()->params['dumpdb']["dumpfile"];
        $scriptpath = Yii::app()->params['dumpdb']["scriptpath"];

        passthru("$scriptpath --opt --host=$dbhost --user=$dbuser --password=$dbpwd $dbname > $dumpfile");

        echo "$dumpfile ";
        passthru("tail -1 $dumpfile");
    }

    static function sqls() {

        $sqls[1][] = 'CREATE TABLE IF NOT EXISTS `agreementcategory` (
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

        $sqls[1][] = 'CREATE TABLE IF NOT EXISTS `agreement2category` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `agreement_id` int(11) NOT NULL,
                `category_id` int(11) NOT NULL,
                PRIMARY KEY (`id`),
                KEY `agreement_id` (`agreement_id`),
                KEY `category_id` (`category_id`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';

        $sqls[1][] = 'ALTER TABLE `agreementcategory`
                    ADD CONSTRAINT `agreementcategory_ibfk_2` FOREIGN KEY (`actioneer`) REFERENCES `user` (`id`),
                    ADD CONSTRAINT `agreementcategory_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `agreementcategory` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;';


        $sqls[1][] = 'ALTER TABLE `agreement2category`
                ADD CONSTRAINT `agreement2category_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `agreementcategory` (`id`) ON DELETE CASCADE,
                ADD CONSTRAINT `agreement2category_ibfk_1` FOREIGN KEY (`agreement_id`) REFERENCES `agreement` (`id`) ON DELETE CASCADE;';

        $sqls[1][] = "ALTER TABLE  `agreementcategory` CHANGE  `parent`  `parent` INT( 11 ) NULL";
        $sqls[1][] = "ALTER TABLE  `agreementcategory` DROP INDEX  `parent` , ADD INDEX  `parent` (  `parent` )";

        $sqls[1][] = "INSERT agreementcategory set title = 'Root', actioneer = 4";

        $sqls[1][] = "ALTER TABLE  `agreementcategory` ADD  `notes` TEXT NOT NULL AFTER  `title`";

        return $sqls;
    }

}

?>
