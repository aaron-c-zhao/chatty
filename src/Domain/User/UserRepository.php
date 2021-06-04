<?php
declare(strict_types=1);

namespace App\Domain\User;

interface UserRepository
{
    /**
     * Retrieve all users. 
     * 
     * @return array list of users.
     */
    public function findAll(): array;

    /**
     * Retrieve a user by its Id.
     * 
     * @param int $id
     * @return array
     * @throws UserNotFoundException
     */
    public function findUserOfId(int $id): array;
    
    /**
     * @param User 
     */
    public function addNewUser(User $user);
}
