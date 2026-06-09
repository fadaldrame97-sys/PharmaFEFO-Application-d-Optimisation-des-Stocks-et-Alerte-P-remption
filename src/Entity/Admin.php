<?php

declare(strict_types=1);

class Admin extends User
{
    public function __construct(
        int $id,
        string $email,
        string $password,
        string $role
    ) {
        parent::__construct(
            $id,
            $email,
            $password,
            $role
        );
    }
}