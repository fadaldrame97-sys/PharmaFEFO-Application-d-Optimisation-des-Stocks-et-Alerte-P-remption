<?php

declare(strict_types=1);

class StockBatch
{
    private int $id;
    private int $productId;
    private string $lotNumber;
    private int $quantity;
    private DateTime $expirationDate;
    private string $status;

    public function __construct(
    int $productId,
    string $lotNumber,
    int $quantity,
    DateTime $expirationDate,
    string $status = 'AVAILABLE'
) {
    $this->productId = $productId;
    $this->lotNumber = $lotNumber;
    $this->quantity = $quantity;
    $this->expirationDate = $expirationDate;
    $this->status = $status;
}


     public function getId(): int
    {
        return $this->id;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getLotNumber(): string
    {
        return $this->lotNumber;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getExpirationDate(): DateTime
    {
        return $this->expirationDate;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }




}