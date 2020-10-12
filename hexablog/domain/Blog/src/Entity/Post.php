<?php

namespace Domain\Blog\Entity;
use DateTimeInterface;

class Post
{
    public string $uuid;
    public string $title;
    public string $content;
    public ?\DateTimeInterface $publishedAt;

    /**
     * Post constructor...
     * @param string $title
     * @param string $content
     * @param ?DateTimeInterface $publishedAt
     * @param ?string $uuid
     */
    public function __construct(
        string $title = '',
        string $content = '',
        ?DateTimeInterface $publishedAt = null,
        ?string $uuid = null
    ) {
        $this->title = $title;
        $this->content = $content;
        $this->publishedAt = $publishedAt;
        $this->uuid = $uuid ?? uniqid();
    }




}