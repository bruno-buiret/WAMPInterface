<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Alias
 *
 * @package App\Entity
 * @author Bruno Buiret <bruno.buiret@gmail.com>
 * @ORM\Table(name="alias")
 * @ORM\Entity(repositoryClass="App\Repository\AliasRepository")
 * @ORM\EntityListeners({"App\EntityListener\TimestampableListener"})
 */
class Alias
{
    /**
     * @var int The alias' id.
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="`id`", type="integer")
     */
    protected $id;

    /**
     * @var string The alias' name.
     * @ORM\Column(name="`name`", type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\Column(name="`description`", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string The alias' description.
     * @ORM\Column(name="`url`", type="string", length=255)
     */
    protected $url;

    /**
     * @var string The alias' path.
     * @ORM\Column(name="`path`", type="text")
     */
    protected $path;

    /**
     * @var bool The alias' hidden status.
     * @ORM\Column(name="`hidden`", type="boolean")
     */
    protected $hidden;

    /**
     * @var \DateTime The alias' date of creation.
     * @ORM\Column(name="`created_at`", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime|null The alias' date of last update.
     * @ORM\Column(name="`last_updated_at`", type="datetime", nullable=true)
     */
    protected $lastUpdatedAt;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return self This alias.
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return self This alias.
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     * @return self This alias.
     */
    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param string|null $path
     * @return self This alias.
     */
    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isHidden(): ?bool
    {
        return $this->hidden;
    }

    /**
     * @param bool|null $hidden
     * @return self This alias.
     */
    public function setHidden(?bool $hidden): self
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return self This alias.
     */
    public function setCreatedAt(DateTime $createdAt): self
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
     * @return self This alias.
     */
    public function setLastUpdatedAt(?DateTime $lastUpdatedAt): self
    {
        $this->lastUpdatedAt = $lastUpdatedAt;

        return $this;
    }
}
