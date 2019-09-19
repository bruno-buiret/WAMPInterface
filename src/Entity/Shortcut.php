<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Shortcut
 *
 * @package App\Entity
 * @author Bruno Buiret <bruno.buiret@gmail.com>
 * @ORM\Entity(repositoryClass="Gedmo\Sortable\Entity\Repository\SortableRepository")
 * @ORM\EntityListeners({"App\EntityListener\TimestampableListener"})
 */
class Shortcut
{
    /**
     * @var int The shortcut's id.
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string The shortcut's title.
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

    /**
     * @var string The shortcut's sub title.
     * @ORM\Column(type="string", length=255)
     */
    protected $subTitle;

    /**
     * @var string The shortcut's color.
     * @ORM\Column(type="string", length=7)
     */
    protected $color;

    /**
     * @var string The shortcut's icon.
     * @ORM\Column(type="string", length=100)
     */
    protected $icon;

    /**
     * @var string The shortcut's URL.
     * @ORM\Column(type="string", length=255)
     */
    protected $url;

    /**
     * @var int The shortcut's position.
     * @ORM\Column(type="integer", options={"unsigned"})
     * @Gedmo\SortablePosition
     */
    protected $position;

    /**
     * @var \DateTime The alias' date of creation.
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime|null The alias' date of last update.
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastUpdatedAt;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this This shortcut.
     */
    public function setId(?int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this This shortcut.
     */
    public function setTitle(?string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubTitle(): ?string
    {
        return $this->subTitle;
    }

    /**
     * @param string $subTitle
     * @return $this This shortcut.
     */
    public function setSubTitle(?string $subTitle)
    {
        $this->subTitle = $subTitle;

        return $this;
    }

    /**
     * @return string
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @param string $color
     * @return $this This shortcut.
     */
    public function setColor(?string $color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return string
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return $this This shortcut.
     */
    public function setIcon(?string $icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return $this This shortcut.
     */
    public function setUrl(?string $url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return int
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return Shortcut
     */
    public function setPosition(?int $position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return $this This shortcut.
     */
    public function setCreatedAt(?DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastUpdatedAt(): ?DateTime
    {
        return $this->lastUpdatedAt;
    }

    /**
     * @param \DateTime|null $lastUpdatedAt
     * @return $this This shortcut.
     */
    public function setLastUpdatedAt(?DateTime $lastUpdatedAt): Shortcut
    {
        $this->lastUpdatedAt = $lastUpdatedAt;

        return $this;
    }
}