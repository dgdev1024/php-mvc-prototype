<?php
    function echo_href ($href) {
        echo CONFIG['domain'] . '/' . $href;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="<?php echo_href('/public/css/style.css'); ?>">
        <title>
            <?php
                if (isset($title)) {
                    echo $title;
                } 
                else if (isset(CONFIG['site-title'])) {
                    echo CONFIG['site-title'];
                }
                else {
                    echo 'Dennis Griffin\'s MVC';
                }
            ?>
        </title>
    </head>
    <body>