<?php

namespace App\Tests\EventSubscriber\DoctrineEventSubscriber;

use App\Tests\FixtureAwareTestCase;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\MessageChatFixtures;
use App\Entity\User;
use App\Entity\Chat;
use App\Entity\Message;

class MessageFlushSubscriberTest extends FixtureAwareTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->addFixture(new UserFixture());
        $this->addFixture(new MessageChatFixture());
        $this->executeFixtures();
    }

    public function testOnFlush()
    {
        $userRepo = $this->em->getRepository(User::class);

        $user1 = $userRepo->findOneBy(['email' => 'user1']);
        $user2 = $userRepo->findOneBy(['email' => 'user2']);

        $chat = $em->getRepository(Chat::class)
                    ->findOneByParticipants([
                        'participant1' => $user1, 
                        'participant2' => $user2
                    ]);

        $this->assertEquals('I come from MessageChatFixtures', $chat->getLastMessage);

        $message = new Message();

        $message->setSender($user1)
                ->setRecipient($user2)
                ->setText('I come from MessageFlushSubscriberTest');
        $this->em->persist($message);
        $this->em->flush();

        $this->assertEquals('I come from MessageFlushSubscriberTest', $chat->getLastMessage);
    }
}