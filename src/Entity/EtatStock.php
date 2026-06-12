<?php

declare(strict_types=1);

class EtatStock
{
    private int $id;
    private int $batchId;
    private string $status;
    private DateTime $checkedAt;

    public function __construct(
        int $id,
        int $batchId,
        string $status,
        DateTime $checkedAt
    ) {
        $this->id = $id;
        $this->batchId = $batchId;
        $this->status = $status;
        $this->checkedAt = $checkedAt;
    }

    public function getId(): int { return $this->id; }
    public function getBatchId(): int { return $this->batchId; }
    public function getStatus(): string { return $this->status; }
    public function getCheckedAt(): DateTime { return $this->checkedAt; }

    public function setStatus(string $status): void { $this->status = $status; }
    public function setCheckedAt(DateTime $checkedAt): void { $this->checkedAt = $checkedAt; }
}
