<?php

class LoginController{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository){
        $this->userRepository=$userRepository;
        session_start();
   }
}

