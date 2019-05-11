<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ForumSubcategories
 *
 * @ORM\Table(name="forum_subcategories", indexes={@ORM\Index(name="forum_subcategories_forum_categories_id_fk", columns={"category_id"})})
 * @ORM\Entity
 */
class ForumSubcategories
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
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=1000, nullable=false)
     */
    private $description;

    /**
     * @var \ForumCategories
     *
     * @ORM\ManyToOne(targetEntity="ForumCategories")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;

    /**
     * @var integer
     */
    private $topicsNumber;

    /**
     * @var integer
     */
    private $repliesNumber;


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
     * @return ForumSubcategories
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

    /**
     * Set description
     *
     * @param string $description
     *
     * @return ForumSubcategories
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\ForumCategories $category
     *
     * @return ForumSubcategories
     */
    public function setCategory(\AppBundle\Entity\ForumCategories $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\ForumCategories
     */
    public function getCategory()
    {
        return $this->category;
    }



    /**
     * Set topicsNumber
     *
     * @param integer $topicsNumber
     *
     * @return ForumSubcategories
     */
    public function setTopicsNumber($topicsNumber)
    {
        $this->topicsNumber = $topicsNumber;

        return $this;
    }

    /**
     * Get topicsNumber
     *
     * @return integer
     */
    public function getTopicsNumber()
    {
        return $this->topicsNumber;
    }

    /**
     * Set repliesNumber
     *
     * @param integer $repliesNumber
     *
     * @return ForumSubcategories
     */
    public function setRepliesNumber($repliesNumber)
    {
        $this->repliesNumber = $repliesNumber;

        return $this;
    }

    /**
     * Get repliesNumber
     *
     * @return integer
     */
    public function getRepliesNumber()
    {
        return $this->repliesNumber;
    }
}
