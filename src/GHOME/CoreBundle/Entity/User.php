<?php

namespace GHOME\CoreBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="username", type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(name="salt", type="string", length=40)
     */
    private $salt;

    /**
     * @ORM\Column(name="password", type="string", length=40)
     */
    private $password;
    
    /**
     * @ORM\Column(type="boolean")
     */
    protected $admin;
    
    public function __construct()
    {
        $this->admin = false;
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }
    
    public function getRoles()
    {
        $roles = array('ROLE_USER');
        if ($this->admin)
        {
            $roles[] = 'ROLE_ADMIN';
        }
        
        return $roles;
    }

    public function equals(UserInterface $user)
    {
        return $user->getUsername() === $this->username;
    }

    public function eraseCredentials()
    {
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getPassword()
    {
        return $this->password;
    }
    
    public function isAdmin()
    {
        return $this->admin;
    }
    
    public function setAdmin($admin)
    {
        $this->admin = $admin;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Set salt
     *
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get admin
     *
     * @return boolean 
     */
    public function getAdmin()
    {
        return $this->admin;
    }
}