<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'IMDB Console Application',
    'commandMap'=> array(
	    'top' => array(
		  'class'=>'ext.imdb.ETopCommand',
	    ),
    ),
	// preloading 'log' component
	'preload'=>array('log'),

	'import'=>array(
		'application.models.*',
		'application.components.*',
	),
	// application components
	'components'=>array(
		'db'=>array(
			'connectionString' => 'mysql:host=;dbname=',
			'emulatePrepare' => true,
			'username' => '',
			'password' => '',
			'charset' => 'utf8',
		),
		'imdbs'=>array(
			'class'=>'IMDB',
		),
		'imdbh'=>array(
			'class'=>'IMDB_HTML',
		),
		'curl' => array(
			'class' => 'ext.curl.Curl',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
);