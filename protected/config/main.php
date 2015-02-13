<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Σύστημα Διαχείρισης Δεξιοτήτων',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
    ),
    'modules' => array(
        // uncomment the following to enable the Gii tool

        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => '123456',
           
        // If removed, Gii defaults to localhost only. Edit carefully to taste.
        ),
        'settings' => array(),
        'users' => array(),
        'webservice' => array(),
        'product' => array(),
        'customers' => array(),
        'orders' => array(),
    ),
    // application components
    'components' => array(
        'model',
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'urlFormat' => 'path',
            'rules' => array(
                'settings/<controller:\w+>/<id:\d+>' => 'settings/<controller>/view',
                'users/<controller:\w+>/<id:\d+>' => 'users/<controller>/view',
                'product/<controller:\w+>/<id:\d+>' => 'product/<controller>/view',
                'orders/<controller:\w+>/<id:\d+>' => 'orders/<controller>/edit',
                'settings/<controller:\w+>/<action:\w+>/<id:\d+>' => 'settings/<controller>/<action>',
                'users/<controller:\w+>/<action:\w+>/<id:\d+>' => 'users/<controller>/<action>',
                'customers/<controller:\w+>/<action:\w+>/<id:\d+>' => 'customers/<controller>/<action>',
                'product/<controller:\w+>/<action:\w+>/<id:\d+>' => 'product/<controller>/<action>',
                'orders/<controller:\w+>/<action:\w+>/<id:\d+>' => 'orders/<controller>/<action>',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        /*
          'db'=>array(
          'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
          ),
         * 
         */
        // uncomment the following to use a MySQL database
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=monitor',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '123456',
            'charset' => 'utf8',
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            // uncomment the following to show log messages on web pages
            /*
              array(
              'class'=>'CWebLogRoute',
              ),
             */
            ),
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'a.dimeas@gmail.com',
        'baseurl' => 'http://192.168.1.105/developing/monitor/',
        'moodleDb' => 'competen_moodle',
        'moodleTblPrefix' => 'mdl_',
        'mainurl' => 'http://192.168.1.105/developing/monitor/',
        'root' => 'C:\\xampp\\htdocs\\developing\\monitor\\',
        'widget' => 'C:\xampp\htdocs\developing\monitor\protected\widget\\'
    ),
);