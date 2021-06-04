<?php
declare(strict_types=1);

namespace App\Domain\Message;

use DateTime;
use JsonSerializable;

class Message implements JsonSerializable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $sender;

    /**
     * @var int  
     */
    private $receiver;

    /**
     * @var string
     */
    private $content;


    /**
     * @var int
     */
    private $timestamp;

    /**
     * @param int  $id
     * @param int    $sender
     * @param int    $receiver
     * @param string $text
     * @param int $timestamp 
     */
    public function __construct(int $id, int $sender, int $receiver, string $content, int $timestamp = null)
    {
        $this->id = $id;
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->content = $content;
        if (!$timestamp) {
            $date = new DateTime('now');
            $this->timestamp = $date->getTimestamp(); 
        }
        $this->timestamp = $timestamp;
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
    public function getSender(): int
    {
        return $this->sender;
    }

    /**
     * @return string
     */
    public function getReceiver(): int 
    {
        return $this->receiver;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->content;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int 
    {
        return $this->timestamp;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'sender' => $this->sender,
            'receiver' => $this->receiver,
            'content' => $this->content,
            'timestamp' => $this->timestamp
        ];
    }

    public function __toString()
    {
        return "id: {$this->id}, sender: {$this->sender}, receiver: {$this->receiver}, 
                content: {$this->content}, timestamp: {$this->timestamp}";
    }
}
