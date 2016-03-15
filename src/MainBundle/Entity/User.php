<?php
// src/AppBundle/Entity/User.php

namespace MainBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_sfgitcollab")
 * 
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *@ORM\OneToOne(targetEntity="MainBundle\Entity\Profile", mappedBy="user",cascade={"persist", "remove"})
     *
     */
    private $profile;

    /**
     *@ORM\OneToOne(targetEntity="MainBundle\Entity\Avatar", mappedBy="user", cascade={"persist", "remove"})
     *
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity="MainBundle\Entity\Advert", mappedBy="user")
     */
    private $adverts;

    public function __construct()
    {
        parent::__construct();
        $this->adverts = new ArrayCollection();
    }

    /**
     * Add adverts
     *
     * @param \MainBundle\Entity\Advert $adverts
     * @return User
     */
    public function addAdvert(\MainBundle\Entity\Advert $adverts)
    {
        $this->adverts[] = $adverts;

        $adverts->setUser($this);

        return $this;
    }

    /**
     * Remove adverts
     *
     * @param \MainBundle\Entity\Advert $adverts
     */
    public function removeAdvert(\MainBundle\Entity\Advert $adverts)
    {
        $this->adverts->removeElement($adverts);
    }

    /**
     * Get adverts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAdverts()
    {
        return $this->adverts;
    }

    /**
     * Set profile
     *
     * @param \MainBundle\Entity\Profile $profile
     * @return User
     */
    public function setProfile(\MainBundle\Entity\Profile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return \MainBundle\Entity\Profile 
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Set avatar
     *
     * @param \MainBundle\Entity\Avatar $avatar
     * @return User
     */
    public function setAvatar(\MainBundle\Entity\Avatar $avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return \MainBundle\Entity\Avatar 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }
}
