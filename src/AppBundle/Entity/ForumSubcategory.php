<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ForumSubcategory
 *
 * @ORM\Table(name="forum_subcategory", indexes={@ORM\Index(name="forum_subcategory_forum_category_id_fk", columns={"category_id"})})
 * @ORM\Entity
 */
class ForumSubcategory
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
     * @var \ForumCategory
     *
     * @ORM\ManyToOne(targetEntity="ForumCategory")
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
     * @return ForumSubcategory
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
     * @return ForumSubcategory
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
     * @param \AppBundle\Entity\ForumCategory $category
     *
     * @return ForumSubcategory
     */
    public function setCategory(\AppBundle\Entity\ForumCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\ForumCategory
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
     * @return ForumSubcategory
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
     * @return ForumSubcategory
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
