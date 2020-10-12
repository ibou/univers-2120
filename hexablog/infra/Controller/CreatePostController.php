<?php


namespace App\Controller;


use Domain\Blog\UseCase\CreatePost;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreatePostController
{

    protected CreatePost $useCase;

    /**
     * CreatePostController constructor.
     * @param CreatePost $useCase
     */
    public function __construct(CreatePost $useCase)
    {
        $this->useCase = $useCase;
    }


    public function handleRequest(Request $request)
    {
        if ($request->isMethod('GET')) {
            //Monter the form
            ob_start();
            include __DIR__.'/../templates/form.html.php';

            return new Response(ob_get_clean());
        }
        if ($request->isMethod('POST')) {


            $post = $this->useCase->execute(
                [

                    'title' => $request->request->get('title', ''),
                    'content' => $request->request->get('content', ''),
                    'uuid' => uniqid(),
                    'publishedAt' => $request->request->has('published') ?
                        new \DateTime() :
                        null,
                ]
            );
        }
        return new Response("<h1>{$post->title}</h1>");
    }
}