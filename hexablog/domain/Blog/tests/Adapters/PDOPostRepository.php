<?php


namespace Domain\Blog\Test\Adapters;


use Domain\Blog\Entity\Post;
use Domain\Blog\Port\PostRepositoryInterface;
use PDO;

class PDOPostRepository implements PostRepositoryInterface
{
    protected PDO $pdo;

    /**
     * PDOPostRepository constructor.
     * @param PDO $pdo
     */
    public function __construct()
    {
        $this->pdo = new PDO(
            'mysql:host=localhost;dbname=hexablog_test;charset=utf8', 'root', 'root',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }


    public function save(Post $post)
    {
        $query = $this->pdo->prepare(
            'INSERT INTO post SET 
                            title = :title,
                            content = :content,
                            uuid = :uuid,
                            published_at = :published_at'
        );
        $query->execute(
            [
                'title' => $post->title,
                'content' => $post->content,
                'uuid' => $post->uuid,
                'published_at' => $post->publishedAt ?
                    $post->publishedAt->format('Y-m-d H:i:s') :
                    null,
            ]
        );
    }

    public function findOne(string $uuid): ?Post
    {
        $query = $this->pdo->prepare(
            '
            SELECT p.* FROM post AS p WHERE p.uuid = :uuid
        '
        );
        $query->execute(
            [
                'uuid' => $uuid,
            ]
        );

        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            return null;
        }
        $post = new Post(
            $result['title'], $result['content'], $result['published_at'] ?
            new \DateTime($result['published_at']) : null, $result['uuid']
        );
        return $post;
    }
}