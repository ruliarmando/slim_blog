<?php

//admin home
$app->get('/admin', $authCheck, $adminSideBar, function() use ($app){
	$articles = Model::factory('Article')->order_by_desc('timestamp')->find_many();
	return $app->render('admin_home.html', array('articles'=>$articles));
})->name('admin');

//admin articles
$app->get('/admin/articles/:id', $authCheck, $adminSideBar, function($id) use ($app){
	$category = Model::factory('Category')->find_one($id);
	if(!$category instanceof Category){
		$app->notFound();
	}
	$count = $category->articles()->count();
	$page_count = ceil($count / 5);
	$articles = $category->articles()->order_by_desc('timestamp')->limit(5)->find_many();
	$app->render('admin_article.html', array(
		'category'=>$category,
		'articles'=>$articles,
		'count'=>$count,
		'page_count'=>$page_count,
	));
})->name('admin_article');

//admin add
$app->map('/admin/add', $authCheck, $adminSideBar, function() use ($app){
	$req = $app->request();
	if($req->isPost()){
		$article = Model::factory('Article')->create();
		$article->title = $req->post('title');
		$article->author = $req->post('author');
		$article->summary = $req->post('summary');
		$article->content = $req->post('content');
		$article->timestamp = date('Y-m-d H:i:s');
		$article->category_id = $req->post('cat_id');
		$article->save();
		$app->redirect($app->urlFor('admin'));
	}
	$categories = Model::factory('Category')->find_many();
	return $app->render('admin_input.html', array(
		'categories'=>$categories,
		'action_url'=>$app->urlFor('admin_add'),
		'action_name'=>'Add',
	));
})->via('GET', 'POST')->name('admin_add');

//admin edit
$app->map('/admin/edit/:id', $authCheck, $adminSideBar, function($id) use ($app){
	$article = Model::factory('Article')->find_one($id);
	if (!$article instanceof Article){
		$app->notFound();
	}
	$req = $app->request();
	if($req->isPost()){
		$article = Model::factory('Article')->find_one($id);
		$article->title = $req->post('title');
		$article->author = $req->post('author');
		$article->summary = $req->post('summary');
		$article->content = $req->post('content');
		$article->timestamp = date('Y-m-d H:i:s');
		$article->save();
		$app->redirect($app->urlFor('admin'));
	}
	return $app->render('admin_edit.html', array(
		'action_url'=>$app->urlFor('admin_edit', array('id'=>$id)),
		'action_name'=>'Edit',
		'article'=>$article,
	));
})->via('GET', 'POST')->name('admin_edit');

//admin delete
$app->get('/admin/delete/:id', $authCheck, function($id) use ($app){
	$article = Model::factory('Article')->find_one($id);
	if ($article instanceof Article){
		$article->delete();
	}
	$app->redirect($app->urlFor('admin'));
})->name('admin_delete');

//paging callback
$app->get('/paging_callback/:category_id/:page', function($category_id,$page) use ($app){
	$req = $app->request();
	if($req->isAjax()){
		$offset = 5 * (intval($page) - 1);
		$articles = Model::factory('Article')->where('category_id', intval($category_id))
			->order_by_desc('timestamp')
			->limit(5)
			->offset($offset)
			->find_many();
		$tmp = array();
		$tmp['offset'] = $offset + 1;
		foreach($articles as $a){
			$tmp['items'][] = array(
				'title'=>$a->title,
				'summary'=>$a->summary,
				'timestamp'=>strftime('%d-%m-%Y', strtotime($a->timestamp)),
				'content'=>$a->content,
				'author'=>$a->author,
			);
		}
		echo json_encode($tmp);
	}
})->name('paging');