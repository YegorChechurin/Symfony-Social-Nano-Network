<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
	private $passwordEncoder;

	public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($i=0; $i < 5; $i++) { 
        	$number = $i+1;
    		$user = new User();
    		$user->setUsername('user'.$number);
    		$user->setEmail('user'.$number);
    		$user->setPassword(
    			$this->passwordEncoder->encodePassword(
    				$user,
	            	'user'.$number
	        	)
    		);
        	$manager->persist($user);
            $manager->flush();

            $this->addReference('user'.$number, $user);
    	}
    }
}
