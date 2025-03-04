<?php
namespace App\Models;

use App\Enums\Role;
use DateTime;

class User
{
    public int $id;
    public Role $role; //naar enum
    public string $username;
    public string $password;
    public string $email;
    public ?string $image; //
    public ?string $phone;
    public ?string $fullname;
    public ?DateTime $registration_date;

}


