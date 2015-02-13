<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <!-- blueprint CSS framework -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
        <!--[if lt IE 8]>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
        <![endif]-->

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery-ui.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.cleditor.css" />      
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.dataTables_themeroller.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.dataTables.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/dropmenu.css" />


        <script language="JavaScript" src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.js"></script>
        <script language="JavaScript" src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery-ui.js"></script>
        <script language="JavaScript" src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.cleditor.js"></script>
        <script language="JavaScript" src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/main.js"></script>
        <script language="JavaScript" src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.dataTables.min.js"></script> 
        <script language="JavaScript" src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/dropmenu.js"></script>



        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>

    <body>
        <div id="progressbar"></div>
        <div class="container" id="page">

            <div id="header">
                <div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
            </div><!-- header -->

            <ul id="nav-one" class="dropmenu"> 
                <li> 
                    <a href="<?php echo Yii::app()->params['mainurl']?>/site/index">Home</a> 
                </li> 
                <li> 
                    <a href="<?php echo Yii::app()->params['mainurl']?>/websites/websites">Websites</a> 
                </li> 
                <li> 
                    <a href="<?php echo Yii::app()->params['mainurl']?>/settings">Settings</a> 
                </li> 
                <li> 
                    <a href="<?php echo Yii::app()->params['mainurl']?>/users">Users</a> 
                </li>
            </ul>

            <?php if (isset($this->breadcrumbs)): ?>
                <?php
                $this->widget('zii.widgets.CBreadcrumbs', array(
                    'links' => $this->breadcrumbs,
                ));
                ?><!-- breadcrumbs -->
<?php endif ?>

<?php echo $content; ?>

            <div id="footer">
                Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
                All Rights Reserved.<br/>
<?php echo Yii::powered(); ?>
            </div><!-- footer -->

        </div><!-- page -->

    </body>
</html>