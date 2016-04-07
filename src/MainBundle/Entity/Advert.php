<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Advert
 *
 * @ORM\Table(name="advert_sfgitcollab")
 * @ORM\Entity(repositoryClass="MainBundle\Repository\AdvertRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Advert
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=165)
     */
    private $title;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=180)
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;


    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=20)
     */
    private $type;

    /**
     * @ORM\Column(name="location", type="string", length=20)
     */
    private $location;

    /**
     * @ORM\Column(name="zipcode", type="string", length=50)
     */
    private $zipcode;

    /**
     * @ORM\Column(name="longitude", type="decimal", precision=11, scale=8, nullable=true)
     */
    private $longitude;

    /**
     * @ORM\Column(name="latitude", type="decimal", precision=11, scale=8, nullable=true)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255, unique=true)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="is_public", type="boolean", nullable=true)
     */
    private $isPublic;

    /**
     * @var string
     *
     * @ORM\Column(name="is_activated", type="boolean", nullable=true)
     */
    private $isActivated;

    /**
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expired_at", type="datetime", nullable=true)
     */
    private $expiredAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="MainBundle\Entity\Category", inversedBy="adverts")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="MainBundle\Entity\User", inversedBy="adverts", cascade="persist")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;


    /**
     * @var File
     *
     * @ORM\OneToMany(targetEntity="MainBundle\Entity\AdvertFile", mappedBy="advert", cascade={"persist", "remove"})
     *
     */
    private $fileadverts;

    private $files;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fileadverts = new ArrayCollection();
        $this->files = array();
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
     * Set title
     *
     * @param string $title
     * @return Advert
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Advert
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Advert
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }


    /**
     * Set longitude
     *
     * @param string $longitude
     * @return Advert
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return Advert
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Advert
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
     * Set isAvailable
     *
     * @param boolean $isAvailable
     * @return Advert
     */
    public function setIsAvailable($isAvailable)
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    /**
     * Get isAvailable
     *
     * @return boolean 
     */
    public function getIsAvailable()
    {
        return $this->isAvailable;
    }

    /**
     * Set isPublic
     *
     * @param boolean $isPublic
     * @return Advert
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * Get isPublic
     *
     * @return boolean 
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }

    /**
     * Set isActivated
     *
     * @param boolean $isActivated
     * @return Advert
     */
    public function setIsActivated($isActivated)
    {
        $this->isActivated = $isActivated;

        return $this;
    }

    /**
     * Get isActivated
     *
     * @return boolean 
     */
    public function getIsActivated()
    {
        return $this->isActivated;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return Advert
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime 
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set expiredAt
     *
     * @param \DateTime $expiredAt
     * @return Advert
     */
    public function setExpiredAt($expiredAt)
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    /**
     * Get expiredAt
     *
     * @return \DateTime 
     */
    public function getExpiredAt()
    {
        return $this->expiredAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Advert
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Advert
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set category
     *
     * @param \MainBundle\Entity\Category $category
     * @return Advert
     */
    public function setCategory(\MainBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \MainBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set user
     *
     * @param \MainBundle\Entity\User $user
     * @return Advert
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
     * Set price
     *
     * @param float $price
     * @return Advert
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }


    /**
     * Set type
     *
     * @param string $type
     * @return Advert
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * Set location
     *
     * @param string $location
     * @return Advert
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return mixed
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param mixed $files
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }


    /**
     * Add fileadverts
     *
     * @param \MainBundle\Entity\AdvertFile $fileadverts
     * @return Advert
     */
    public function addFileadvert(\MainBundle\Entity\AdvertFile $fileadverts = null)
    {
        if($fileadverts == null)
        {
            return;
        }

        $this->fileadverts[] = $fileadverts;

        $fileadverts->setAdvert($this);

        return $this;
    }

    /**
     * Remove fileadverts
     *
     * @param \MainBundle\Entity\AdvertFile $fileadverts
     */
    public function removeFileadvert(\MainBundle\Entity\AdvertFile $fileadverts)
    {
        $this->fileadverts->removeElement($fileadverts);
        $fileadverts->setAdvert(null);
    }

    /**
     * Get fileadverts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFileadverts()
    {
        return $this->fileadverts;
    }

    /**
     * @ORM\PreFlush()
     */
    public function preFlushUpload()
    {
        if (null === $this->files) {
            return;
        }
        foreach($this->files as $file)
        {
            $this->addFileadvert($file);
            unset($file);
        }
    }

    /**
     * @ORM\PrePersist()
     */
    public function setTokenValue()
    {
        if(!$this->getToken())
        {
            $this->token = sha1(uniqid(mt_rand(), true));
            $now = $this->getCreatedAt() ? $this->getCreatedAt()->format('U') : time();
            $this->expiredAt = new \DateTime(date('Y-m-d H:i:s', $now + 86400 * 30));
        }
    }

    public function __toString(){
        return $this->title;
    }

    /**
     * Set zipcode
     *
     * @param string $zipcode
     * @return Advert
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string 
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }
}
