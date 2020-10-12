<?php


namespace Domain\Blog\UseCase;


use Assert\LazyAssertionException;
use Domain\Blog\Entity\Post;
use Domain\Blog\Exception\InvalidPostDataException;
use Domain\Blog\Port\PostRepositoryInterface;
use function Assert\lazy;

class CreatePost
{

    /**
     * @var PostRepositoryInterface
     */
    private PostRepositoryInterface  $postRepository;


    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }


    public function execute(array $postData): ?Post
    {

        $post = new Post(
            $postData['title'] ?? '',
            $postData['content'] ?? '',
            $postData['publishedAt'] ?? null
        );
        try {
            $this->validate($post);
            $this->postRepository->save($post);

            return $post;
        } catch (LazyAssertionException $e) {
            throw new InvalidPostDataException($e->getMessage());
        }


    }

    protected function validate(Post $post)
    {
        lazy()
            ->that($post->title)->notBlank()->minLength(5)
            ->that($post->content)->notBlank()->minLength(10)
            ->that($post->publishedAt)->nullOr()->isInstanceOf(\DateTimeInterface::class)
            ->verifyNow();;
    }
}