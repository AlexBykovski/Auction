<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="referral_system")
 */
class ReferralSystem
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $percentFromReferral = 0;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getPercentFromReferral(): int
    {
        return $this->percentFromReferral;
    }

    /**
     * @param int $percentFromReferral
     */
    public function setPercentFromReferral(int $percentFromReferral): void
    {
        $this->percentFromReferral = $percentFromReferral;
    }
}