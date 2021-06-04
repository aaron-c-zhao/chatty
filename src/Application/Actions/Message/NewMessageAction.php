<?php
declare(strict_types=1);

namespace App\Application\Actions\Message;

use App\Domain\Message\Message;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class NewMessageAction extends MessageAction
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
        
        $message = new Message($json->{'id'}, $json->{'sender'}, $json->{'receiver'}, $json->{'content'}, $json->{'timestamp'});
        $this->messageRepository->addNewMessage($message);
        
        return $this->respondWithData();
    }
}

