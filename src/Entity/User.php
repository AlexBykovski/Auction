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
 * @UniqueEntity("username", message="Пользователь с таким ником уже существует")
 * @UniqueEntity("email", message="Пользователь с таким email уже существует")
 */
class User extends BaseUser
{
    const DEFAULT_PHOTO = "default/profile_avatar.png";

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default" : true})
     */
    private $sex = true;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Assert\GreaterThan(
     *     value = 0
     * )
     */
    private $age;

    /**
     * One User has One UserDeliveryDetail.
     * @ORM\OneToOne(targetEntity="UserDeliveryDetail", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="delivery_detail_id", referencedColumnName="id")
     */
    private $deliveryDetail;

    /**
     * One User has One NotificationDetail.
     * @ORM\OneToOne(targetEntity="NotificationDetail", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="notification_detail_id", referencedColumnName="id")
     */
    private $notificationDetail;

    /**
     * One User has One StakeDetail.
     * @ORM\OneToOne(targetEntity="StakeDetail", cascade={"persist", "remove"})
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
     * @return UserDeliveryDetail
     */
    public function getDeliveryDetail()
    {
        return $this->deliveryDetail;
    }

    /**
     * @param UserDeliveryDetail $deliveryDetail
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
     * @return StakeDetail
     */
    public function getStakeDetail()
    {
        return $this->stakeDetail;
    }

    /**
     * @param StakeDetail $stakeDetail
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

    public function toArray()
    {
        $stakeDetail = $this->getStakeDetail();

        return [
            "username" => $this->getUsername(),
            "stakes" => $stakeDetail instanceof StakeDetail ? $stakeDetail->getCount() : 0,
            "photo" => self::DEFAULT_PHOTO
        ];
    }
}