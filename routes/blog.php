<?php

//blog home
$app->get('/', $injectSideBar, function() use ($app){
	$articles = Model::factory('Article')->order_by_desc('timestamp')->find_many();
	return $app->render('blog_home.html', array('articles'=>$articles));
})->name('home');

//blog view
$app->get('/view/(:id)', $injectSideBar, function($id) use ($app){
	$article = Model::factory('Article')->find_one($id);
	if(!$article instanceof Article){
		$app->notFound();
	}
	return $app->render('blog_detail.html', array('article'=>$article));
})->name('view');

//category view
$app->get('/cat/:id', $injectSideBar, function($id) use ($app){
	$category = Model::factory('Category')->find_one($id);
	if (!$category instanceof Category){
		$app->notFound();
	}
	$articles = $category->articles()->find_many();
	$app->render('blog_category.html', array(
		'category'=>$category,
		'articles'=>$articles,
	));
})->name('category_view');

//about
$app->get('/about', $injectSideBar, function() use ($app){
	$app->render('blog_about.html');
})->name('about');

//contact
$app->get('/contact', $injectSideBar, function() use ($app){
	echo 'contact page';
})->name('contact');

//login
$app->map('/login', function() use ($app){
	if(isset($_SESSION['logged_in'])) $app->redirect($app->urlFor('home'));
	$req = $app->request();
	if($req->isPost()){
		$user = Model::factory('User')
			->where_equal('username', trim(strtolower($req->post('username'))))
			->find_one();
		if (!$user instanceof User){
			$app->flashNow('error', 'Username or Password invalid');
		}else{
			if($user->password !== md5($req->post('password'))){
				$app->flashNow('error', 'Username or Password invalid');
			}else{
				$app->flash('info', 'Your login was successfull');
				$_SESSION['logged_in'] = true;
				$app->redirect($app->urlFor('admin'));
			}
		}
	}
	return $app->render('admin_login.html');
})->via('GET', 'POST')->name('login');

//login ajax callback
$app->post('/login-callback', function() use ($app){
	$req = $app->request();
	sleep(5);
	if($req->isAjax()){
		$user = Model::factory('User')
			->where_equal('username', trim(strtolower($req->post('username'))))
			->find_one();
		if (!$user instanceof User){
			echo '0';
			return;
		}else{
			if($user->password !== md5($req->post('password'))){
				echo '0';
			}else{
				$_SESSION['logged_in'] = true;
				echo '1';
			}
		}
	}else echo 0;
})->name('login_callback');


//logout
$app->get('/logout', function() use ($app){
	session_destroy();
	unset($_SESSION['logged_in']);
	$app->redirect($app->urlFor('home'));
})->name('logout');