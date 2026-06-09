<?php

declare(strict_types=1);

class User
{
    private int $id;
    private string $email;
    private string $password;
    private string $role;


    public function __construct(
        int $id,
        string $email,
        string $password,
        string $role
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }



}