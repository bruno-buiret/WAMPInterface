<?php

namespace App\Tools;

use DomainException;

/**
 * Class PaginationTools
 *
 * @package App\Tools
 * @author Bruno Buiret <bruno.buiret@gmail.com>
 */
class PaginationTools
{
    /**
     * @var int The provided current page number.
     */
    protected $currentPage;

    /**
     * @var int The computed pages number.
     */
    protected $pagesNumber;

    /**
     * @var int The provided items per page number.
     */
    protected $itemsPerPageNumber;

    /**
     * @var int The provided items number.
     */
    protected $itemsNumber;

    /**
     * @var int The computed offset.
     */
    protected $offset;

    /**
     * PaginationTools constructor.
     *
     * @param int $currentPage
     * @param int $itemsNumber
     * @param int $itemsPerPageNumber
     */
    public function __construct(int $currentPage, int $itemsNumber, int $itemsPerPageNumber = 20)
    {
        if($itemsPerPageNumber <= 0)
        {
            throw new DomainException(sprintf(
                'Number of items per pages can\'t be lower than 1, got "%d".',
                $itemsPerPageNumber
            ));
        }

        // Initialize properties
        $this->currentPage = $currentPage;
        $this->itemsNumber = $itemsNumber;
        $this->itemsPerPageNumber = $itemsPerPageNumber;

        $this->update();
    }

    /**
     * Updates these tools.
     */
    protected function update()
    {
        $this->pagesNumber = (int) max(1, ceil($this->itemsNumber / $this->itemsPerPageNumber));
        $this->currentPage = (int) max(1, min($this->pagesNumber, $this->currentPage));
        $this->offset = ($this->currentPage - 1) * $this->itemsPerPageNumber;
    }

    /**
     * Gets the current page.
     *
     * @return int The current page.
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * Sets the current page.
     *
     * @param int $currentPage The current page.
     * @return \App\Tools\PaginationTools This pagination tools.
     */
    public function setCurrentPage(int $currentPage): PaginationTools
    {
        $this->currentPage = $currentPage;
        $this->update();

        return $this;
    }

    /**
     * Gets the items number.
     *
     * @return int The items number.
     */
    public function getItemsNumber(): int
    {
        return $this->itemsNumber;
    }

    /**
     * Sets the items number.
     *
     * @param int $itemsNumber The items number.
     * @return \App\Tools\PaginationTools This pagination tools.
     */
    public function setItemsNumber(int $itemsNumber): PaginationTools
    {
        $this->itemsNumber = $itemsNumber;
        $this->update();

        return $this;
    }

    /**
     * Gets the items per page number.
     *
     * @return int The items per page number.
     */
    public function getItemsPerPageNumber(): int
    {
        return $this->itemsPerPageNumber;
    }

    /**
     * Sets the items per page number.
     *
     * @param int $itemsPerPageNumber The items per page number.
     * @return \App\Tools\PaginationTools This pagination tools.
     */
    public function setItemsPerPageNumber(int $itemsPerPageNumber): PaginationTools
    {
        if($itemsPerPageNumber <= 0)
        {
            throw new DomainException(sprintf(
                'Number of items per pages can\'t be lower than 1, got "%s".',
                $itemsPerPageNumber
            ));
        }

        $this->itemsPerPageNumber = $itemsPerPageNumber;
        $this->update();

        return $this;
    }

    /**
     * Gets the computed pages number.
     *
     * @return int The computed pages number.
     */
    public function getPagesNumber()
    {
        return $this->pagesNumber;
    }

    /**
     * Gets the computed offset.
     *
     * @return int The computed offset.
     */
    public function getOffset()
    {
        return $this->offset;
    }
}