<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * ForumTopics
 *
 * @ORM\Table(name="forum_topics", indexes={@ORM\Index(name="forum_topics_forum_subcategories_id_fk", columns={"subcategory_id"}), @ORM\Index(name="forum_topics_users_id_fk", columns={"user_id"})})
 * @ORM\Entity
 */
class ForumTopics
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
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", length=65535, nullable=false)
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_added", type="datetime", nullable=false)
     */
    private $dateAdded;

    /**
     * @var \ForumSubcategories
     *
     * @ORM\ManyToOne(targetEntity="ForumSubcategories")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subcategory_id", referencedColumnName="id")
     * })
     */
    private $subcategory;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var integer
     */
    private $repliesNumber;

    /**
     * @var string
     */
    private $authorName;

    /**
     * @var string
     */
    private $subcategoryName;

    /**
     * @var \ForumTopics
     * 
     * @ORM\OneToMany(targetEntity="ForumReplies", mappedBy="topic")
     */
    private $replies;

    public function __construct() {
        $this->replies = new ArrayCollection();
    }

    /**
     * @return Collection|ForumReplies[]
     */
    public function getReplies(): Collection {
        return $this->replies;
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
     *
     * @return ForumTopics
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
     * Set message
     *
     * @param string $message
     *
     * @return ForumTopics
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     *
     * @return ForumTopics
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * Set subcategory
     *
     * @param \AppBundle\Entity\ForumSubcategories $subcategory
     *
     * @return ForumTopics
     */
    public function setSubcategory(\AppBundle\Entity\ForumSubcategories $subcategory = null)
    {
        $this->subcategory = $subcategory;

        return $this;
    }

    /**
     * Get subcategory
     *
     * @return \AppBundle\Entity\ForumSubcategories
     */
    public function getSubcategory()
    {
        return $this->subcategory;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\Users $user
     *
     * @return ForumTopics
     */
    public function setUser(\AppBundle\Entity\Users $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\Users
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set repliesNumber
     *
     * @param integer $repliesNumber
     *
     * @return ForumTopics
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

    /**
     * Set authorName
     * 
     * @param string $authorName
     * 
     * @return ForumTopics
     */
    public function setAuthorName($authorName) 
    {
        $this->authorName = $authorName;

        return $this;
    }

    /**
     * Get authorName
     * 
     * @return string
     */
    public function getAuthorName() {
        return $this->authorName;
    }

    /**
     * Set subcategoryName
     * 
     * @param string $subcategoryName
     * 
     * @return ForumTopics
     */
    public function setSubcategoryName($subcategoryName) {
        $this->subcategoryName = $subcategoryName;

        return $this;
    }

    /**
     * Get subcategoryName
     * 
     * @return string
     */
    public function getSubcategoryName() {
        return $this->subcategoryName;
    }
}
