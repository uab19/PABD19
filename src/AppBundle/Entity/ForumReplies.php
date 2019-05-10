<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ForumReplies
 *
 * @ORM\Table(name="forum_replies", indexes={@ORM\Index(name="forum_replies_forum_replies_id_fk", columns={"reply_id"}), @ORM\Index(name="forum_replies_forum_topics_id_fk", columns={"topic_id"}), @ORM\Index(name="forum_replies_users_id_fk", columns={"user_id"})})
 * @ORM\Entity
 */
class ForumReplies
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
     * @ORM\Column(name="content", type="text", length=65535, nullable=false)
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_added", type="datetime", nullable=false)
     */
    private $dateAdded;

    /**
     * @var \ForumReplies
     *
     * @ORM\ManyToOne(targetEntity="ForumReplies")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="reply_id", referencedColumnName="id")
     * })
     */
    private $reply;

    /**
     * @var \ForumTopics
     *
     * @ORM\ManyToOne(targetEntity="ForumTopics")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="topic_id", referencedColumnName="id")
     * })
     */
    private $topic;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return ForumReplies
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     *
     * @return ForumReplies
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
     * @param \AppBundle\Entity\ForumReplies $reply
     *
     * @return ForumReplies
     */
    public function setReply(\AppBundle\Entity\ForumReplies $reply = null)
    {
        $this->reply = $reply;

        return $this;
    }

    /**
     * Get reply
     *
     * @return \AppBundle\Entity\ForumReplies
     */
    public function getReply()
    {
        return $this->reply;
    }

    /**
     * Set topic
     *
     * @param \AppBundle\Entity\ForumTopics $topic
     *
     * @return ForumReplies
     */
    public function setTopic(\AppBundle\Entity\ForumTopics $topic = null)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * Get topic
     *
     * @return \AppBundle\Entity\ForumTopics
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\Users $user
     *
     * @return ForumReplies
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
}