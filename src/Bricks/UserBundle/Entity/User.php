<?php
namespace Bricks\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use FOS\MessageBundle\Model\ParticipantInterface;

use Bricks\SiteBundle\Entity\Brick;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser implements ParticipantInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var boolean $emailpolicy_send_on_new_message
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $emailpolicy_send_on_new_message = true;

    /**
     * @var datetime $created_at
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @var datetime $updated_at
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated_at;
    
    /**
     * @ORM\OneToMany(targetEntity="Bricks\SiteBundle\Entity\Brick", mappedBy="user", cascade={"persist"})
     * @ORM\OrderBy({"created_at" = "ASC"})
     */
    private $bricks;
    
    /**
     * @ORM\OneToMany(targetEntity="Bricks\SiteBundle\Entity\UserStarsBrick", mappedBy="user", cascade={"persist"})
     */
    private $userStarsBricks;

    /**************************************************************************************************
     *	custom functions
    **************************************************************************************************/
    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
    
    /**
     * return if a user marked a $brick as "starred".
     * 
     * marking a brick as "starred" is like putting it in the "favorites"
     * 
     * @param Brick $brick
     * @return boolean
     */
    public function isStarringBrick(Brick $brick)
    {
        foreach ($this->getUserStarsBricks() as $usb) {
            if ($usb->getBrick() && $usb->getBrick()->getId() === $brick->getId()) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * return the starred bricks for a user
     * 
     * @return multitype:NULL
     */
    public function getStarredBricks()
    {
        $entities = array();
        
        foreach ($this->getUserStarsBricks() as $usb) {
            $entities[] = $usb->getBrick();
        }
        
        return $entities;
    }
    
    /**************************************************************************************************
     *	getters and setters
    **************************************************************************************************/
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
     * Set emailpolicy_send_on_new_message
     *
     * @param boolean $emailpolicySendOnNewMessage
     * @return User
     */
    public function setEmailpolicySendOnNewMessage($emailpolicySendOnNewMessage)
    {
        $this->emailpolicy_send_on_new_message = $emailpolicySendOnNewMessage;
    
        return $this;
    }

    /**
     * Get emailpolicy_send_on_new_message
     *
     * @return boolean 
     */
    public function getEmailpolicySendOnNewMessage()
    {
        return $this->emailpolicy_send_on_new_message;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    
        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
    
        return $this;
    }

    /**
     * Get updated_at
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Add bricks
     *
     * @param \Bricks\SiteBundle\Entity\Brick $bricks
     * @return User
     */
    public function addBrick(\Bricks\SiteBundle\Entity\Brick $bricks)
    {
        $this->bricks[] = $bricks;
    
        return $this;
    }

    /**
     * Remove bricks
     *
     * @param \Bricks\SiteBundle\Entity\Brick $bricks
     */
    public function removeBrick(\Bricks\SiteBundle\Entity\Brick $bricks)
    {
        $this->bricks->removeElement($bricks);
    }

    /**
     * Get bricks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBricks()
    {
        return $this->bricks;
    }

    /**
     * Add userStarsBricks
     *
     * @param \Bricks\SiteBundle\Entity\UserStarsBrick $userStarsBricks
     * @return User
     */
    public function addUserStarsBrick(\Bricks\SiteBundle\Entity\UserStarsBrick $userStarsBricks)
    {
        $this->userStarsBricks[] = $userStarsBricks;
    
        return $this;
    }

    /**
     * Remove userStarsBricks
     *
     * @param \Bricks\SiteBundle\Entity\UserStarsBrick $userStarsBricks
     */
    public function removeUserStarsBrick(\Bricks\SiteBundle\Entity\UserStarsBrick $userStarsBricks)
    {
        $this->userStarsBricks->removeElement($userStarsBricks);
    }

    /**
     * Get userStarsBricks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserStarsBricks()
    {
        return $this->userStarsBricks;
    }
}