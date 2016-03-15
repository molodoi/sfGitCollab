<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Whishlist
 *
 * @ORM\Table(name="whishlist_sfgitcollab")
 * @ORM\Entity(repositoryClass="MainBundle\Repository\WhishlistRepository")
 */
class Whishlist
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Advert")
     * @ORM\JoinColumn(name="advert_id", referencedColumnName="id")
     */
    private $avert;

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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Whishlist
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set user
     *
     * @param \MainBundle\Entity\User $user
     * @return Whishlist
     */
    public function setUser(\MainBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \MainBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set avert
     *
     * @param \MainBundle\Entity\Advert $avert
     * @return Whishlist
     */
    public function setAvert(\MainBundle\Entity\Advert $avert = null)
    {
        $this->avert = $avert;

        return $this;
    }

    /**
     * Get avert
     *
     * @return \MainBundle\Entity\Advert 
     */
    public function getAvert()
    {
        return $this->avert;
    }
}
