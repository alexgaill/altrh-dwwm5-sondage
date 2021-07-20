<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ReponseRepository::class)
 * @UniqueEntity("titre")
 */
class Reponse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(
     *  min=3,
     *  max=40,
     *  minMessage="Le titre de la réponse fait {{ value }} caractères au lieu de {{ limit }} au minimum",
     *  maxMessage="Le titre de la réponse doit faire au maximum {{ limit }} caractères",
     * )
     */
    private $titre;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="reponses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $question;

    /**
     * @ORM\Column(type="integer", options={"default":0}, nullable=true)
     * @Assert\PositiveOrZero
     */
    private $score;

    /**
     * @Assert\Type(
     *  type= "boolean"
     * )
     */
    private $highScore = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get the value of highScore
     */
    public function getHighScore()
    {
        return $this->highScore;
    }

    /**
     * Set the value of highScore
     */
    public function setHighScore($highScore): self
    {
        $this->highScore = $highScore;

        return $this;
    }
}
