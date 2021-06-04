<?php
declare(strict_types=1);

namespace App\Domain\Message;

interface MessageRepository
{
    /**
     * Retrieve all messages send from and to the specified user. 
     * 
     * This method can be used to retrieve the chat history of a specific user.
     * 
     * @param int $id  Id of the user whose message history is ment to be retrieved. 
     * @return array   an array of stringified messages, each message has the form of
     *                 an array.
     */
    public function findAllByUserId(int $id);

    /**
     * Retrieve all messages send from and to the specified user after a certain point in time. 
     * 
     * This method is fro retrieving the most recent messages.
     * 
     * @param int $id  Id of the user whose message history is ment to be retrieved. 
     * @return array   an array of serialized messages
     */
    public function findAllByIdAfterTimestamp(int $id, int $timestamp);
    
    /**
     * Retrieve a message with a specified ID. 
     * 
     * @param int $id   unique Id of the message to be retrieved. 
     * @return array    a serialized message
     */
    public function findMessage(int $id) : array;

    /**
     * Add a new message to the db. 
     * 
     * @param Message $message 
     */
    public function addNewMessage(Message $message);

    /**
     * Delete a message with a specific id.
     * 
     * @param int $id 
     */
    public function deleteMessage(int $id);
}
