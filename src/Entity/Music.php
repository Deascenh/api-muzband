<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Gedmo\Timestampable\Traits\TimestampableEntity;
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
 *     },
 * )
 * @UniqueEntity("title")
 * @ORM\Entity(repositoryClass="App\Repository\MusicRepository")
 */
class Music
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({ "music_get_all", "music_get" })
     */
    private $id;

    /**
     * @var string Title of the music also used as resource slug
     *
     * @ORM\Column(type="string", length=225, unique=true, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(max = 225)
     * @Groups({ "music_get_all", "music_get" })
     */
    private $title;

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
     * @return ArrayCollection
     */
    public function getMusicians()
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
}
