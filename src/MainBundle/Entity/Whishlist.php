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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}