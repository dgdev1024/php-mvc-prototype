<?php
    class people_model {
        public static function add_person (string $name, int $age) {
            $GLOBALS['db']->execute_query('add_person', array(
                'name' => $name,
                'age' => $age
            ));
        }

        public static function get_people () {
            $stmt = $GLOBALS['db']->execute_query('get_people');
            return $stmt->fetchAll();
        }

        public static function search_by_name (string $name) {
            $stmt = $GLOBALS['db']->execute_query('search_by_name', array(
                'name' => '%' . $name . '%'
            ));

            return $stmt->fetchAll();
        }
    }
?>