<?php
// src/AppBundle/Entity/User.php

namespace MainBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_sfgitcollab")
 * @ORM\Entity(repositoryClass="MainBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true)
     */
    protected $facebook_id;

    /**
     * @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true)
     */
    protected $facebook_access_token;

    /**
     * @ORM\Column(name="facebook_email", type="string", length=255, nullable=true)
     */
    protected $facebook_email;

    /**
     * @ORM\Column(name="facebook_picture", type="string", length=255, nullable=true)
     */
    protected $facebook_picture;

    /**
     * @ORM\Column(name="google_id", type="string", length=255, nullable=true)
     */
    protected $google_id;

    /**
     * @ORM\Column(name="google_access_token", type="string", length=255, nullable=true)
     */
    protected $google_access_token;

    /**
     * @ORM\Column(name="google_email", type="string", length=255, nullable=true)
     */
    protected $google_email;

    /**
     * @ORM\Column(name="google_verified_email", type="string", length=10, nullable=true)
     */
    protected $google_verified_email;

    /**
     * @ORM\Column(name="google_link", type="string", length=255, nullable=true)
     */
    protected $google_link;

    /**
     * @ORM\Column(name="google_picture", type="string", length=255, nullable=true)
     */
    protected $google_picture;

    /**
     * @ORM\Column(name="google_gender", type="string", length=15, nullable=true)
     */
    protected $google_gender;

    /**
     * @ORM\Column(name="google_locale", type="string", length=8, nullable=true)
     */
    protected $google_locale;

    /**
     * @ORM\Column(name="ip_user", type="string", length=100, nullable=true)
     */
    protected $ip_user;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=125, nullable=true)
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=125, nullable=true)
     */
    protected $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=30, nullable=true)
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=30, nullable=true)
     */
    protected $mobile;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    protected $address;

    /**
     * @var string
     *
     * @ORM\Column(name="zipcode", type="string", length=10, nullable=true)
     */
    protected $zipcode;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=125, nullable=true)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=125, nullable=true)
     */
    protected $country;

    /**
     * @var string
     *
     * @ORM\Column(name="complement", type="string", length=255, nullable=true)
     */
    protected $complement;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_public", type="boolean", nullable=true)
     */
    protected $phonePublic = false;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile_public", type="boolean", nullable=true)
     */
    protected $mobilePublic = false;

    /**
     * @var string
     *
     * @ORM\Column(name="address_public", type="boolean", nullable=true)
     */
    protected $addressPublic = false;

    /**
     * @ORM\Column(name="longitude", type="decimal", precision=11, scale=8, nullable=true)
     */
    protected $longitude;

    /**
     * @ORM\Column(name="latitude", type="decimal", precision=11, scale=8, nullable=true)
     */
    protected $latitude;

    /**
     * Stocke le chemin relatif du fichier et est persistée dans la base de données.
     * @var string
     *
     * @ORM\Column( type="string", length=255, nullable=true)
     */
    protected $path;

    /**
     * Est un champ « virtuel » pour gérer l’upload de fichier dans le formulaire
     *
     * @Assert\File(
     *     maxSize = "2M",
     *     mimeTypes = {"image/jpeg", "image/png", "image/gif"},
     *     mimeTypesMessage = "Please upload a valid Image type jpeg, png, gif"
     * )
     */
    protected $file;

    /*
     * Permet de gérer la suppression de l'ancien fichier dans le cas d'une modification d'image / editAction du controller des entités reliés
     *
     * @var string
     */
    protected $oldFile;

    private $resized_thumbnails;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;


    /**
     * @ORM\OneToMany(targetEntity="MainBundle\Entity\Advert", mappedBy="user")
     */
    protected $adverts;

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


    public function setPassword($password)
    {
        if ($password) {
            $this->password = $password;
        }
        return $this;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Avatar
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $file
     *
     * @return File
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;

        if($this->path != null){
            $this->oldFile = $this->path;
        }

        if ($oldFile = $this->getAbsolutePath()) {
            if(file_exists($this->getUploadDir().$oldFile)){
                unlink($oldFile);
            }
        }
        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    /**
     * Retourne null ou le chemin absolu du fichier
     * @return null|string
     */
    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().$this->path;
    }

    /**
     * Retourne le chemin web et peut être utilisé dans un template pour ajouter un lien vers le fichier uploadé
     * @return null|string
     */
    public function getWebPath($thumb)
    {
        if(!empty($thumb)){
            if(file_exists($this->getUploadDir().$thumb.$this->path)){
                return $this->getUploadDir().$thumb.$this->path;
            }
        }
        return null === $this->path ? null : $this->getUploadDir().$this->path;
    }

    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche le document/image dans la vue.
        return 'uploads/avatars/';
    }

    protected $thumbnails = array(
        '50_' => array(50,50),
        '150_' => array(150,150),
        '300_' => array(300,300),
        '320150_' => array(320,150),
        '800500_' => array(800,500),
    );

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {

        $this->oldFile = $this->getPath();
        if (null !== $this->file) {
            // génération d'un un nom unique pour l'image
            $this->path = sha1(uniqid(mt_rand(), true)).'.'.$this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        // la propriété « file » peut être vide si le champ n'est pas requis
        if (null === $this->file) {
            return;
        }
        // S'il y a une erreur lors du déplacement du fichier, une exception va automatiquement être lancée par la méthode move().
        //Cela va empêcher proprement l'entité d'être persistée dans la base de données si erreur il y a
        $this->file->move($this->getUploadRootDir(), $this->path);
        // Creation des thumbnails
        $imagine = new \Imagine\Gd\Imagine();
        $imagineOpen = $imagine->open($this->getUploadRootDir().$this->path);
        //REDIMENSIONNE ET GARDE LES PROPORTIONS
        $mode1    = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;
        //REDIMENSIONNE ET CROP
        $mode2    = \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;

        foreach ($this->thumbnails as $key => $value ){
            $imagineOpen->thumbnail(
                new \Imagine\Image\Box(
                    $value[0],
                    $value[1]
                ),
                $mode1
            )
                ->save($this->getUploadRootDir().$key.$this->path);
        }

        //le transfert est effectué on détruit la variable $file = la superglobale $_FILES qui est la variable de téléchargement de fichier via HTTP
        if(!empty($this->oldFile)){
            if(file_exists($this->getUploadRootDir().$this->oldFile)){
                unlink($this->getUploadRootDir().$this->oldFile);
            }
            foreach($this->thumbnails as $nameThumb => $size)
            {
                if (file_exists($this->getUploadRootDir().$nameThumb.$this->oldFile))
                {
                    unlink($this->getUploadRootDir().$nameThumb.$this->oldFile);
                }
            }
        }

        unset($this->file);
        $this->file = null;
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        foreach($this->thumbnails as $nameThumb => $size)
        {
            if (file_exists($this->getUploadRootDir().$nameThumb.$this->path))
            {
                $this->resized_thumbnails[] = $this->getUploadRootDir().$nameThumb.$this->path;
            }
        }
        $this->path = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        foreach($this->resized_thumbnails as $key => $thumbPath)
        {
            if (file_exists($thumbPath))
            {
                unlink($thumbPath);
            }
        }
        if(file_exists($this->path)){
            unlink($this->path);
        }

    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
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
     * Set mobile
     *
     * @param string $mobile
     * @return User
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string 
     */
    public function getMobile()
    {
        return $this->mobile;
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
     * Set zipcode
     *
     * @param string $zipcode
     * @return User
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
     * Set complement
     *
     * @param string $complement
     * @return User
     */
    public function setComplement($complement)
    {
        $this->complement = $complement;

        return $this;
    }

    /**
     * Get complement
     *
     * @return string 
     */
    public function getComplement()
    {
        return $this->complement;
    }

    /**
     * Set phonePublic
     *
     * @param boolean $phonePublic
     * @return User
     */
    public function setPhonePublic($phonePublic)
    {
        $this->phonePublic = $phonePublic;

        return $this;
    }

    /**
     * Get phonePublic
     *
     * @return boolean 
     */
    public function getPhonePublic()
    {
        return $this->phonePublic;
    }

    /**
     * Set mobilePublic
     *
     * @param boolean $mobilePublic
     * @return User
     */
    public function setMobilePublic($mobilePublic)
    {
        $this->mobilePublic = $mobilePublic;

        return $this;
    }

    /**
     * Get mobilePublic
     *
     * @return boolean 
     */
    public function getMobilePublic()
    {
        return $this->mobilePublic;
    }

    /**
     * Set addressPublic
     *
     * @param boolean $addressPublic
     * @return User
     */
    public function setAddressPublic($addressPublic)
    {
        $this->addressPublic = $addressPublic;

        return $this;
    }

    /**
     * Get addressPublic
     *
     * @return boolean 
     */
    public function getAddressPublic()
    {
        return $this->addressPublic;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return User
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
     * @return User
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return User
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
     * @return User
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

    public function __toString()
    {
        return 'user';
    }


    /**
     * Set facebook_id
     *
     * @param string $facebookId
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebook_id = $facebookId;

        return $this;
    }

    /**
     * Get facebook_id
     *
     * @return string 
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * Set facebook_access_token
     *
     * @param string $facebookAccessToken
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebook_access_token = $facebookAccessToken;

        return $this;
    }

    /**
     * Get facebook_access_token
     *
     * @return string 
     */
    public function getFacebookAccessToken()
    {
        return $this->facebook_access_token;
    }

    /**
     * Set google_id
     *
     * @param string $googleId
     * @return User
     */
    public function setGoogleId($googleId)
    {
        $this->google_id = $googleId;

        return $this;
    }

    /**
     * Get google_id
     *
     * @return string 
     */
    public function getGoogleId()
    {
        return $this->google_id;
    }

    /**
     * Set google_access_token
     *
     * @param string $googleAccessToken
     * @return User
     */
    public function setGoogleAccessToken($googleAccessToken)
    {
        $this->google_access_token = $googleAccessToken;

        return $this;
    }

    /**
     * Get google_access_token
     *
     * @return string 
     */
    public function getGoogleAccessToken()
    {
        return $this->google_access_token;
    }

    /**
     * Set facebook_email
     *
     * @param string $facebookEmail
     * @return User
     */
    public function setFacebookEmail($facebookEmail)
    {
        $this->facebook_email = $facebookEmail;

        return $this;
    }

    /**
     * Get facebook_email
     *
     * @return string 
     */
    public function getFacebookEmail()
    {
        return $this->facebook_email;
    }

    /**
     * Set google_email
     *
     * @param string $googleEmail
     * @return User
     */
    public function setGoogleEmail($googleEmail)
    {
        $this->google_email = $googleEmail;

        return $this;
    }

    /**
     * Get google_email
     *
     * @return string 
     */
    public function getGoogleEmail()
    {
        return $this->google_email;
    }

    /**
     * Set ip_user
     *
     * @param string $ipUser
     * @return User
     */
    public function setIpUser($ipUser)
    {
        $this->ip_user = $ipUser;

        return $this;
    }

    /**
     * Get ip_user
     *
     * @return string 
     */
    public function getIpUser()
    {
        return $this->ip_user;
    }

    /**
     * Set facebook_picture_data_url
     *
     * @param string $facebookPictureDataUrl
     * @return User
     */
    public function setFacebookPictureDataUrl($facebookPictureDataUrl)
    {
        $this->facebook_picture_data_url = $facebookPictureDataUrl;

        return $this;
    }

    /**
     * Get facebook_picture_data_url
     *
     * @return string 
     */
    public function getFacebookPictureDataUrl()
    {
        return $this->facebook_picture_data_url;
    }

    /**
     * Set facebook_picture
     *
     * @param string $facebookPicture
     * @return User
     */
    public function setFacebookPicture($facebookPicture)
    {
        $this->facebook_picture = $facebookPicture;

        return $this;
    }

    /**
     * Get facebook_picture
     *
     * @return string 
     */
    public function getFacebookPicture()
    {
        return $this->facebook_picture;
    }

    /**
     * Set google_link
     *
     * @param string $googleLink
     * @return User
     */
    public function setGoogleLink($googleLink)
    {
        $this->google_link = $googleLink;

        return $this;
    }

    /**
     * Get google_link
     *
     * @return string 
     */
    public function getGoogleLink()
    {
        return $this->google_link;
    }

    /**
     * Set google_picture
     *
     * @param string $googlePicture
     * @return User
     */
    public function setGooglePicture($googlePicture)
    {
        $this->google_picture = $googlePicture;

        return $this;
    }

    /**
     * Get google_picture
     *
     * @return string 
     */
    public function getGooglePicture()
    {
        return $this->google_picture;
    }

    /**
     * Set google_gender
     *
     * @param string $googleGender
     * @return User
     */
    public function setGoogleGender($googleGender)
    {
        $this->google_gender = $googleGender;

        return $this;
    }

    /**
     * Get google_gender
     *
     * @return string 
     */
    public function getGoogleGender()
    {
        return $this->google_gender;
    }

    /**
     * Set google_locale
     *
     * @param string $googleLocale
     * @return User
     */
    public function setGoogleLocale($googleLocale)
    {
        $this->google_locale = $googleLocale;

        return $this;
    }

    /**
     * Get google_locale
     *
     * @return string 
     */
    public function getGoogleLocale()
    {
        return $this->google_locale;
    }

    /**
     * Set google_verified_email
     *
     * @param string $googleVerifiedEmail
     * @return User
     */
    public function setGoogleVerifiedEmail($googleVerifiedEmail)
    {
        $this->google_verified_email = $googleVerifiedEmail;

        return $this;
    }

    /**
     * Get google_verified_email
     *
     * @return string 
     */
    public function getGoogleVerifiedEmail()
    {
        return $this->google_verified_email;
    }
}
