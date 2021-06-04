<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\User;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class AddUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $json = $this->getFormData();
        
        if (!$json) {
            throw new HttpBadRequestException($this->request);
        }
        
        $user = new User($json->{'id'}, $json->{'username'}, $json->{'first_name'}, $json->{'last_name'});
        $this->userRepository->addNewUser($user);
        $this->logger->info("New user created: ${user}");
        return $this->respondWithData();
    }
}
