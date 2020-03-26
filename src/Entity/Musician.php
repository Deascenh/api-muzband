<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"musician_get_all"}}
 *          },
 *          "post"={
 *              "normalization_context"={"groups"={"musician_get"}, "enable_max_depth"=true},
 *              "denormalization_context"={"groups"={"musician_get"}}
 *          }
 *     },
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"musician_get"}, "enable_max_depth"=true}
 *          },
 *          "put"={
 *              "normalization_context"={"groups"={"musician_get"}, "enable_max_depth"=true},
 *              "denormalization_context"={"groups"={"musician_get"}}
 *          },
 *     },
 * )
 * @ORM\Entity(repositoryClass="App\Repository\MusicianRepository")
 */
class Musician
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Music The music for which this musician plays
     *
     * @ORM\ManyToOne(targetEntity="Music", inversedBy="musicians")
     * @ORM\JoinColumn(name="music_id", referencedColumnName="id", nullable=false)
     * @Groups({ "musician_get_all", "musician_get" })
     * @MaxDepth(1)
     */
    public $music;

    /**
     * @var User The user who is the musician
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     * @Groups({ "musician_get_all", "musician_get" })
     * @MaxDepth(1)
     */
    public $user;

    /**
     * @var ArrayCollection Instruments the musician play
     *
     * @ORM\ManyToMany(targetEntity="Instrument", mappedBy="musicians")
     * @JoinTable(name="musicians_instruments")
     * @Groups({ "musician_get_all", "musician_get" })
     * @MaxDepth(1)
     */
    public $instruments;

    public function __construct()
    {
        $this->instruments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection
     */
    public function getInstruments(): ArrayCollection
    {
        return $this->instruments;
    }

    /**
     * @param ArrayCollection $instruments
     */
    public function setInstruments(ArrayCollection $instruments): void
    {
        $this->instruments = $instruments;
    }

    public function addInstrument(Instrument $instrument): Musician
    {
        if (!$this->instruments->contains($instrument)) {
            $this->instruments->add($instrument);
        }

        return $this;
    }

    public function removeInstrument(Instrument $instrument): Musician
    {
        $this->instruments->removeElement($instrument);

        return $this;
    }

    /**
     * @return Music
     */
    public function getMusic(): Music
    {
        return $this->music;
    }

    /**
     * @param Music $music
     */
    public function setMusic(Music $music): void
    {
        $this->music = $music;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}
