<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
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
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

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
     * @var \DateTime $createdAt Creation date and time
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @Groups({ "music_get_all", "music_get" })
     */
    private $createdAt;

    /**
     * @var \DateTime $updatedAt Last modification date and time
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     * @Groups({ "music_get_all", "music_get" })
     */
    private $updatedAt;

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
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
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
}
