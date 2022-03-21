<?php


namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class ProductPagination
{
    /**
     * @var int
     * @Assert\PositiveOrZero
     */
    private $firstResult;

    /**
     * @var int
     * @Assert\Positive
     */
    private $maxResults;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Choice({"id", "name","description", "price", "currency"})
     */
    private $order;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Choice({"asc", "desc"})
     */
    private $sort;
    /**
     * @var int
     */
    private $totalResults;

    /**
     * @return int
     */
    public function getFirstResult(): int
    {
        return $this->firstResult;
    }

    /**
     * @param int $firstResult
     */
    public function setFirstResult(int $firstResult): void
    {
        $this->firstResult = $firstResult;
    }

    /**
     * @return int
     */
    public function getMaxResults(): int
    {
        return $this->maxResults;
    }

    /**
     * @param int $maxResults
     */
    public function setMaxResults(int $maxResults): void
    {
        $this->maxResults = $maxResults;
    }

    /**
     * @return string
     */
    public function getOrder(): string
    {
        return $this->order;
    }

    /**
     * @param string $order
     */
    public function setOrder(string $order): void
    {
        $this->order = $order;
    }

    /**
     * @return string
     */
    public function getSort(): string
    {
        return $this->sort;
    }

    /**
     * @param string $sort
     */
    public function setSort(string $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * @return int
     */
    public function getTotalResults(): int
    {
        return $this->totalResults;
    }

    /**
     * @param int $totalResults
     */
    public function setTotalResults(int $totalResults): void
    {
        $this->totalResults = $totalResults;
    }

}