<?php

namespace MainBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AdvertFile
 *
 * @ORM\Table(name="advert_file_sfgitcollab")
 * @ORM\Entity(repositoryClass="MainBundle\Repository\AdvertFileRepository")
 * @ORM\HasLifecycleCallbacks
 */
class AdvertFile
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
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * Est un champ « virtuel » pour gérer l’upload de fichier dans le formulaire
     *
     * @Assert\File(
     *     maxSize = "20M",
     *     maxSizeMessage = "The file is too large ({{ size }} {{ suffix }}). Allowed maximum size is {{ limit }} {{ suffix }}",
     *     mimeTypes = {"image/jpeg", "image/png", "image/gif"},
     *     mimeTypesMessage = "Please upload a valid Image type jpeg, png, gif"
     * )
     */
    private $file;

    /*
     * Permet de gérer la suppression de l'ancien fichier dans le cas d'une modification d'image / editAction du controller des entités reliés
     *
     * @var string
     */
    private $oldFile;

    /**
     * @ORM\ManyToOne(targetEntity="MainBundle\Entity\Advert", inversedBy="fileadverts")
     * @ORM\JoinColumn(name="advert_id", referencedColumnName="id", nullable=true)
     */
    private $advert;

    private $resized_thumbnails;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->resized_thumbnails = array();
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
     * Set alt
     *
     * @param string $alt
     * @return AdvertFile
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string 
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return AdvertFile
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return AdvertFile
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
     * Set advert
     *
     * @param \MainBundle\Entity\Advert $advert
     * @return AdvertFile
     */
    public function setAdvert(\MainBundle\Entity\Advert $advert = null)
    {
        $this->advert = $advert;

        return $this;
    }

    /**
     * Get advert
     *
     * @return \MainBundle\Entity\Advert 
     */
    public function getAdvert()
    {
        return $this->advert;
    }

    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     *
     * @return File
     */
    public function setFile(UploadedFile $file)
    {
        /*die(dump($file));*/

        $this->file = $file;

        if($this->path != null){
            $this->oldFile = $this->path;
        }

        if ($oldFile = $this->getAbsolutePath()) {
            unlink($oldFile);
        }
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
     * @param null $thumb | retourne une image redimensionné thumb-100-100-, thumb-400-400-, thumb-800-800-
     * @return null|string
     */
    public function getWebPath($thumb = null)
    {
        if(!empty($thumb)){
            if(file_exists($this->getUploadDir().$thumb.$this->path)){
                return $this->getUploadDir().$thumb.$this->path;
            }
        }
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche le document/image dans la vue.
        return 'uploads/files_adverts/';
    }

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

            if(empty($this->alt)){
                $this->alt = $this->file->getClientOriginalName();
            }
        }
    }

    protected $thumbnails = array(
        '150_' => array(150,150),
        '400_' => array(400,400),
        '800_' => array(800,800),
    );

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
                $mode2
            )
                ->save($this->getUploadRootDir().$key.$this->path);
        }

        //le transfert est effectué on détruit la variable $file = la superglobale $_FILES qui est la variable de téléchargement de fichier via HTTP
        unset($this->file);
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

        if(file_exists($this->path))
        {
            unlink($this->path);
        }
    }

    public function __toString()
    {
        return $this->getAlt();
    }
}