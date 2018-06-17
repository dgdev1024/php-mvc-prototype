<?php
    /**
     * A class for mapping routes to functions and processing the
     * current route.
     */
    class router {
        private static $routes = array();   /** Our mapped routes. */

        /**
         * Maps a new route to the given URI string, if one hasn't
         * already been mapped.
         * 
         * @param string $uri The URI string to which to map the route.
         */
        private static function add_route (string $uri) {
            if (array_key_exists($uri, self::$routes) === false) {
                self::$routes[$uri] = new route($uri);
            }
        }

        /**
         * Maps a function to the given URI, which will run when the route is
         * hit with the given HTTP request.
         * 
         * @param string $method The HTTP request to be expected.
         * @param string $uri The URI to which the function will be mapped.
         * @param callable $functions The functions to run when the route is hit.
         */
        public static function on (string $method, string $uri, callable ...$functions) {
            self::add_route($uri);
            self::$routes[$uri]->map_function($method, ...$functions);
        }

        /**
         * Iterates through all mapped routes, running the first matched
         * route.
         * 
         * @return boolean True if a route was matched.
         */
        public static function run () {
            foreach (self::$routes as $route) {
                if ($route->process(CONFIG['uri-parts']) === true) {
                    return true;
                }
            }

            echo 'Cannot ' . CONFIG['method'] . ' "' . CONFIG['uri'] . '".';
            return false;
        }
    }
?>