<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * ForumCategories
 *
 * @ORM\Table(name="forum_categories")
 * @ORM\Entity
 */
class ForumCategories
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var \ForumSubcategories
     * 
     * @ORM\OneToMany(targetEntity="ForumSubcategories", mappedBy="category")
     */
    private $subcategories;


    public function __construct() {
        $this->subcategories = new ArrayCollection();
    }

    /**
     * @return Collection|ForumSubcategories[]
     */
    public function getSubcategories(): Collection {
        return $this->subcategories;
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
     * Set name
     *
     * @param string $name
     *
     * @return ForumCategories
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
