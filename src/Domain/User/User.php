<?php
declare(strict_types=1);

namespace App\Domain\User;

use JsonSerializable;

class User implements JsonSerializable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string|null
     */
    private $firstName;

    /**
     * @var string|null
     */
    private $lastName;

    /**
     * @param int  $id
     * @param string    $username
     * @param string|null    $firstName
     * @param string|null    $lastName
     */
    public function __construct(int $id, string $username, ?string $firstName, ?string $lastName)
    {
        $this->id = $id;
        $this->username = strtolower($username);
        $this->firstName = ucfirst($firstName);
        $this->lastName = ucfirst($lastName);
    }

    /**
     * @return int|null
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
        ];
    }

    public function __toString()
    {
        return "id: {$this->id}, username: {$this->username}, firstName: {$this->firstName}, lastName: {$this->lastName}";
    }
}
