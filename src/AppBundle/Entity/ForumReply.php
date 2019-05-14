<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * ForumReply
 *
 * @ORM\Table(name="forum_reply", indexes={@ORM\Index(name="forum_reply_forum_reply_id_fk", columns={"reply_id"}), @ORM\Index(name="forum_reply_forum_topic_id_fk", columns={"topic_id"}), @ORM\Index(name="forum_reply_user_id_fk", columns={"user_id"})})
 * @ORM\Entity
 */
class ForumReply
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
     * @var \ForumReply
     *
     * @ORM\ManyToOne(targetEntity="ForumReply")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="reply_id", referencedColumnName="id")
     * })
     */
    private $reply;

    /**
     * @var \ForumTopic
     *
     * @ORM\ManyToOne(targetEntity="ForumTopic")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="topic_id", referencedColumnName="id")
     * })
     */
    private $topic;

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
     * @var string
     */
    private $authorName;

    /**
     * @var \ForumReply
     * 
     * @ORM\OneToMany(targetEntity="ForumReply", mappedBy="reply")
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
     * Set message
     *
     * @param string $message
     *
     * @return ForumReply
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
     * @return ForumReply
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
     * Set reply
     *
     * @param \AppBundle\Entity\ForumReply $reply
     *
     * @return ForumReply
     */
    public function setReply(\AppBundle\Entity\ForumReply $reply = null)
    {
        $this->reply = $reply;

        return $this;
    }

    /**
     * Get reply
     *
     * @return \AppBundle\Entity\ForumReply
     */
    public function getReply()
    {
        return $this->reply;
    }

    /**
     * Set topic
     *
     * @param \AppBundle\Entity\ForumTopic $topic
     *
     * @return ForumReply
     */
    public function setTopic(\AppBundle\Entity\ForumTopic $topic = null)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * Get topic
     *
     * @return \AppBundle\Entity\ForumTopic
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return ForumReply
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
     * Set authorName
     * 
     * @param string $authorName
     * 
     * @return ForumReply
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
}
