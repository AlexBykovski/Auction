<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default" : true})
     */
    private $sex = true;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\GreaterThan(
     *     value = 0
     * )
     */
    private $age;

    /**
     * One User has One UserDeliveryDetail.
     * @ORM\OneToOne(targetEntity="UserDeliveryDetail")
     * @ORM\JoinColumn(name="delivery_detail_id", referencedColumnName="id")
     */
    private $deliveryDetail;

    /**
     * One User has One NotificationDetail.
     * @ORM\OneToOne(targetEntity="NotificationDetail")
     * @ORM\JoinColumn(name="notification_detail_id", referencedColumnName="id")
     */
    private $notificationDetail;

    /**
     * One User has One StakeDetail.
     * @ORM\OneToOne(targetEntity="StakeDetail")
     * @ORM\JoinColumn(name="stake_detail_id", referencedColumnName="id")
     */
    private $stakeDetail;

    /**
     * One User has Many Products.
     * @ORM\OneToMany(targetEntity="Product", mappedBy="winner")
     */
    private $winProducts;

    /**
     * One User has Many SupportQuestions.
     * @ORM\OneToMany(targetEntity="SupportQuestion", mappedBy="user")
     */
    private $questions;

    public function __construct()
    {
        parent::__construct();

        $this->winProducts = new ArrayCollection();
        $this->questions = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return boolean
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * @param boolean $sex
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
    }

    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param mixed $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }

    /**
     * @return mixed
     */
    public function getDeliveryDetail()
    {
        return $this->deliveryDetail;
    }

    /**
     * @param mixed $deliveryDetail
     */
    public function setDeliveryDetail($deliveryDetail)
    {
        $this->deliveryDetail = $deliveryDetail;
    }

    /**
     * @return mixed
     */
    public function getNotificationDetail()
    {
        return $this->notificationDetail;
    }

    /**
     * @param mixed $notificationDetail
     */
    public function setNotificationDetail($notificationDetail)
    {
        $this->notificationDetail = $notificationDetail;
    }

    /**
     * @return mixed
     */
    public function getStakeDetail()
    {
        return $this->stakeDetail;
    }

    /**
     * @param mixed $stakeDetail
     */
    public function setStakeDetail($stakeDetail)
    {
        $this->stakeDetail = $stakeDetail;
    }

    /**
     * @return mixed
     */
    public function getWinProducts()
    {
        return $this->winProducts;
    }

    /**
     * @param mixed $winProducts
     */
    public function setWinProducts($winProducts)
    {
        $this->winProducts = $winProducts;
    }

    /**
     * @return mixed
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @param mixed $questions
     */
    public function setQuestions($questions)
    {
        $this->questions = $questions;
    }
}