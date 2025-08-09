<?php

use Core\Database;

return new class {
    public function up()
    {
        $query = "create table if not exists resumes (
            id bigint auto_increment primary key,
            name varchar(100) not null,
            drive_id varchar(255) not null unique,
            path varchar(255) not null,
            employee_id bigint unsigned,
            created_at timestamp default current_timestamp,

            foreign key(employee_id) references employees(id)
                on update cascade on delete cascade
        );";

        Database::instance()->query($query);
    }
};
