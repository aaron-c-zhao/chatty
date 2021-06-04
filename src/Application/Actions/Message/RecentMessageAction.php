<?php
declare(strict_types=1);

namespace App\Application\Actions\Message;

use Psr\Http\Message\ResponseInterface as Response;

class RecentMessageAction extends MessageAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        
        $userId = (int) $this->resolveArg('id');
        $timestamp = (int) $this->resolveArg('timestamp');
        $result = $this->messageRepository->findAllByIdAfterTimestamp($userId, $timestamp);
        
        return $this->respondWithData($result);
    }
}


