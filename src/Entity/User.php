<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *fields = {"username"},
 *message = "Username already exist"
 *)

 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @EqualTo(propertyPath="password", message="Passwords have to be the same to continue")
     */
    private $passwordConfirm;

    /**
     * @ORM\OneToMany(targetEntity=ToDo::class, mappedBy="user")
     */
    private $toDos;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity=Check::class, mappedBy="user", orphanRemoval=true)
     */
    private $checks;

    public function __construct()
    {
        $this->toDos = new ArrayCollection();
        $this->checks = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function getPasswordConfirm(): ?string
    {
        return $this->passwordConfirm;
    }

    public function setPasswordConfirm(string $passwordConfirm): self
    {
        $this->passwordConfirm = $passwordConfirm;

        return $this;
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
    }
    public function getRoles()
    {
        $roles = $this->roles;
        array_push($roles,  'ROLE_USER');

        # $roles[] = "ROLE_USER"; #}
        return array_unique($roles);
    }
    public function getUserIdentifier()
    {
        return $this->username;
    }

    /**
     * @return Collection|ToDo[]
     */
    public function getToDos(): Collection
    {
        return $this->toDos;
    }

    public function addToDo(ToDo $toDo): self
    {
        if (!$this->toDos->contains($toDo)) {
            $this->toDos[] = $toDo;
            $toDo->setUser($this);
        }

        return $this;
    }

    public function removeToDo(ToDo $toDo): self
    {
        if ($this->toDos->removeElement($toDo)) {
            // set the owning side to null (unless already changed)
            if ($toDo->getUser() === $this) {
                $toDo->setUser(null);
            }
        }

        return $this;
    }

    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection|Check[]
     */
    public function getChecks(): Collection
    {
        return $this->checks;
    }

    public function addCheck(Check $check): self
    {
        if (!$this->checks->contains($check)) {
            $this->checks[] = $check;
            $check->setUser($this);
        }

        return $this;
    }

    public function removeCheck(Check $check): self
    {
        if ($this->checks->removeElement($check)) {
            // set the owning side to null (unless already changed)
            if ($check->getUser() === $this) {
                $check->setUser(null);
            }
        }

        return $this;
    }
}
