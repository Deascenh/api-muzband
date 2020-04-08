<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping\OneToMany;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"music_get_all"}}
 *          },
 *          "post"={
 *              "normalization_context"={"groups"={"music_get"}, "enable_max_depth"=true},
 *              "denormalization_context"={"groups"={"music_get"}}
 *          }
 *     },
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"music_get"}, "enable_max_depth"=true}
 *          },
 *          "put"={
 *              "normalization_context"={"groups"={"music_get"}, "enable_max_depth"=true},
 *              "denormalization_context"={"groups"={"music_get"}}
 *          },
 *          "delete"={"method"="DELETE"},
 *     },
 *     attributes={"order"={"title": "ASC"}}
 * )
 * @UniqueEntity("title")
 * @ORM\Entity(repositoryClass="App\Repository\MusicRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Music
{
    use SoftDeleteableEntity;

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({ "music_get_all", "music_get" })
     */
    private $id;

    /**
     * @var string Title of the music
     *
     * @ORM\Column(type="string", length=225, unique=true, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(max = 225)
     * @Groups({ "music_get_all", "music_get" })
     */
    private $title;

    /**
     * @var string Creator and owner of music rights
     *  May be the name of a band or a music performer.
     *
     * @ORM\Column(type="string", length=225, nullable=true)
     * @Assert\Length(max = 225)
     * @Groups({ "music_get_all", "music_get" })
     */
    private $artist = null;

    /**
     * @var User The user who declared this music
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     * @Groups({ "music_get_all", "music_get" })
     * @MaxDepth(1)
     */
    public $creator;

    /**
     * @var ArrayCollection
     *
     * @ApiSubresource()
     * @OneToMany(targetEntity="Musician", mappedBy="music")
     */
    private $musicians;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @Groups({ "music_get" })
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     * @Groups({ "music_get" })
     */
    protected $updatedAt;

    public function __construct()
    {
        $this->musicians = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return User
     */
    public function getCreator(): User
    {
        return $this->creator;
    }

    /**
     * @param User $creator
     */
    public function setCreator(User $creator): void
    {
        $this->creator = $creator;
    }

    /**
     * @return ArrayCollection|null
     */
    public function getMusicians(): ?ArrayCollection
    {
        return $this->musicians;
    }

    /**
     * @param ArrayCollection $musicians
     */
    public function setMusicians(ArrayCollection $musicians): void
    {
        $this->musicians = $musicians;
    }

    public function addMusician(Instrument $musician): Music
    {
        if (!$this->musicians->contains($musician)) {
            $this->musicians->add($musician);
        }

        return $this;
    }

    public function removeMusician(Instrument $musician): Music
    {
        $this->musicians->removeElement($musician);

        return $this;
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

    /**
     * @return string|null
     */
    public function getArtist(): ?string
    {
        return $this->artist;
    }

    /**
     * @param string $artist
     */
    public function setArtist(string $artist): void
    {
        $this->artist = $artist;
    }
}
