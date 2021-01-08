<?php


namespace App\Message;


use DateTimeInterface;

class MailNotification
{
    private $id;
    private $title;
    private $description;
    private $created_at;

    public function __construct(int $id, string $title, string $description, DateTimeInterface $created_at)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->created_at = $created_at;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->created_at;
    }
}
