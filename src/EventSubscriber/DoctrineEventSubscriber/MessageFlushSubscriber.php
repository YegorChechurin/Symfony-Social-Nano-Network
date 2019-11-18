<?php

namespace App\EventSubscriber\DoctrineEventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Message;
use App\Entity\Chat;

/** 
 * TODO !!!
 * This is one should be pretty slow, as it has to traverse 
 * through all the entities scheduled for insertion, thus
 * it should be replaced with a custom event 
 * App\Event\MessageSentEvent subscriber  
 */

class MessageFlushSubscriber implements EventSubscriber
{
	public function getSubscribedEvents()
    {
        return [
            Events::onFlush,
        ];
    }

    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();
 
        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof Message) {
                $message = $entity;

                $chat = $em->getRepository(Chat::class)->findOneByParticipants([
                    'participant1' => $message->getSender(), 
                    'participant2' => $message->getRecipient()
                ]);

                if (!$chat) {
                    $chat = $this->createChat($message);
                } else {
                    $this->updateChat($chat, $message);
                }

                $em->persist($chat);
                $uow->computeChangeSet($em->getClassMetadata(Chat::class), $chat);
            }
        }
    }

    private function createChat(Message $message): Chat
    {
        $chat = new Chat();

        $chat->setParticipant1($message->getSender());
        $chat->setParticipant2($message->getRecipient());
        $chat->setLastMessage($message);

        return $chat;
    }

    private function updateChat(Chat $chat, Message $message)
    {
        $chat->setLastMessage($message);
    }
}
