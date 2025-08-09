<?php

namespace Database\Seeders;

use Core\Database;

class UserSeeder
{
    public function run()
    {
        $db = Database::instance();

        $user = ['name' => 'Verge Systems', 'email' => 'hr@web.hr', 'password' => password_hash("webhr", PASSWORD_DEFAULT)];
        $db->query("insert into users (name, email, password) values (:name, :email, :password)", $user);
    }
}