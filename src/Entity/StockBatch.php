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
        int $id,
        int $productId,
        string $lotNumber,
        int $quantity,
        DateTime $expirationDate,
        string $status
    ) {
        $this->id = $id;
        $this->productId = $productId;
        $this->lotNumber = $lotNumber;
        $this->quantity = $quantity;
        $this->expirationDate = $expirationDate;
        $this->status = $status;
    }




}