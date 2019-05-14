<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BlogPostComment
 *
 * @ORM\Table(name="blog_post_comment", indexes={@ORM\Index(name="blog_post_comment_blog_post_id_fk", columns={"blog_post_id"}), @ORM\Index(name="blog_post_comment_user_id_fk", columns={"user_id"})})
 * @ORM\Entity
 */
class BlogPostComment
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
     * @ORM\Column(name="create_date", type="datetime", nullable=false)
     */
    private $createDate;

    /**
     * @var \BlogPost
     *
     * @ORM\ManyToOne(targetEntity="BlogPost")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="blog_post_id", referencedColumnName="id")
     * })
     */
    private $blogPost;

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
    private $userFullName;



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
     * @return BlogPostComment
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
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return BlogPostComment
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Set blogPost
     *
     * @param \AppBundle\Entity\BlogPost $blogPost
     *
     * @return BlogPostComment
     */
    public function setBlogPost(\AppBundle\Entity\BlogPost $blogPost = null)
    {
        $this->blogPost = $blogPost;

        return $this;
    }

    /**
     * Get blogPost
     *
     * @return \AppBundle\Entity\BlogPost
     */
    public function getBlogPost()
    {
        return $this->blogPost;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return BlogPostComment
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
     * Get userFullName
     *
     * @return string
     */
    public function getUserFullName()
    {
        return $this->userFullName;
    }

    /**
     * Set userFullName
     *
     * @param string $userFullName
     *
     * @return BlogPost
     */
    public function setUserFullName($userFullName)
    {
        $this->userFullName = $userFullName;

        return $this;
    }
}