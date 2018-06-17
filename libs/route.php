<?php
    /**
     * A class for storing routes and their HTTP method functions.
     */
    class route {
        private $uri = '';              /** The route's URI string. */
        private $uri_parts = array();   /** The route's URI parts. */
        private $uri_methods = array(); /** The route's HTTP method functions. */

        /**
         * Gets an array of the request headers sent along with the
         * HTTP request.
         * 
         * @return array The HTTP request headers.
         */
        private function get_request_headers () {
            $headers = array();

            foreach ($_SERVER as $key => $value) {
                if (substr($key, 0, 5) !== 'HTTP_') {
                continue;
                }

                $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
                $headers[$header] = $value;
            }

            return $headers;
        }

        /**
         * Class constructor.
         * 
         * @param string $uri The route's URI string.
         */
        public function __construct (string $uri) {
            $this->uri = $uri;
            $this->uri_parts = explode('/', $uri);
        }

        /**
         * Assigns the given function to the HTTP request method string given.
         * 
         * @param string $method The HTTP method to assign the function to.
         * @param callable $functions The functions to be mapped.
         */
        public function map_function (string $method, callable ...$functions) {
            if (count($functions) === 0) {
                throw new Exception("Missing response function(s)!");
            }

            $method = strtoupper($method);

            switch ($method) {
                case 'GET':
                case 'POST':
                case 'PUT':
                case 'PATCH':
                case 'DELETE':
                    $this->uri_methods[$method] = $functions;
                    break;
                default:
                    throw new Exception("'$method' is not a valid HTTP method.");
            }
        }

        /**
         * Processes this route's URL parts to see if the given array of URI
         * parts matches. If they do, runs the function mapped to the given
         * HTTP method.
         * 
         * @param array $uri_parts The URI parts given.
         * 
         * @return boolean True if processing is successful.
         */
        public function process (array $uri_parts) {
            // Variable Setup...
            $request = array();
            $request_method = CONFIG['method'];
            $part_mismatch = false;
            $wildcard_found = false;
            $route_part_count = count($this->uri_parts);
            $uri_part_count = count($uri_parts);
            $route_params = array();

            // Make sure there is a function mapped to the HTTP method given.
            if (isset($this->uri_methods[$request_method]) === false) {
                return false;
            }

            // Iterate through each part of the route's URI.
            foreach (range(0, $route_part_count - 1) as $index) {
                // Make sure we don't run out of parts yet.
                if ($index === $uri_part_count) {
                    $part_mismatch = true;
                    break;
                }

                // Get the part mapped to the current index from both arrays.
                // Make them lowercase.
                $route_part = strtolower($this->uri_parts[$index]);
                $uri_part = strtolower($uri_parts[$index]);

                // Check for the wildcard symbol. If we find that, store this and the
                // remaining URI parts in an array and break.
                if ($route_part === '*') {
                    $wildcard_found = true;
                    $request['wildcards'] = array_slice($uri_parts, $index);
                    break;
                }

                // Check for a colon at the start of the route part. That indicates
                // a route parameter. Map the part from the URI given to the name
                // of the parameter and store it.
                else if (strlen($route_part) > 1 && $route_part[0] === ':') {
                    // Get the key and value.
                    $key = substr($route_part, 1);
                    $value = $uri_part;

                    // Add the pair to the route params.
                    $route_params[$key] = $value;
                }

                // Otherwise, check to see if the parts match.
                else {
                    if ($uri_part !== $route_part) {
                        $part_mismatch = true;
                        break;
                    }
                }
            }

            // Check to see if there was a part mismatch.
            if ($part_mismatch === true) {
                return false;
            }

            // Check to see if the URI parts length given was too long.
            if ($wildcard_found === false && $uri_part_count > $route_part_count) {
                return false;
            }

            // Construct our request object. By default, add the URL and query
            // parameters, and the HTTP request headers.
            $request['params'] = $route_params;
            $request['query'] = $_GET;
            $request['headers'] = $this->get_request_headers();
      
            // In the case of POST, PUT, and PATCH requests, add the request body,
            // too.
            if ($request_method === 'POST' || 
                $request_method === 'PUT' || 
                $request_method === 'PATCH') {
              $request['body'] = $_POST;
            }

            $next_wrapper = function ($function_index, $methods, $request, $wrapper) {
                return function ($err, $req = null) use ($function_index, $methods, $request, $wrapper) {
                    if ($err !== null && $err !== '') {
                        throw new Exception("Route Exception: " . $err);
                    }

                    if ($req !== null) {
                        if ($request['headers'] !== $req['headers'] ||
                            $request['query'] !== $req['query'] ||
                            $request['params'] !== $req['params'] ||
                            (isset($request['body']) && $request['body'] !== $req['body'])) {
                                throw new Exception("Route exception: Request Object Compromised!");
                            }
                        $request = $req;
                    }

                    $idx = $function_index + 1;
                    
                    if (array_key_exists($idx, $methods)) {
                        $methods[$idx]($request, $wrapper($idx, $methods, $request, $wrapper));
                    }
                };
            };

            $methods = $this->uri_methods[$request_method];
            $methods[0]($request, $next_wrapper(0, $methods, $request, $next_wrapper));
            
            return true;
        }
    }
?>