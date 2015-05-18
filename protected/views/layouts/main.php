<!DOCTYPE html>
<?php $user = User::model()->findByPk(Yii::app()->user->id); ?>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <!-- Title and other stuffs -->
        <title><?php echo $this->pagename;?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="">



        <?php foreach ((array) $this->css as $css): ?>
            <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/style/<?php echo $css ?>" />
        <?php endforeach; ?>

        <?php foreach ((array) $this->js as $js): ?>
            <script language="JavaScript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/<?php echo $js ?>"></script>
        <?php endforeach; ?>   
        <!-- HTML5 Support for IE -->
        <!--[if lt IE 9]>
        <script src="js/html5shim.js"></script>
        <![endif]-->

        <!-- Favicon -->
        <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/img/favicon/favicon.png">
    </head>
    
    <body>
        <div style="width:250px; height:250px; background: transparent;" id="progressbar"><img height="250" width="250" src="<?php echo Yii::app()->request->baseUrl; ?>/images/loading1.gif"></div>
        
        <div class="modal_window"></div>
        <div style='background:#b2d234' class="navbar navbar-fixed-top">
            <div style='background:#b2d234' class="navbar-inner">
                <div class="container">
                    <!-- Menu button for smallar screens -->
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span>Menu</span>
                    </a>
                    <!-- Site name for smallar screens -->
                    <a href="index.html" class="brand hidden-desktop">MacBeath</a>

                    <!-- Navigation starts -->
                    <div class="nav-collapse collapse">        

                        <ul class="nav">  

                            <li class="dropdown dropdown-big">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="badge badge-important"><i class="icon-cloud-upload"></i></span> Upload to Cloud</a>
  
                            </li>

                            
                            <!--li class="dropdown dropdown-big">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="badge badge-success"><i class="icon-refresh"></i></span> Sync with Server</a>
                               
                                <ul class="dropdown-menu">
                                    <li>

                                        <p><span class="badge badge-success"><i class="icon-refresh icon-spin"></i></span> Syncing Members Lists to Server</p>
                                        <hr />
                                        <p><span class="badge badge-warning"><i class="icon-refresh icon-spin"></i></span> Syncing Bookmarks Lists to Cloud</p>

                                        <hr />

                                        <div class="drop-foot">
                                            <a href="#">View All</a>
                                        </div>

                                    </li>
                                </ul>
                            </li-->

                        </ul>

                        <!-- Search form -->
                        <!--form class="navbar-search pull-left">
                            <input type="text" class="search-query" placeholder="Search">
                        </form-->

                        <!-- Links -->
                        
                        <ul class="nav pull-right">
                            
                            
                            
                            <li class="dropdown pull-right">            
                                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                    <i class="icon-user"></i> Admin <b class="caret"></b>              
                                </a>

                                <!-- Dropdown menu -->
                                <ul class="dropdown-menu">
                                    <?php if ($user->role != '' AND $user->role == "admin"): ?>
                                    <li><a href="#"><i class="icon-user"></i> Profile</a></li>
                                    <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/settings"><i class="icon-cogs"></i> Settings</a></li>
                                    <?php endif; ?>
                                    
                                    <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/site/logout"><i class="icon-off"></i> Logout</a></li>
                                </ul>
                            </li>

                        </ul>
                    </div>

                </div>
            </div>
        </div>

        <!-- Header starts -->
        <header>
            <div class="container-fluid">
                <div class="row-fluid">

                    <!-- Logo section -->
                    <div class="span4">
                        <!-- Logo. -->
                        <div class="logo" style="text-aling:center">
                            <img style="height: 50px;" src="<?php echo Yii::app()->request->baseUrl?>/img/Partsbox logo final-02.png"/>
                        </div>
                        <!-- Logo ends -->
                    </div>



                </div>
            </div>
        </header>

        <!-- Header ends -->

        <!-- Main content starts -->

        <div class="content">
            <!-- Sidebar -->
            
            <?php if ($user->role != '' AND $user->role == "admin"): ?>
                <div class="sidebar">
                    <div class="sidebar-dropdown"><a href="#">Navigation</a></div>
                    <ul id="nav">
                        <!-- Main menu with font awesome icon -->
                        <li><a href="<?php echo Yii::app()->request->baseUrl; ?>"><i class="icon-home"></i><?php echo $this->translate("Αρχική"); ?></a></li>   
                        <li><a class="<?php echo $this->pagename == "Users" ? "open" : "" ?>" href="<?php echo Yii::app()->request->baseUrl; ?>/users/user"><i class="icon-user-md"></i><?php echo $this->translate("Χρήστες"); ?></a></li>  
                        <li><a class="<?php echo $this->pagename == "Products" ? "open" : "" ?>" href="<?php echo Yii::app()->request->baseUrl; ?>/product/product"><i class="icon-cogs"></i><?php echo $this->translate("Προϊόντα"); ?></a></li>  
                        <li><a class="<?php echo $this->pagename == "Πελάτες" ? "open" : "" ?>" href="<?php echo Yii::app()->request->baseUrl; ?>/customers/customer"><i class="icon-user"></i><?php echo $this->translate("Πελάτες"); ?></a></li> 
                        <li>
                            <a class="<?php echo $this->pagename == "Παραγγελίες" ? "open" : "" ?>" href="#"><i class="icon-shopping-cart"></i><?php echo $this->translate("Παραγγελίες"); ?></a>
                            <ul>
                                <li>
                                    <a class="<?php echo $this->pagename == "Παραγγελίες" ? "open" : "" ?>" href="<?php echo Yii::app()->request->baseUrl; ?>/orders/order"><?php echo $this->translate("Λίστα Παραγγελίων"); ?></a>
                                </li>
                                <li>
                                    <a class="<?php echo $this->pagename == "Παραγγελίες" ? "open" : "" ?>" href="<?php echo Yii::app()->request->baseUrl; ?>/orders/order/edit/"><?php echo $this->translate("Νέα Παραγγελία"); ?></a>
                                </li>
                                <li>
                                    <a class="<?php echo $this->pagename == "Παραγγελίες" ? "open" : "" ?>" href="<?php echo Yii::app()->request->baseUrl; ?>/orders/order/noorder/"><?php echo $this->translate("Νεα Προσφορά"); ?></a>
                                </li>                               
                            </ul>
                        </li> 
                    </ul>
                </div>
            <?php elseif ($user->role != '' AND $user->role == "user"): ?>
                <div class="sidebar">
                    <div class="sidebar-dropdown"><a href="#">Navigation</a></div>
                    <ul id="nav">
                        <!-- Main menu with font awesome icon -->
                        <li><a href="<?php echo Yii::app()->request->baseUrl; ?>"><i class="icon-home"></i><?php echo $this->translate("Αρχική"); ?></a></li>   
                        <li><a class="<?php echo $this->pagename == "Products" ? "open" : "" ?>" href="<?php echo Yii::app()->request->baseUrl; ?>/product/product"><i class="icon-cogs"></i><?php echo $this->translate("Προϊόντα"); ?></a></li>  
                        <li><a class="<?php echo $this->pagename == "Πελάτες" ? "open" : "" ?>" href="<?php echo Yii::app()->request->baseUrl; ?>/customers/customer"><i class="icon-user"></i><?php echo $this->translate("Πελάτες"); ?></a></li> 
                        <li>
                            <a class="<?php echo $this->pagename == "Παραγγελίες" ? "open" : "" ?>" href="#"><i class="icon-shopping-cart"></i><?php echo $this->translate("Παραγγελίες"); ?></a>
                            <ul>
                                <li>
                                    <a class="<?php echo $this->pagename == "Παραγγελίες" ? "open" : "" ?>" href="<?php echo Yii::app()->request->baseUrl; ?>/orders/order"><i class="icon-shopping-cart"></i><?php echo $this->translate("Παραγγελίες"); ?></a>
                                </li>
                                <li>
                                    <a class="<?php echo $this->pagename == "Παραγγελίες" ? "open" : "" ?>" href="<?php echo Yii::app()->request->baseUrl; ?>/orders/order/edit/"><i class="icon-shopping-cart"></i><?php echo $this->translate("Νέα"); ?></a>
                                </li>
                                <li>
                                    <a class="<?php echo $this->pagename == "Παραγγελίες" ? "open" : "" ?>" href="<?php echo Yii::app()->request->baseUrl; ?>/orders/order/noorder/"><i class="icon-shopping-cart"></i><?php echo $this->translate("Μόνο τιμές"); ?></a>
                                </li>                                
                            </ul>
                        </li> 
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Sidebar ends -->

            <!-- Main bar -->
            <div class="mainbar">

                <!-- Page heading -->
                <div class="page-head">
                    <h2 class="pull-left"><i class="icon-home"></i> <?php echo $this->pagename ?></h2>

                    <!-- Breadcrumb -->
                    <div class="bread-crumb pull-right">
                        <a href="index.html"><i class="icon-home"></i> Home</a> 
                        <!-- Divider -->
                        <span class="divider">/</span> 
                        <a href="#" class="bread-current"><?php echo $this->pagename ?></a>
                    </div>

                    <div class="clearfix"></div>

                </div>
                <!-- Page heading ends -->



                <!-- Matter -->

                <div class="matter" style="overflow: auto; height: 650px">
                    <div class="container-fluid">
                        <!-- Today status. jQuery Sparkline plugin used. -->
                        <div class="row-fluid">
                            <?php echo $content; ?>
                        </div>  
                    </div>
                </div>



                <!-- Matter ends -->

            </div>

            <!-- Mainbar ends -->	    	
            <div class="clearfix"></div>

        </div>
        <!-- Content ends -->

        <!-- Footer starts -->
        <footer style='background:#b2d234' >
            <div class="container-fluid">
                <div class="row-fluid">
                    <div class="span12">
                        <!-- Copyright info -->
                        <p class="copy">Copyright &copy; 2015 | <a href="#">Parts Box</a> </p>
                        <p class="copy">Design and Develop by <a href="http://www.fastwebltd.com">FastWeb</a></p>
                    </div>
                </div>
            </div>
        </footer> 	

        <!-- Footer ends -->

        <!-- Scroll to top -->
        <span class="totop"><a href="#"><i class="icon-chevron-up"></i></a></span> 



    </body>
</html>