<?php require_once 'views/includes/header.php' ?>

<h1>
    List of People
</h1>

<?php
    if (count($people) === 0) {
        echo '<em>There are no people, yet.</em>';
    }
?>

<ul>
    <?php
        foreach ($people as $person) {
            echo '<li>' . $person['name'] . 
                ' - age ' . $person['age'] . '</li>';
        }
    ?>
</ul>

<h1>
    Add Person
</h1>

<form action="add" method="POST">
    <label for="name">Name: </label>
    <input type="text" id="name" name="name" required />
    <label for="age">Age: </label>
    <input type="number" id="age" name="age" required />
    <button type="submit">Add Person</button>
</form>

<h1>
    Search By Name
</h1>

<form action="search" method="GET">
    <label for="name">Name: </label>
    <input type="text" id="name" name="name" required />
    <button type="submit">Search</button>
</form>

<?php require_once 'views/includes/footer.php' ?>