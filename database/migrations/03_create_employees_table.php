<?php

use Core\Database;

return new class {
    public function up()
    {
        $query =  "create table if not exists employees (
            id bigint unsigned auto_increment primary key,
            name varchar(100) not null,
            email varchar(100) unique not null,
            department_id  bigint unsigned,
            manager varchar(255) not null,
            hire_date date not null,
            created_at timestamp default current_timestamp,

            foreign key(department_id) references departments(id)
                on update cascade on delete cascade
        );";

        Database::instance()->query($query);
    }
};
