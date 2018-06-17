<?php
    class people_controller {
        public static function get_people () {
            $people = people_model::get_people();

            view::render('pages/people', array(
                'title' => 'List of People',
                'people' => $people
            ));
        }

        public static function add_person (string $name, int $age) {
            people_model::add_person($name, $age);
            header('Location: ' . CONFIG['domain']);
        }

        public static function search_by_name (string $name) {
            $people = people_model::search_by_name($name);

            view::render('pages/people', array(
                'title' => 'Search Results',
                'people' => $people
            ));
        }
    }
?>