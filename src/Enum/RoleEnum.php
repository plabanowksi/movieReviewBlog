<?php

namespace App\Enum;

class UserRole
{
    const ADMIN = 'ROLE_ADMIN';
    const MODERATOR = 'ROLE_MOD';
    const USER = 'ROLE_USER';
    
    private function __construct() 
    {

    }
}