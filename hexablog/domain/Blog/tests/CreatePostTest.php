<?php

use Domain\Blog\Exception\InvalidPostDataException;
use Domain\Blog\Test\Adapters\InMemoryPostRepository;
use Domain\Blog\Test\Adapters\PDOPostRepository;
use PHPUnit\Framework as Assert;
use Domain\Blog\UseCase\CreatePost;
use Domain\Blog\Entity\Post;


it(
    "should create a post",
    function () {

        //$repository = new InMemoryPostRepository;
        $repository = new PDOPostRepository;

        $useCase = new CreatePost($repository);

        $post = $useCase->execute(
            [
                'title' => 'Mon titre',
                'content' => 'Mon contenu test',
                'publishedAt' => new \DateTime('2020-10-11T23:51:28'),
            ]
        );
        Assert\assertInstanceOf(Post::class, $post);
        Assert\assertEquals($post, $repository->findOne($post->uuid));
    }
);

it(
    "should throw a InvalidPostDataException if bad data is provided",
    function ($postData) {

        $repository = new InMemoryPostRepository;

        $useCase = new CreatePost($repository);

        $post = $useCase->execute($postData);
    }
)->with(
    [
        [
            [
                'title' => 'Mon contenu test',
                'publishedAt' => new \DateTime('2020-10-11T23:51:28'),
            ],
        ],
        [
            [
                'content' => 'Mon contenu test',
                'publishedAt' => new \DateTime('2020-10-11T23:51:28'),
            ],
        ],
        [
            [
                'content' => 'Mon contenu test',
                's' => new \DateTime('2020-10-11T23:51:28'),
            ],
        ],
        [[]],
    ]
)->throws(InvalidPostDataException::class);

beforeEach(
    function () {
    }
);