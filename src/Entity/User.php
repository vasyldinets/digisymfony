<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * The below length depends on the "algorithm" you use for encoding
     * the password, but this works well with bcrypt.
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles = array();

    // other properties and methods
    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    public function __construct(){
        $this->isActive = true;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid('', true));
    }

    public function getId(){
        return $this->id;
    }

    public function getUsername(){
        return $this->username;
    }

    public function setUsername($username){
        $this->username = $username;
    }

    public function getEmail(){
        return $this->email;
    }

    public function setEmail($email){
        $this->email = $email;
    }

    public function getPlainPassword(){
        return $this->plainPassword;
    }

    public function setPlainPassword($password){
        $this->plainPassword = $password;
    }


    public function getPassword(){
        return $this->password;
    }

    public function setPassword($password){
        $this->password = $password;
    }


    /**
     * Returns the roles or permissions granted to the user for security.
     */
    public function getRoles(){
        $roles = $this->roles;

        // guarantees that a user always has at least one role for security

        if (empty($roles)) {

            $roles[] = 'ROLE_USER';

        }

        return array_unique($roles);

    }

    public function setRoles($roles){
        $this->roles = $roles;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     */
    public function getSalt(){
        return;
    }

    /**
     * Removes sensitive data from the user.
     */
    public function eraseCredentials(){
        // if you had a plainPassword property, you'd nullify it here
        $this->plainPassword = null;
    }



}