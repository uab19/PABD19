<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * ForumTopic
 *
 * @ORM\Table(name="forum_topic", indexes={@ORM\Index(name="forum_topic_forum_subcategory_id_fk", columns={"subcategory_id"}), @ORM\Index(name="forum_topic_user_id_fk", columns={"user_id"})})
 * @ORM\Entity
 */
class ForumTopic
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
     * @var \ForumSubcategory
     *
     * @ORM\ManyToOne(targetEntity="ForumSubcategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subcategory_id", referencedColumnName="id")
     * })
     */
    private $subcategory;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
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
     * @var \ForumTopic
     * 
     * @ORM\OneToMany(targetEntity="ForumReply", mappedBy="topic")
     */
    private $replies;

    public function __construct() {
        $this->replies = new ArrayCollection();
    }

    /**
     * @return Collection|ForumReply[]
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
     * @return ForumTopic
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
     * @return ForumTopic
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
     * @return ForumTopic
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
     * @param \AppBundle\Entity\ForumSubcategory $subcategory
     *
     * @return ForumTopic
     */
    public function setSubcategory(\AppBundle\Entity\ForumSubcategory $subcategory = null)
    {
        $this->subcategory = $subcategory;

        return $this;
    }

    /**
     * Get subcategory
     *
     * @return \AppBundle\Entity\ForumSubcategory
     */
    public function getSubcategory()
    {
        return $this->subcategory;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return ForumTopic
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
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
     * @return ForumTopic
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
     * @return ForumTopic
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
     * @return ForumTopic
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
