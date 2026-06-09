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


}