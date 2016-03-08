<?php
// src/AppBundle/Entity/User.php

namespace MainBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_sfgitcollab")
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
     * @ORM\OneToMany(targetEntity="MainBundle\Entity\Advert", mappedBy="user")
     */
    private $adverts;

    public function __construct()
    {
        parent::__construct();
        // your own logic
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
}
