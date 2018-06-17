<?php
    router::on('GET', '', function ($req) {
        people_controller::get_people();
    });

    router::on('GET', 'search', function ($req) {
        people_controller::search_by_name($req['query']['name']);
    });

    router::on('POST', 'add', function ($req) {
        people_controller::add_person(
            $req['body']['name'],
            $req['body']['age']
        );
    });

    router::on('GET', 'test', function ($req, $next) {
        echo 'First Function.<br />';
        $next(null);
    }, function ($req, $next) {
        echo 'Second Function.<br />';
        $req['rocker'] = 'roller';
        $next(null, $req);
    }, function ($req) {
        echo '<pre>';
        print_r($req);
        echo '</pre>';
    });
?>