<?php
declare(strict_types=1);

namespace App\Application\Actions\Message;

use Psr\Http\Message\ResponseInterface as Response;

class MessageListAction extends MessageAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');
        $messageList = $this->messageRepository->findAllByUserId($userId);

        return $this->respondWithData($messageList);
    }
}

