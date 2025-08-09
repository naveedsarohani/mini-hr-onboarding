<?php

use Core\Database;

return new class {
    public function up()
    {
        $query = "create table if not exists users (
            id bigint unsigned auto_increment primary key,
            name varchar(100) not null,
            email varchar(100) unique not null,
            password varchar(255) not null,
            created_at timestamp default current_timestamp
        );";

        Database::instance()->query($query);
    }
};
