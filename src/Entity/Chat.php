<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChatRepository")
 */
class Chat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="chats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $participant1;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="chats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $participant2;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Message", inversedBy="chat", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $lastMessage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParticipant1(): ?User
    {
        return $this->participant1;
    }

    public function setParticipant1(?User $participant1): self
    {
        $this->participant1 = $participant1;
        $this->participant1->addChat($this);

        return $this;
    }

    public function getParticipant2(): ?User
    {
        return $this->participant2;
    }

    public function setParticipant2(?User $participant2): self
    {
        $this->participant2 = $participant2;
        $this->participant2->addChat($this);

        return $this;
    }

    public function getLastMessage(): ?Message
    {
        return $this->lastMessage;
    }

    public function setLastMessage(Message $lastMessage): self
    {
        $this->lastMessage = $lastMessage;
        $this->lastMessage->setChat($this);

        return $this;
    }
}
