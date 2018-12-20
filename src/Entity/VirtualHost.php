<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class VirtualHost
 *
 * @package App\Entity
 * @author Bruno Buiret <bruno.buiret@gmail.com>
 * @ORM\Entity(repositoryClass="App\Repository\VirtualHostRepository")
 * @ORM\EntityListeners({"App\EntityListener\TimestampableListener"})
 */
class VirtualHost
{
    /**
     * @var int The virtual host's id.
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="`id`", type="integer")
     */
    protected $id;

    /**
     * @var string The virtual host's name.
     * @ORM\Column(name="`name`", type="string", length=255)
     */
    protected $name;

    /**
     * @var string|null The virtual host's description.
     * @ORM\Column(name="`description`", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string The virtual host's host.
     * @ORM\Column(name="`host`", type="string", length=255)
     */
    protected $host;

    /**
     * @var int The virtual host's port.
     * @ORM\Column(name="`port`", type="smallint", options={"unsigned": true})
     */
    protected $port;

    /**
     * @var bool The virtual host's use of SSL.
     * @ORM\Column(name="`ssl`", type="boolean", options={"default": false})
     */
    protected $ssl;

    /**
     * @var bool The virtual host's hidden status.
     * @ORM\Column(name="`hidden`", type="boolean", options={"default": false})
     */
    protected $hidden;

    /**
     * @var \DateTime The virtual host's date of creation.
     * @ORM\Column(name="`created_at`", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime|null The virtual host's date of last update.
     * @ORM\Column(name="`last_updated_at`", type="datetime", nullable=true)
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
     * @return self This virtual host.
     */
    public function setId(int $id): VirtualHost
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self This virtual host.
     */
    public function setName(string $name): VirtualHost
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return self This virtual host.
     */
    public function setDescription(?string $description): VirtualHost
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getHost(): ?string
    {
        return $this->host;
    }

    /**
     * @param string $host
     * @return self This virtual host.
     */
    public function setHost(string $host): VirtualHost
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @return int
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * @param int $port
     * @return self This virtual host.
     */
    public function setPort(int $port): VirtualHost
    {
        $this->port = $port;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSsl(): ?bool
    {
        return $this->ssl;
    }

    /**
     * @param bool $ssl
     * @return self This virtual host.
     */
    public function setSsl(bool $ssl): VirtualHost
    {
        $this->ssl = $ssl;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return sprintf(
            '%s://%s:%u/',
            $this->ssl ? 'https' : 'http',
            $this->host,
            $this->port
        );
    }

    /**
     * @return bool
     */
    public function isHidden(): ?bool
    {
        return $this->hidden;
    }

    /**
     * @param bool $hidden
     * @return self This virtual host.
     */
    public function setHidden(bool $hidden): VirtualHost
    {
        $this->hidden = $hidden;

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
     * @return self This virtual host.
     */
    public function setCreatedAt(DateTime $createdAt): VirtualHost
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
     * @return self This virtual host.
     */
    public function setLastUpdatedAt(?DateTime $lastUpdatedAt): VirtualHost
    {
        $this->lastUpdatedAt = $lastUpdatedAt;

        return $this;
    }
}
