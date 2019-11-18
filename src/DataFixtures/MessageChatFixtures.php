<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Message;

class MessageChatFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
    	$message = new Message();
    	$message->setSender($this->getReference('user1'))
    	        ->setRecipient($this->getReference('user2'))
    	        ->setText('I come from MessageChatFixtures');

    	$manager->persist($message);
        $manager->flush();
    }
}
