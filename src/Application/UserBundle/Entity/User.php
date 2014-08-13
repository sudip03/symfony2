<?php

namespace Application\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * The User entity
 * 
 * @ORM\Table(name="users",uniqueConstraints={@ORM\UniqueConstraint(name="unique_email", columns={"email"})})
 * @ORM\Entity(repositoryClass="Classified\UserBundle\Entity\UserRepository")
 * @UniqueEntity(fields="email", message="Email id entered is already registered. Please try a new email id")
 * @package UserBundle
 * @author Sudip Banerjee<sudipbanerjee03@gmail.com>
 */
class User implements AdvancedUserInterface
{

    const STATUS_ACTIVE          = 1;
    const STATUS_INACTIVE        = 0;


    /**
     * @ORM\Column(type="integer", name="id", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="firstName", type="string", nullable=false)
     * @var String
     */
    protected $firstName;

    /**
     * @ORM\Column(name="lastName", type="string", nullable=false)
     * @var String
     */
    protected $lastName;

    /**
     * @ORM\Column(name="email", unique=true, nullable=false)
     * @Assert\Email
     * @var String User email
     */
    protected $email;  

    /**
     * @ORM\Column(name="password", type="string")
     * @var String 
     */
    protected $password;

    /**
     * Is the record active yet?
     * 
     * @ORM\Column(name="status", type="smallint", nullable=true)
     * @var boolean
     */
    protected $status;
    
    /** 
    * @ORM\Column(type="boolean") 
    *
    */
    protected $isAdmin;

    /**
     * @ORM\Column(name="salt", type="string")
     * @var salt Password salt
     */
    protected $salt;

    /**
     * Token
     * 
     * @ORM\Column(name="token", type="string")
     * @var String
     */
    protected $token;
    
     /**
     * @ORM\OneToOne(targetEntity="Upload", cascade={"all"})
     * @var upload object
     */
    protected $avatar;
    

    /**
     * @ORM\Column(name="address", type="string", nullable=true)
     * @var String User address 
     */
    protected $address;

    
    /**
     * @ORM\Column(name="pincode", type="string", nullable=true)
     * @var String User pincode 
     */
    protected $pincode;
    
    /**
     * @ORM\Column(name="country", type="string", nullable=true)
     * @var String User country 
     */
    protected $country;
    
    /**
     * @ORM\Column(name="screenName", type="string", nullable=true)
     * @var string 
     */
    protected $screenName;
    
    /**
     * @ORM\Column(name="city", type="string", nullable=true)
     * @var string 
     */
    protected $city;
    
    /**
     * @ORM\Column(name="phone", type="string", nullable=true)
     * @var integer
     */
    protected $phone;    
    
    /** 
    * @ORM\Column(type="integer") 
    *
    * @var integer
    */
    protected $created;
    
    /** 
    * @ORM\Column(type="integer") 
    *
    * @var integer
    */
    protected $updated;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->created = time();

        $this->updated = time();

        $this->isAdmin = false;

        $this->salt = rand();

        $this->status = self::STATUS_INACTIVE;

        $this->avatar = new \Doctrine\Common\Collections\ArrayCollection();
        
    }
    
    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->email; // if having username property, use username else email
    }
    
    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }
    
    /**
     * @inheritDoc
     */
    public function getSalt()
    {
       return $this->salt;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials() {
        return true;
    }
        
    /**
     * {@inheritdoc}
     * @return bool is account active
     */
    public function isEnabled() {
        if($this->status == self::STATUS_ACTIVE){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * @inheritDoc
     */
    public function getRoles() {
        if ($this->isAdmin) {
            return array('ROLE_ADMIN');
        }

        return array('ROLE_USER');
    }
    
    /**
     * {@inheritdoc}
     */
    public function isAccountNonExpired() {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isCredentialsNonExpired() {
        return true;
    }

    /**
     * 
     * {@inheritdoc}
     */
    public function isAccountNonLocked() {
        return true;
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
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return User
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set created
     *
     * @param integer $created
     * @return User
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return integer 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param integer $updated
     * @return User
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return integer 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set isAdmin
     *
     * @param boolean $isAdmin
     * @return User
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * Get isAdmin
     *
     * @return boolean 
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return User
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set pincode
     *
     * @param string $pincode
     * @return User
     */
    public function setPincode($pincode)
    {
        $this->pincode = $pincode;

        return $this;
    }

    /**
     * Get pincode
     *
     * @return string 
     */
    public function getPincode()
    {
        return $this->pincode;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return User
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set screenName
     *
     * @param string $screenName
     * @return User
     */
    public function setScreenName($screenName)
    {
        $this->screenName = $screenName;

        return $this;
    }

    /**
     * Get screenName
     *
     * @return string 
     */
    public function getScreenName()
    {
        return $this->screenName;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set avatar
     *
     * @param \Application\UserBundle\Entity\Upload $avatar
     * @return User
     */
    public function setAvatar(\Application\UserBundle\Entity\Upload $avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return \Application\UserBundle\Entity\Upload 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }
}
