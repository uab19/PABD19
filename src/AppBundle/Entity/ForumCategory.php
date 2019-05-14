<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * ForumCategory
 *
 * @ORM\Table(name="forum_category")
 * @ORM\Entity
 */
class ForumCategory
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
     * @var \ForumSubcategory
     * 
     * @ORM\OneToMany(targetEntity="ForumSubcategory", mappedBy="category")
     */
    private $subcategory;


    public function __construct() {
        $this->subcategory = new ArrayCollection();
    }

    /**
     * @return Collection|ForumSubcategory[]
     */
    public function getSubcategory(): Collection {
        return $this->subcategory;
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
     * @return ForumCategory
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
