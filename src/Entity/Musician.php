<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping\JoinTable;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
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
 *          "delete"={"method"="DELETE"},
 *     },
 *     subresourceOperations={
     *     "api_musics_musicians_get_subresource"={
     *         "normalization_context"={"groups"={"musician_get"}}
 *          }
 *     },
 *     attributes={"order"={"createdAt": "ASC"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\MusicianRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Musician
{
    use SoftDeleteableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({ "musician_get_all", "musician_get" })
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
    public $user = null;

    /**
     * @var ArrayCollection Instruments the musician play
     *
     * @ORM\ManyToMany(targetEntity="Instrument", inversedBy="musicians")
     * @JoinTable(name="musicians_instruments")
     * @Groups({ "musician_get_all", "musician_get" })
     * @MaxDepth(1)
     */
    public $instruments;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @Groups({ "musician_get" })
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     * @Groups({ "musician_get" })
     */
    protected $updatedAt;

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
    public function getInstruments()
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
    public function getUser()
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

    /**
     * Sets createdAt.
     *
     * @param  \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Returns createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * Sets updatedAt.
     *
     * @param  \DateTime $updatedAt
     * @return $this
     */
    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Returns updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}
