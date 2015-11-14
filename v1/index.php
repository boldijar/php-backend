<?php
require_once 'vendor/autoload.php';
include 'post.php';
include 'database.php';
include 'error.php';
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
$app = new Silex\Application();
$database = new Database;

$app->get('/add', function ( Application $app, Request $request) use ($app) {
		$details= $request->query->get('details');
		$fullImage=$request->query->get('fullImage');
		$category=$request->query->get('category');
		global $database;
		$database->addPost($details,$fullImage,$category);
		return "yep";
});

$app->get('/posts', function ( Application $app, Request $request) use ($app) {

		$limit= $request->query->get('limit');
		$offset=$request->query->get('offset');
		$beforeDate=$request->query->get('beforeDate');
		$category=$request->query->get('category');


		if( is_null($limit) || is_null($offset))
		{

			$error = new Error;
			$error->message = "You must have limit and offset parameter.";
			return json_encode($error);

		}
		global $database;
		$results = $database->getPosts($offset,$limit,$category,$beforeDate);
		$postResponse =new PostResponse;
		$postResponse->posts=$results;
		$postResponse->count=count($results);
		return $app->json($postResponse, 200);

});

$app->run();

?>