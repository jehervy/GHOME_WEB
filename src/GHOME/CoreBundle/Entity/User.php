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
    
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->admin = false;
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
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
     * For the purpose of the application, we are dealing with only to sorts 
     * or users: users and admins.
     *
     * {@inheritdoc}
     */
    public function getRoles()
    {
        $roles = array('ROLE_USER');
        if ($this->admin)
        {
            $roles[] = 'ROLE_ADMIN';
        }
        
        return $roles;
    }

    /**
     * {@inheritdoc}
     */
    public function equals(UserInterface $user)
    {
        return $user->getUsername() === $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->password;
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
    
    /**
     * Get admin
     *
     * @return boolean 
     */
    public function isAdmin()
    {
        return $this->admin;
    }
    
    /**
     * Set admin
     *
     * @param boolean $admin
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;
    }
}