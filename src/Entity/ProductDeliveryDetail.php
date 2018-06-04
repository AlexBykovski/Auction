<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="product_delivery_detail")
 */
class ProductDeliveryDetail
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message = "Проверьте корректность ввода", groups={"create_order"})
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message = "Проверьте корректность ввода", groups={"create_order"})
     */
    private $postIndex;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message = "Проверьте корректность ввода", groups={"create_order"})
     */
    private $country;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message = "Проверьте корректность ввода", groups={"create_order"})
     */
    private $city;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message = "Проверьте корректность ввода", groups={"create_order"})
     */
    private $address;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message = "Проверьте корректность ввода", groups={"create_order"})
     */
    private $phone;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotNull(message = "Вы должны выбрать способ оплаты для продолжения", groups={"create_order"})
     */
    private $payment;

    /**
     * @var Product
     *
     * Many ProductDeliveryDetails have One Product.
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="deliveryDetails")
     * @ORM\JoinColumn(name="product", referencedColumnName="id", onDelete="SET NULL")
     */
    private $product;

    /**
     * @var User
     *
     * Many ProductDeliveryDetails have One User.
     * @ORM\ManyToOne(targetEntity="User", inversedBy="productDeliveryDetails")
     * @ORM\JoinColumn(name="user", referencedColumnName="id", onDelete="SET NULL")
     */
    private $user;

    /**
     * @ORM\Column(type="float", options={"default" : 0}, nullable=false)
     */
    private $cost = 0;

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
    public function getPostIndex()
    {
        return $this->postIndex;
    }

    /**
     * @param mixed $postIndex
     */
    public function setPostIndex($postIndex)
    {
        $this->postIndex = $postIndex;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param mixed $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * @return mixed
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @param mixed $payment
     */
    public function setPayment($payment): void
    {
        $this->payment = $payment;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param mixed $cost
     */
    public function setCost($cost): void
    {
        $this->cost = $cost;
    }

    public function setUserDeliveryDetail(UserDeliveryDetail $deliveryDetail)
    {
        $this->address = $deliveryDetail->getAddress();
        $this->city = $deliveryDetail->getCity();
        $this->country = $deliveryDetail->getCountry();
        $this->name = $deliveryDetail->getName();
        $this->phone = $deliveryDetail->getPhone();
        $this->postIndex = $deliveryDetail->getPostIndex();
        $this->note = $deliveryDetail->getNote();
    }
}