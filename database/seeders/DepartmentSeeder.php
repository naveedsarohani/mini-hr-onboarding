<?php

namespace Database\Seeders;

use Core\Database;

class DepartmentSeeder
{
    public function run()
    {
        $db = Database::instance();

        $data = ['Information Technology', 'Human Resource', 'Finance'];
        array_map(function ($dept) use ($db) {
            $db->query("insert into departments (name) values (:name)", [$dept]);
        }, $data);
    }
}
