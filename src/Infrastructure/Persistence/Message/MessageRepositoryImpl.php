<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Message;

use App\Domain\Message\Message;
use App\Domain\Message\MessageRepository;
use App\Infrastructure\db\ChattyDB;
use App\Infrastructure\db\DatabaseFailureException;
use Psr\Log\LoggerInterface;
use SQLite3Result;
use SQLite3Stmt;
use Exception;

class MessageRepositoryImpl implements MessageRepository
{

    /**
     * @var ChattyDB
     */
    private $db;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * InMemoryMessageRepository constructor.
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->db = new ChattyDB();
        $this->db->enableExceptions(true);

        if (!$this->db) {
            throw new DatabaseFailureException($this->db->lastErrorMsg());
        }

        $sql_create_talbe =<<<EOF
            CREATE TABLE IF NOT EXISTS messages (
            id INT PRIMARY KEY  NOT NULL,
            sender INT NOT NULL,
            receiver INT NOT NULL,
            content TEXT NOT NULL,
            time_stamp INT NOT NULL
            )
        EOF;
        
        $res = $this->db->exec($sql_create_talbe);

        if (!$res) {
            $this->logger->error("[DB] Could not create table [messages].");
            throw new DatabaseFailureException();
        }
        
    }

    /**
     * {@inheritdoc}
     */
    public function findAllByUserId(int $id){
        $stmt = $this->db->prepare('SELECT * FROM messages 
                                    WHERE sender=:id OR receiver=:id 
                                    ORDER BY time_stamp ASC');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $this->prepareStatementExecute($stmt);
        return $this->extractMessageFromResult($result);
    }


    /**
     * {@inheritdoc}
     */
    public function findAllByIdAfterTimestamp(int $id, int $timestamp): array {
        $stmt = $this->db->prepare('SELECT * FROM messages 
                                    WHERE (sender=:id OR receiver=:id) AND time_stamp > :time_stamp 
                                    ORDER BY time_stamp ASC ');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $stmt->bindValue('time_stamp', $timestamp, SQLITE3_INTEGER);

        $result = $this->prepareStatementExecute($stmt);
        return $this->extractMessageFromResult($result);
    }
    


    /**
     * {@inheritdoc}
     */
    public function findMessage(int $id): array {
        $stmt = $this->db->prepare('SELECT * FROM messages
                                    WHERE id=:id');
        $stmt->bindValue(':id', $id);
        $result = $this->prepareStatementExecute($stmt);
        return $this->extractMessageFromResult($result)[0];
    }


    /**
     * {@inheritdoc}
     */
    public function addNewMessage(Message $message) {
        $stmt = $this->db->prepare('INSERT INTO messages (id, sender, receiver, content, time_stamp)
                                    VALUES (:id, :sender, :receiver, :content, :time_stamp)');
        $stmt->bindValue(':id', $message->getId(), SQLITE3_INTEGER);
        $stmt->bindValue(':sender', $message->getSender(), SQLITE3_INTEGER);
        $stmt->bindValue(':receiver', $message->getReceiver(), SQLITE3_INTEGER);
        $stmt->bindValue(':content', $message->getText(), SQLITE3_TEXT);
        $stmt->bindValue(':time_stamp', $message->getTimestamp(), SQLITE3_INTEGER);
        
        $result = $this->prepareStatementExecute($stmt);
        $result->finalize();
    }


    /**
     * {@inheritdoc}
     */
    public function deleteMessage(int $id) {
        $stmt = $this->db->prepare('DELETE FROM messages
                                    WHERE id = :id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $this->prepareStatementExecute($stmt);
        $result->finalize();
    }

    private function extractMessageFromResult(SQLite3Result $res): array{
        $messages = [];
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
           $messages[] = [
                'id' => $row['id'],
                'sender' => $row['sender'],
                'receiver' => $row['receiver'],
                'content' => $row['content'],
                'timestamp' => $row['time_stamp']
           ];
        }
        return $messages; 
    }

    private function prepareStatementExecute(SQLite3Stmt $stmt): SQLite3Result {
        $result = null;
        try {
            $result = $stmt->execute();
        }
        catch (Exception $e) {
            $errorMsg = $this->db->lastErrorMsg();
            $this->logger->error("Database failure: ${errorMsg}");
            throw new DatabaseFailureException();
        }

        if ($result) {
            return $result;
        }
        else {
            $this->logger->error("Prepare statement failed: ${stmt}");
            throw new DatabaseFailureException();
        }
    }
}
