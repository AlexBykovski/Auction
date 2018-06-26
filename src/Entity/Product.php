<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table(name="product")
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $mainPhoto;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $photos;

    /**
     * @ORM\Column(type="float", options={"default" : 100}, nullable=false)
     */
    private $cost = 100;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $characteristics;

    /**
     * @ORM\Column(type="text")
     */
    private $conditions;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $categories;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $startAt;

    /**
     * Many Product have One User.
     * @ORM\ManyToOne(targetEntity="User", inversedBy="winProducts")
     * @ORM\JoinColumn(name="winner_id", referencedColumnName="id")
     */
    private $winner;

    /**
     * Many Product have One User.
     * @ORM\ManyToOne(targetEntity="User", inversedBy="potentialProducts")
     * @ORM\JoinColumn(name="potential_winner_id", referencedColumnName="id")
     */
    private $potentialWinner;

    /**
     * @var ArrayCollection
     *
     * One Product has Many ProductDeliveryDetail.
     * @ORM\OneToMany(targetEntity="ProductDeliveryDetail", mappedBy="product")
     */
    private $deliveryDetails;

    /**
     * One Product has Many StakeExpenses.
     * @ORM\OneToMany(targetEntity="StakeExpense", mappedBy="product", cascade={"remove"})
     */
    private $stakeExpenses;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $isProcessed = false;

    /**
     * One Product has One ProductTimer.
     * @ORM\OneToOne(targetEntity="ProductTimer", mappedBy="product", cascade={"persist"})
     * @ORM\JoinColumn(name="timer_id", referencedColumnName="id", onDelete="cascade")
     */
    private $timer;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endAt;

    /**
     * One Product has Many AutoStakes.
     * @ORM\OneToMany(targetEntity="AutoStake", mappedBy="auction", cascade={"remove"})
     */
    private $autoStakes;

    /**
     * @ORM\Column(type="float", options={"default" : 0}, nullable=false)
     */
    private $buyCost = 0;

    static $allCategories = [
        'apple' => 'apple',
        //'детские товары' => 'child',
        'игровые приставки и аксессуары' => 'gaming_сonsoles_accessories',
        'ноутбуки, планшеты и аксессуары' => 'laptop_tablet_accessories',
        'подарочные карты и сертификаты' => 'present_cards_sertificates',
        'телефоны и аксессуары' => 'phones_accessories',
        'товары для дома' => 'home',
        'фото- и видеотехника' => 'photo_video',
    ];

    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->stakeExpenses = new ArrayCollection();
        $this->deliveryDetails = new ArrayCollection();
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getMainPhoto()
    {
        return $this->mainPhoto;
    }

    /**
     * @param mixed $mainPhoto
     */
    public function setMainPhoto($mainPhoto)
    {
        $this->mainPhoto = $mainPhoto;
    }

    /**
     * @return array
     */
    public function getPhotos()
    {
        if(!$this->photos){
            return [];
        }

        return unserialize($this->photos);
    }

    /**
     * @param array $photos
     */
    public function setPhotos(array $photos)
    {
        $this->photos = serialize($photos);
    }

    public function addPhoto(string $photo){
        $photos = $this->getPhotos();
        $photos[] = $photo;

        $this->setPhotos($photos);
    }

    /**
     * @return integer
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param mixed $cost
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCharacteristics()
    {
        return $this->characteristics;
    }

    /**
     * @param mixed $characteristics
     */
    public function setCharacteristics($characteristics)
    {
        $this->characteristics = $characteristics;
    }

    /**
     * @return mixed
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @param mixed $conditions
     */
    public function setConditions($conditions)
    {
        $this->conditions = $conditions;
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        if(!$this->categories){
            return [];
        }

        return unserialize($this->categories);
    }

    /**
     * @param mixed $categories
     */
    public function setCategories($categories)
    {
        $this->categories = serialize($categories);
    }

    /**
     * @return mixed
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * @param DateTime $startAt
     */
    public function setStartAt(DateTime $startAt)
    {
        $this->startAt = $startAt;
    }

    /**
     * @return mixed
     */
    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * @param mixed $winner
     */
    public function setWinner($winner)
    {
        $this->winner = $winner;
    }

    /**
     * @return ArrayCollection
     */
    public function getDeliveryDetails(): ArrayCollection
    {
        return $this->deliveryDetails;
    }

    /**
     * @param ArrayCollection $deliveryDetails
     */
    public function setDeliveryDetails(ArrayCollection $deliveryDetails): void
    {
        $this->deliveryDetails = $deliveryDetails;
    }

    public function addDeliveryDetail(ProductDeliveryDetail $deliveryDetail)
    {
        if(!($this->deliveryDetails instanceof ArrayCollection)){
            $this->setDeliveryDetails(new ArrayCollection());
        }

        $this->deliveryDetails->add($deliveryDetail);
    }

    /**
     * @return ArrayCollection
     */
    public function getStakeExpenses()
    {
        return $this->stakeExpenses;
    }

    /**
     * @param ArrayCollection $stakeExpenses
     */
    public function setStakeExpenses($stakeExpenses)
    {
        $this->stakeExpenses = $stakeExpenses;
    }

    /**
     * @return mixed
     */
    public function getIsProcessed()
    {
        return $this->isProcessed;
    }

    /**
     * @param mixed $isProcessed
     */
    public function setIsProcessed($isProcessed)
    {
        $this->isProcessed = $isProcessed;
    }

    /**
     * @return ProductTimer
     */
    public function getTimer()
    {
        return $this->timer;
    }

    /**
     * @param ProductTimer $timer
     */
    public function setTimer($timer)
    {
        $this->timer = $timer;
    }

    /**
     * @return mixed
     */
    public function getEndAt()
    {
        return $this->endAt;
    }

    /**
     * @param mixed $endAt
     */
    public function setEndAt($endAt)
    {
        $this->endAt = $endAt;
    }

    /**
     * @return mixed
     */
    public function getPotentialWinner()
    {
        return $this->potentialWinner;
    }

    /**
     * @param mixed $potentialWinner
     */
    public function setPotentialWinner($potentialWinner)
    {
        $this->potentialWinner = $potentialWinner;
    }

    /**
     * @return mixed
     */
    public function getBuyCost()
    {
        return $this->buyCost;
    }

    /**
     * @param mixed $buyCost
     */
    public function setBuyCost($buyCost): void
    {
        $this->buyCost = $buyCost;
    }


    public function toArrayMainPage($withAutoStake = false)
    {
        $now = new DateTime();

        $parameters = [
            "id" => $this->id,
            "mainPhoto" => $this->mainPhoto,
            "name" => $this->name,
            "cost" => $this->cost,
            "isProcessing" => !$this->endAt && $this->startAt <= $now,
            "isFinish" => $this->endAt instanceof DateTime,
            "isSoon" => !$this->endAt && $this->startAt > $now,
        ];

        if($parameters["isFinish"]){
            $parameters["endAt"] = $this->endAt->format("d.m.Y H:i");
            $parameters["winner"] = $this->winner ? $this->winner->getUsername() : null;
        }
        elseif($parameters["isSoon"]){
            $parameters["startAt"] = ($this->startAt->getTimestamp() - $now->getTimestamp()) * 1000;
        }
        else{
            $parameters["timeEnd"] = $this->getTimer()->getEndTimeInMS() - $now->getTimestamp() * 1000;
            $parameters["lastUser"] = $this->potentialWinner instanceof User ? $this->potentialWinner->getUsername() : "";
        }

        if($withAutoStake){
            $parameters["stakes"] = [];

            $expenses = $this->stakeExpenses->getValues();

            usort($expenses, function(StakeExpense $a, StakeExpense $b){
                return $a->getCreatedAt() < $b->getCreatedAt();
            });

            $expenses =  array_slice($expenses, 0, 5);
            $index = 0;

            /** @var StakeExpense $stakeExpense */
            foreach($expenses as $stakeExpense){
                if(($cost = $this->cost - ($index * 0.1)) < 100){
                    break;
                }

                $parameters["stakes"][] = [
                    "time" => $stakeExpense->getCreatedAt()->format("H:i:s"),
                    "username" => $stakeExpense->getStakeDetail()->getUser()->getUsername(),
                    "cost" => number_format($this->cost - ($index * 0.1), 2, '.', ''),
                ];

                ++$index;
            }
        }

        return $parameters;
    }

    /**
     * @return mixed
     */
    public function getAutoStakes()
    {
        return $this->autoStakes;
    }

    /**
     * @param mixed $autoStakes
     */
    public function setAutoStakes($autoStakes)
    {
        $this->autoStakes = $autoStakes;
    }

    public function getAllCategories()
    {
        return self::$allCategories;
    }
}