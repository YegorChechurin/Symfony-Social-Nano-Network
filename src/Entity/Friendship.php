<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FriendshipRepository")
 */
class Friendship
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="friendships")
     * @ORM\JoinColumn(nullable=false)
     */
    private $friend1;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $friend2;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFriend1(): ?User
    {
        return $this->friend1;
    }

    public function setFriend1(?User $friend1): self
    {
        $this->friend1 = $friend1;
        $this->friend1->addFriendship($this);

        return $this;
    }

    public function getFriend2(): ?User
    {
        return $this->friend2;
    }

    public function setFriend2(?User $friend2): self
    {
        $this->friend2 = $friend2;
        $this->friend2->addFriendship($this);

        return $this;
    }
}
