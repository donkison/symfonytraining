<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApiTokenRepository")
 */
class ApiToken
{


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="apiTokens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct(User $user)
    {
        $this->token = bin2hex(random_bytes(30));
        $this->user = $user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     *
     * @return string|null
     * @Groups("main")
     */

    public function getToken(): ?string
    {
        return $this->token;
    }


    public function getUser(): ?User
    {
        return $this->user;
    }


}
