<?php

use App\Controller\CreatePostController;
use Domain\Blog\UseCase\CreatePost;
use Symfony\Component\HttpFoundation\Request;

require __DIR__.'/vendor/autoload.php';

$request = Request::createFromGlobals();

$useCase = new CreatePost((new \Domain\Blog\Test\Adapters\PDOPostRepository()));
$controller = new CreatePostController($useCase);

$response = $controller->handleRequest($request);

$response->send();