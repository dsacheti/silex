<?php

/*
 * Criado no NetBeans
 * por Dalcinei Sacheti
 * dalcinei@gmail.com
 * pode usar este arquivo, mas deixe este cabeçalho
 */

namespace MicroFrame\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;//existe o AdvanceUserInterface
/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="MicroFrame\Entity\UserRepository")
 */
class User implements UserInterface{
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;
    
    /**
     * @ORM\Column(type="string",length=100)
     */
    private $username;
    
    /**
     * @ORM\Column(type="string",length=100)
     */
    private $password;
    
    /**
     * @ORM\Column(type="string",length=100,nullable=true)
     */
    private $plainPassword;
    
    /**
     * @ORM\Column(type="string",length=50)
     */
    private $roles = array('ROLE_USER');
    
    /**
     * @ORM\Column(type="datetime",length=100)
     */
    private $createdAt;
    
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getCreatedAt() 
    {
        return $this->createdAt;
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    public function setRoles($roles) 
    {
        $this->roles = $roles;
        return $this;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function eraseCredentials() 
    {
        $this->plainPassword = null;
    }

    public function getSalt()
    {
        return 'GpaXgODru';
    }
    
    public function __toString() 
    {
        return $this->getUsername();
    }
    
    public function toArray():array
    {
        return array(
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'salt' => $this->getSalt(),//só para fins didáticos
            'roles' => $this->getRoles(),
            'password' => $this->getPassword()
        
        );
    }

}
