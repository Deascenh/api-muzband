<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"instrument_get_all"}}
 *          },
 *          "post"={
 *              "normalization_context"={"groups"={"instrument_get"}},
 *              "denormalization_context"={"groups"={"instrument_get"}}
 *          }
 *     },
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"instrument_get"}}
 *          },
 *          "put"={
 *              "normalization_context"={"groups"={"instrument_get"}},
 *              "denormalization_context"={"groups"={"instrument_get"}}
 *          },
 *     },
 *
 * )
 * @ORM\Entity(repositoryClass="App\Repository\InstrumentRepository")
 */
class Instrument
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({ "instrument_get_all", "instrument_get" })
     */
    private $id;

    /**
     * @var string Name of this instrument
     *
     * @ORM\Column(type="string", unique=true, length=255)
     * @Groups({ "instrument_get_all", "instrument_get" })
     */
    private $name;

    /**
     * @var string Musicians playing this instrument
     *
     * @ApiSubresource()
     * @ManyToMany(targetEntity="Musician", mappedBy="instruments")
     * @Groups({ "instrument_get" })
     */
    private $musicians;

    public function __construct() {
        $this->musicians = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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
}
