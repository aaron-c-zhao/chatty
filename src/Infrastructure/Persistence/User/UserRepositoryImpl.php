<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;
use App\Infrastructure\db\ChattyDB;
use App\Infrastructure\db\DatabaseFailureException;
use SQLite3Result;
use SQLite3Stmt;
use Exception;

class UserRepositoryImpl implements UserRepository
{

    /**
     * @var ChattyDB
     */
    private $db;

    /**
     * InMemoryUserRepository constructor.
     *
     * @param array|null $users
     */
    public function __construct()
    {
        $this->db = new ChattyDB();
        $this->db->enableExceptions(true);

        if (!$this->db) {
            throw new DatabaseFailureException($this->db->lastErrorMsg());
        }

        $sql_create_talbe =<<<EOF
            CREATE TABLE IF NOT EXISTS users (
            id INT PRIMARY KEY  NOT NULL,
            user_name TEXT NOT NULL,
            first_name TEXT,
            last_name TEXT
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
    public function findAll(): array
    {
        $result = $this->db->query('SELECT * FROM users');
        return $this->extractUsersFromResult($result);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserOfId(int $id): array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id=:id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        
        $result = $this->prepareStatementExecute($stmt);
        return $this->extractUsersFromResult($result)[0];
    }


    /**
     * {@inheritdoc}
     */
    public function addNewUser(User $user) {
        $stmt = $this->db->prepare('INSERT INTO users (id, user_name, first_name, last_name)
                                    VALUES (:id, :username, :first_name, :last_name)');
        $stmt->bindValue(':id', $user->getId(), SQLITE3_INTEGER);
        $stmt->bindValue(':username', $user->getUsername(), SQLITE3_TEXT);
        $stmt->bindValue(':first_name', $user->getFirstName(), SQLITE3_TEXT);
        $stmt->bindValue(':last_name', $user->getLastName(), SQLITE3_TEXT);
        
        $result = $this->prepareStatementExecute($stmt);
        $result->finalize();
    }

    private function extractUsersFromResult(SQLite3Result $result): array {
        $users = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
           $users[] = [
                'id' => $row['id'],
                'username' => $row['user_name'],
                'firstName' => $row['first_name'],
                'lastName' => $row['last_name']
           ];
        }
        if (empty($users)) {
            throw new UserNotFoundException();
        }
        return $users; 
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
