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
 * @UniqueEntity("username", message="Пользователь с таким ником уже существует", groups={"edit_profile", "registration"})
 * @UniqueEntity("email", message="Пользователь с таким email уже существует", groups={"edit_profile", "registration"})
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
     * @var string
     *
     * @Assert\NotBlank(message = "Проверьте корректность ввода", groups={"edit_profile"})
     */
    protected $username;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "Проверьте корректность ввода", groups={"edit_profile"})
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\NotBlank(message = "Проверьте корректность ввода", groups={"edit_profile"})
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\NotBlank(message = "Проверьте корректность ввода", groups={"edit_profile"})
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
     *
     * @Assert\NotBlank(message = "Проверьте корректность ввода", groups={"edit_profile"})
     * @Assert\GreaterThan(value = 0, message = "Значение должно быть целым и положительным", groups={"edit_profile"})
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
     * One User has Many Products.
     * @ORM\OneToMany(targetEntity="Product", mappedBy="potentialWinner")
     */
    private $potentialProducts;

    /**
     * One User has Many SupportQuestions.
     * @ORM\OneToMany(targetEntity="SupportQuestion", mappedBy="user")
     */
    private $questions;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * One User has One ForgotPassword.
     * @ORM\OneToOne(targetEntity="ForgotPassword", mappedBy="user", cascade={"persist", "remove"})
     */
    private $forgotPassword;

    /**
     * @var User|null
     * Many Users has One User.
     * @ORM\ManyToOne(targetEntity="User", inversedBy="referrals")
     * @ORM\JoinColumn(name="referrer", referencedColumnName="id")
     */
    private $referrer;

    /**
     * @var ArrayCollection
     *
     * One User has Many Users.
     * @ORM\OneToMany(targetEntity="User", mappedBy="referrer")
     */
    private $referrals;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $referralCode;

    /**
     * @var ArrayCollection
     *
     * One User has Many ProductDeliveryDetail.
     * @ORM\OneToMany(targetEntity="ProductDeliveryDetail", mappedBy="user")
     */
    private $productDeliveryDetails;

    public function __construct()
    {
        parent::__construct();

        $this->winProducts = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->referrals = new ArrayCollection();
        $this->productDeliveryDetails = new ArrayCollection();
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

    /**
     * @return mixed
     */
    public function getPotentialProducts()
    {
        return $this->potentialProducts;
    }

    /**
     * @param mixed $potentialProducts
     */
    public function setPotentialProducts($potentialProducts)
    {
        $this->potentialProducts = $potentialProducts;
    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param mixed $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * @return mixed
     */
    public function getForgotPassword()
    {
        return $this->forgotPassword;
    }

    /**
     * @param mixed $forgotPassword
     */
    public function setForgotPassword($forgotPassword): void
    {
        $this->forgotPassword = $forgotPassword;
    }

    /**
     * @return User|null
     */
    public function getReferrer(): ?User
    {
        return $this->referrer;
    }

    /**
     * @param User|null $referrer
     */
    public function setReferrer(?User $referrer): void
    {
        $this->referrer = $referrer;
    }

    /**
     * @return mixed
     */
    public function getReferrals()
    {
        return $this->referrals;
    }

    /**
     * @param ArrayCollection $referrals
     */
    public function setReferrals(ArrayCollection $referrals): void
    {
        $this->referrals = $referrals;
    }

    public function addReferral(User $referral) : void
    {
        $this->referrals->add($referral);
    }

    /**
     * @return string
     */
    public function getReferralCode(): string
    {
        return $this->referralCode;
    }

    /**
     * @param string $referralCode
     */
    public function setReferralCode(string $referralCode): void
    {
        $this->referralCode = $referralCode;
    }

    /**
     * @return ArrayCollection
     */
    public function getProductDeliveryDetails(): ArrayCollection
    {
        return $this->productDeliveryDetails;
    }

    /**
     * @param ArrayCollection $productDeliveryDetails
     */
    public function setProductDeliveryDetails(ArrayCollection $productDeliveryDetails): void
    {
        $this->productDeliveryDetails = $productDeliveryDetails;
    }

    public function toArray()
    {
        $stakeDetail = $this->getStakeDetail();

        return [
            "username" => $this->getUsername(),
            "stakes" => $stakeDetail instanceof StakeDetail ? $stakeDetail->getCount() : 0,
            "photo" => $this->photo ?: self::DEFAULT_PHOTO
        ];
    }

    public function getFullName()
    {
       return $this->lastName . ' ' . $this->firstName;
    }
}