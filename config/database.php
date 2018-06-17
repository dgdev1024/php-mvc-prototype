<?php
    $GLOBALS['db'] = new database('test');

    $GLOBALS['db']->add_query('add_person', 'INSERT INTO people (name, age) VALUES (:name, :age)');
    $GLOBALS['db']->add_query('get_people', 'SELECT * FROM people');
    $GLOBALS['db']->add_query('search_by_name', 'SELECT * FROM people WHERE name LIKE :name');
?>