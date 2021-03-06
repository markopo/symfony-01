<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="This e-mail is already used.")
 * @UniqueEntity(fields="username", message="This username is already used.")
 * @ApiResource(
 *     normalizationContext={"groups"={"get"}},
 *     itemOperations={
 *      "get"={
 *          "access_control"="is_granted('IS_AUTHENTICATED_FULLY')",
 *           "normalization_context"={"groups"={"get"}}
 *          },
 *      "put"={
 *          "access_control"="is_granted('IS_AUTHENTICATED_FULLY') and object == user",
 *           "denormalization_context"={
 *               "groups"={"put"}
 *              }
 *           }
 *     },
 *     collectionOperations={
 *         "post"={
 *              "denormalization_context"={
 *                  "groups"="post"
 *              }
 *           }
 *     },
 *     )
 */
class User implements UserInterface, \Serializable
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';



    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get", "get-blog-post-with-author"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=5, max=50)
     * @Groups({"get", "post", "get-blog-post-with-author"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=8, max=4096)
     * @Groups({"put", "post"})
     */
    private $plainPassword;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=4, max=50)
     * @Groups({"get", "post", "put", "get-blog-post-with-author"})
     */
    private $fullName;

    /**
     * @var array
     * @ORM\Column(type="simple_array")
     */
    private $roles;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Groups({"get", "post", "put"})
     */
    private $email;

    /**
     * @ORM\Column(type="text")
     * @Groups({"get", "post", "put"})
     */
    private $description;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MicroPost", mappedBy="user")
     * @Groups({"get"})
     */
    private $posts;



    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BlogPost", mappedBy="author")
     * @Groups({"get"})
     */
    private $blogposts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="author")
     * @Groups({"get"})
     */
    private $comments;


    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->roles = [ self::ROLE_USER ];
        $this->description = 'Registered: '. date('Y-m-d H:i:s'). ' Name: '.  $this->getFullName();

        $this->blogposts = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
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
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return Collection
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }


    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return Collection
     */
    public function getBlogposts(): Collection
    {
        return $this->blogposts;
    }

    public function setBlogposts($blogposts): self
    {
        $this->blogposts = $blogposts;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function setComments($comments): self
    {
        $this->comments = $comments;
        return $this;
    }



    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return array('ROLE_USER');
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }



    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {

    }

    public function serialize()
    {
       return serialize([
           $this->id,
           $this->username,
           $this->password
       ]);
    }



    public function unserialize($serialized)
    {
        list($this->id,
            $this->username,
            $this->password) = unserialize($serialized);
    }

    public function __toString()
    {
        return 'user: '. $this->getId().' '. $this->getUsername();
    }
}
