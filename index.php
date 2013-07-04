<?php
session_cache_limiter(false);
session_start();

//autoload
require 'vendor/autoload.php';

//paris and idiorm
require 'vendor/jamie/idiorm/idiorm.php';
require 'vendor/jamie/paris/paris.php';

//models
require 'models/Article.php';
require 'models/User.php';
require 'models/Category.php';

//use namespace
use Slim\Slim;
use Slim\Extras\Views\Twig as TwigView;

//view configuration
TwigView::$twigExtensions = array('Twig_Extensions_Slim');

//orm configuration
ORM::configure('mysql:host=localhost;dbname=slim_blog');
ORM::configure('username', 'root');
ORM::configure('password', '');

//start slim
$app = new Slim(array(
	'view'=> new TwigView,
));

//auth checking middleware
$authCheck = function() use ($app){
	if(!isset($_SESSION['logged_in'])) $app->redirect($app->urlFor('login'));
};

//sidebar middleware
$injectSideBar = function() use ($app){
	$app->view()->appendData(array(
		'categories'=>Model::factory('Category')->find_many(),
		'recent_posts'=>Model::factory('Article')->order_by_desc('timestamp')->limit(5)->find_many(),
		'popular_posts'=>Model::factory('Article')->order_by_desc('view')->limit(5)->find_many(),
	));
};

//admin sidebar middleware
$adminSideBar = function() use ($app){
	$app->view()->appendData(array(
		'categories'=>Model::factory('Category')->find_many(),
	));
};

//before routing hook
$app->hook('slim.before', function() use ($app){
    $app->view()->appendData(array(
		'baseUrl'=>'/slim_blog', // base url of the website
		'static'=>'/slim_blog/assets', // static files location
		'curio'=>'/slim_blog/assets/curio', // curio theme location
		'bluenile'=>'/slim_blog/assets/bluenileadmin', // bluenileadmin theme location
		'logged_in'=>isset($_SESSION['logged_in']), // is user logged in ?
	));
});

//routes
require 'routes/blog.php';
require 'routes/admin.php';

//here we go!
$app->run();