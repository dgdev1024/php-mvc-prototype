<?php
    /**
     * A static class for rendering views.
     */
    class view {
        /**
         * Renders the given view with the given parameters.
         * 
         * @param string $view_file The view file to render.
         * @param array $params The view parameters.
         */
        public static function render (string $view_file, array $params) {
            // Make sure a file was provided.
            if ($view_file === '') {
                throw new Exception('Did not specify a view file.');
            }

            // Iterate through the given parameters array. Delcare a
            // standalone variable named after the key, and assign it to the
            // value.
            foreach ($params as $key => $value) {
                $$key = $value;
            }

            // Check to see if a file extension was provided.
            $view_file_parts = explode('.', $view_file);
            if (count($view_file_parts) > 1 && end($view_file_parts) === 'php') {
                require_once 'views/' . $view_file;
            } else {
                require_once 'views/' . $view_file . '.php';
            }
        }
    }
?>