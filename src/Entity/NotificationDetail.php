<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="notification_detail")
 */
class NotificationDetail
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $news = false;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $noveltyAppearance = false;

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
     * @return bool
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * @param bool $news
     */
    public function setNews($news)
    {
        $this->news = $news;
    }

    /**
     * @return bool
     */
    public function getNoveltyAppearance()
    {
        return $this->noveltyAppearance;
    }

    /**
     * @param bool $noveltyAppearance
     */
    public function setNoveltyAppearance($noveltyAppearance)
    {
        $this->noveltyAppearance = $noveltyAppearance;
    }
}