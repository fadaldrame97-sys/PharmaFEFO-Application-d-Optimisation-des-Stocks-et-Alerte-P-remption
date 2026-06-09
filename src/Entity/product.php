<?php

declare(strict_types=1);

class Product
{
    private int $id;
    private string $name;
    private string $code;
    private string $description;

    public function __construct(
        int $id,
        string $name,
        string $code,
        string $description
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->code = $code;
        $this->description = $description;
    }
     public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }


}