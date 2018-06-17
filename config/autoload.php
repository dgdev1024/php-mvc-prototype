<?php
    /**
     * Attempts to load the given class file from the given folder.
     * 
     * @param string $class The class file to load.
     * @param string $folder The folder to load the class from.
     */
    function load_class_from (string $class, string $folder) {
        if (file_exists($folder . '/' . $class . '.php')) {
            require_once $folder . '/' . $class . '.php';
        }
    }

    // Autoload our classes.
    spl_autoload_register(function (string $class) {
        load_class_from($class, 'models');
        load_class_from($class, 'views');
        load_class_from($class, 'controllers');
        load_class_from($class, 'libs');
    });
?>