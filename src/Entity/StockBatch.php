<?php
// src/Entity/StockBatch.php
namespace PharmaFEFO\Entity;

use DateTime;
use PharmaFEFO\Enum\BatchStatus;

class StockBatch {
    private ?int $id = null;
    private int $productId;
    private string $lotNumber;
    private int $quantity;
    private DateTime $expirationDate;
    private BatchStatus $status;

    // Getters ou Setters simples
    public function getId(): ?int { return $this->id; }
    
    public function getLotNumber(): string { return $this->lotNumber; }
    public function setLotNumber(string $lotNumber): self {
        $this->lotNumber = $lotNumber;
        return $this;
    }

    public function getQuantity(): int { return $this->quantity; }
    public function setQuantity(int $quantity): self {
        $this->quantity = $quantity;
        return $this;
    }

    public function getExpirationDate(): DateTime { return $this->expirationDate; }
    public function setExpirationDate(DateTime $expirationDate): self {
        $this->expirationDate = $expirationDate;
        return $this;
    }

    public function getStatus(): BatchStatus { return $this->status; }
    public function setStatus(BatchStatus $status): self {
        $this->status = $status;
        return $this;
    }
}