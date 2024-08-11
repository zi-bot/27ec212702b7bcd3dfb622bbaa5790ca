<?php

namespace App\MsAuth\Models;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "redis_messages")]
class RedisMessage
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer', unique: true, options: ['unsigned' => true])]
    private int $id;

    #[ORM\Column(type: 'string', unique: true, options: ['notnull' => true])]
    private string $channel;

    #[ORM\Column(type: Types::JSON)]
    private string $message;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTime $createdAt;

    public function __construct(string $channel, string $message)
    {
        $this->channel = $channel;
        $this->message = $message;
        $this->createdAt = new \DateTime();
    }

    // Getter and setter methods...
    public function getId(): int
    {
        return $this->id;
    }
    public function getChannel(): string
    {
        return $this->channel;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(): void
    {
        $this->createdAt = new \DateTime();
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
    public function setChannel(string $channel): void
    {
        $this->channel = $channel;
    }
}
