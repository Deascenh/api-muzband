<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 ** Secured resource.
 *
 * @ApiResource(
 *     attributes={"security"="is_granted('ROLE_USER')"},
 *     collectionOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"user_get_all"}},
 *          },
 *          "post"={
 *              "normalization_context"={"groups"={"user_get"}},
 *              "denormalization_context"={"groups"={"user_post"}}
 *          }
 *     },
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"user_get"}},
 *          },
 *          "put"={
 *              "security"="object == user",
 *              "normalization_context"={"groups"={"user_get"}},
 *              "denormalization_context"={"groups"={"user_put"}}
 *          },
 *          "delete"={
 *              "security"="object == user",
 *              "method"="DELETE"
 *          }
 *     },
 *     attributes={"order"={"email": "ASC"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class User implements UserInterface
{
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @var UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     *
     * @Groups({
     *     "user_get", "user_get_all",
     *     "musician_get", "musician_get_all"
     * })
     */
    private $id;

    /**
     * @var string User name displayed everywhere in the
     *  application and seen by all other users. If null or empty,
     *  the application uses email instead.
     *
     * @ORM\Column(type="string", length=225, unique=true, nullable=true)
     * @Assert\Length(max = 225)
     * @Groups({
     *     "user_get_all", "user_get", "user_post", "user_put",
     *     "musician_get", "musician_get_all"
     * })
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Length(max = 180)
     * @Groups({
     *     "user_get", "user_get_all", "user_post", "user_put",
     *     "musician_get", "musician_get_all"
     * })
     */
    private $email;

    /**
     * @var array Security roles assigned and managed by the application server
     *
     * @ORM\Column(type="json")
     * @Groups({"user_get", "user_post"})
     */
    private $roles = [];

    /**
     * @var string The hashed user password
     *
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var string The raw user password
     *
     * @Assert\Length(max=4096)
     * @Groups({"user_post"})
     */
    private $plainPassword;

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password): self
    {
        $this->plainPassword = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
