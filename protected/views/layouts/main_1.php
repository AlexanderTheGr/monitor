<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />



        <?php foreach ((array) $this->css as $css): ?>
            <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/<?php echo $css ?>" />
        <?php endforeach; ?>
        <?php foreach ((array) $this->js as $js): ?>
            <script language="JavaScript" src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/<?php echo $js ?>"></script>
        <?php endforeach; ?>        

        <!--          
        <script language="JavaScript" src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.liveflex.Tree.js"></script>
        <script language="JavaScript" src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.multiselect.min.js"></script>
        <script language="JavaScript" src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.multiselect.filter.js"></script>
        -->


        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>


    <body >
        <div class="progressbar"></div>
        <div class="modal_window"></div>
        <div id="progressbar"></div>
        <div id="header">
            <div id="logo">
                <span style="font-size:40px; color:#ffffff;">Σύστημα Διαχείρισης Δεξιοτήτων</span>
                <div class="right">
                    <span class="login_email">Συνδεδεμένος ώς: <?php echo $this->getUserName() ?> | <a href="<?php echo Yii::app()->params['mainurl'] ?>site/logout"><?php echo $this->ls->translate("Έξοδος") ?></a> </span>

                </div>
            </div>


        </div><!-- header -->
        <?php //if (Yii::app()->user->id > 0): ?>
        <?php
        $user = User::model()->findByPk(Yii::app()->user->id);
        $this->ls = Langtranslater::create(array('page_key' => 'main', 'lang' => $this->lang, 'create' => true));
        ?>
        <div id="navmenu">
            <?php if ($user->role != '' AND $user->role == "admin"): ?>
                <ul id="nav-one" class="dropmenu"> 
                    <li> 
                        <a href="<?php echo Yii::app()->params['mainurl'] ?>site/index"><?php echo $this->ls->translate("Αρχική") ?></a> 
                    </li> 

                    <li> 
                        <a href="<?php echo Yii::app()->params['mainurl'] ?>users/user"><?php echo $this->ls->translate("Χρήστες") ?></a> 
                    </li> 
                    <li> 
                        <a href="<?php echo Yii::app()->params['mainurl'] ?>competency/organization"><?php echo $this->ls->translate("Εταιρίες") ?></a> 
                    </li>                     
                    <li> 
                        <a href="<?php echo Yii::app()->params['mainurl'] ?>competency/department"><?php echo $this->ls->translate("Θέσεις Εγρασίας") ?></a> 
                    </li>                      
                    <li>
                        <a href="<?php echo Yii::app()->params['mainurl'] ?>competency/competency"><?php echo $this->ls->translate("Δεξιότητες") ?></a>
                        <!--
                        <a><?php echo $this->ls->translate("Δεξιότητες") ?></a>
                        <ul>
                            <li><a href="<?php echo Yii::app()->params['mainurl'] ?>competency/family"><?php echo $this->ls->translate("Κατηγορίες") ?></a></li>
                            <li><a href="<?php echo Yii::app()->params['mainurl'] ?>competency/competency"><?php echo $this->ls->translate("Δεξιότητες") ?></a></li>                            
                        </ul>
                        -->
                    </li> 
                    <li> 
                        <a href="<?php echo Yii::app()->params['mainurl'] ?>settings"><?php echo $this->ls->translate("Ρυθμίσεις") ?></a> 
                    </li>                                    
                </ul>
            <?php elseif ($user->role != '' AND $user->role == "crm"): ?>
                <ul id="nav-one" class="dropmenu"> 
                    <li> 
                        <a href="<?php echo Yii::app()->params['mainurl'] ?>site/index"><?php echo $this->ls->translate("Αρχική") ?></a> 
                    </li> 

                    <li> 
                        <a href="<?php echo Yii::app()->params['mainurl'] ?>users/user"><?php echo $this->ls->translate("Χρήστες") ?></a> 
                    </li>                   
                    <li> 
                        <a href="<?php echo Yii::app()->params['mainurl'] ?>competency/department"><?php echo $this->ls->translate("Θέσεις Εγρασίας") ?></a> 
                    </li>                      
                    <li>
                        <a href="<?php echo Yii::app()->params['mainurl'] ?>competency/competency"><?php echo $this->ls->translate("Δεξιότητες") ?></a>
                        <!--
                        <a><?php echo $this->ls->translate("Δεξιότητες") ?></a>
                        <ul>
                            <li><a href="<?php echo Yii::app()->params['mainurl'] ?>competency/family"><?php echo $this->ls->translate("Κατηγορίες") ?></a></li>
                            <li><a href="<?php echo Yii::app()->params['mainurl'] ?>competency/competency"><?php echo $this->ls->translate("Δεξιότητες") ?></a></li>                            
                        </ul>
                        -->
                    </li> 
                    <li> 
                        <a href="<?php echo Yii::app()->params['mainurl'] ?>settings"><?php echo $this->ls->translate("Ρυθμίσεις") ?></a> 
                    </li>                                    
                </ul>         
            <?php elseif ($user->role != '' AND $user->role == "user"): ?>
                <ul id="nav-one" class="dropmenu"> 
                    <li> 
                        <a href="<?php echo Yii::app()->params['mainurl'] ?>site/index"><?php echo $this->ls->translate("Αρχική") ?></a> 
                    </li> 

                    <li> 
                        <a href="<?php echo Yii::app()->params['mainurl'] ?>users/user"><?php echo $this->ls->translate("Χρήστες") ?></a> 
                    </li> 

                    <li>
                        <a href="<?php echo Yii::app()->params['mainurl'] ?>/competency/competency/competencyuser"><?php echo $this->ls->translate("Δέλτα Δεξιοτήτων") ?></a>
                    </li> 
                    <li> 
                        <a href="<?php echo Yii::app()->params['mainurl'] ?>users/user/personal"><?php echo $this->ls->translate("Ρυθμίσεις") ?></a> 
                    </li>                                    
                </ul>            
            <?php endif; ?>
        </div>    
        <?php //endif; ?>
        <?php if (isset($this->breadcrumbs)): ?>
            <div id="breadcrumb">
                <?php
                $this->widget('zii.widgets.CBreadcrumbs', array(
                    'links' => $this->breadcrumbs,
                ));
                ?><!-- breadcrumbs -->                

            </div>

        <?php endif ?>

        <div class="container" id="page">     
            <div id="content">
                <?php echo $content; ?>
            </div>    


        </div><!-- page -->
        <script type="text/javascript" src="http://www.langtranslater.com/platform/js/libs/langtranslater/langtranslater.js"></script>
        <?php echo System::settings("google_analytics") ?>
        <?php echo System::settings("user_voice") ?>    
    </body>
</html>