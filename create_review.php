<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>dblabb3</title>
</head>
<body>
<?php
include('view/header.php');
include('dblabb2.php');

$db = new DB();

// Lägg till itemtype
if (isset($_POST['addType'])) {
    // query för att lägga till name i itemtypes
    $query = "INSERT INTO itemtypes(name)
              VALUES (:name)";
    // filtrera input
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);

    $sth = $db->prepare($query);

    if ($sth->execute(array(':name' => $name))) {
        echo "<h4>Itemtype added</h4>";
    } else {
        // om något går fel skriv ut PDO felmeddelande
        echo "<h4>Error</h4>";
        echo "<pre>" . print_r($sth->errorInfo(), 1) . "</pre>";
    }
}

// Lägg till items
if (isset($_POST['addItem'])) {
    // query för att lägga till items
    $query = "INSERT INTO items(type, description)
              VALUES (:type, :description)";

    // filtrera input, notera sanitize int
    $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_NUMBER_INT);
    $desc = filter_input(INPUT_POST, 'desc', FILTER_SANITIZE_SPECIAL_CHARS);

    // array med värden för prep. statement
    $values = array(':type' => $type,
                    ':description' => $desc);

    $sth = $db->prepare($query);

    if ($sth->execute($values)) {
        echo "<h4>Item added</h4>";
    } else {
        // om något går fel skriv ut PDO felmeddelande
        echo "<h4>Error</h4>";
        echo "<pre>" . print_r($sth->errorInfo(), 1) . "</pre>";
    }
}

// ta bort itemtypes
if (isset($_GET['delType'])) {
    $id = filter_input(INPUT_GET, 'delType', FILTER_SANITIZE_NUMBER_INT);

    $query = "DELETE FROM itemtypes WHERE id=(:id)";

    $sth = $db->prepare($query);

    if ($sth->execute(array(':id' => $id))) {
        echo "<h4>Item type with id: " . $id . " deleted";
    } else {
        echo "<h4>Error</h4>";
        echo "<pre>" . print_r($sth->errorInfo(), 1) . "</pre>";
    }
}

// ta bort items
if (isset($_GET['delItem'])) {
    $id = filter_input(INPUT_GET, 'delItem', FILTER_SANITIZE_NUMBER_INT);

    $query = "DELETE FROM items WHERE id=(:id)";

    $sth = $db->prepare($query);

    if ($sth->execute(array(':id' => $id))) {
        echo "<h4>Item with id: " . $id . " deleted";
    } else {
        echo "<h4>Error</h4>";
        echo "<pre>" . print_r($sth->errorInfo(), 1) . "</pre>";
    }
}
?>
    <h4>Add itemtype</h4>
    <!--Formulär för kategorier -->
    <form action="dblabb3.php" method="post">
        <input type="text" name="name" placeholder="name">
        <input type="submit" name="addType" value="Submit">
    </form>

    <h4>Add item</h4>
    <!--Formulär för föremål -->
    <form action="dblabb3.php" method="post">
        <select name="type">
        <?php
            /* Här hämtas all data från itemtypes tabellen för att enkelt skapa
             * en lista över existerande itemtype kategorier till formuläret.
             * Detta gör även att vi slipper uppdatera vår html när vi lägger
             * till nya kategorier.
             */
            $query = "SELECT * FROM itemtypes ORDER BY name ASC";

            if ($result = $db->query($query)) {
                while ($row = $result->fetch(PDO::FETCH_NUM)) {
                    echo '<option value="' . $row['0'] . '">' . $row['1'] . '</option>';
                }
            }
        ?>
        </select>
        <input type="text" name="desc" placeholder="description">
        <input type="submit" name="addItem" value="Submit">
    </form>

    <h4>List categories</h4>
    <?php
    // skriv ut en lista över existerande kategorier med delete länk
    $query = "SELECT * FROM itemtypes ORDER BY name ASC";

    if ($result = $db->query($query)) {
        echo "<ul>";
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo '<li>id: ' . $row['id'] . ' type: ' . $row['name'] .
                 ' <a href="dblabb3.php?delType=' . $row['id'] .
                 '">Delete type</a></li>';
        }
        echo "</ul>";
    }
    ?>
    <h4>List items</h4>
    <?php
    // skriv ut en lista över existerande föremål med delete länk

    // Vi använder en JOIN för att visa föremålets typ i listan
    $query = "SELECT items.id, description, name FROM items
              LEFT JOIN itemtypes ON itemtypes.id = items.type";

    if ($result = $db->query($query)) {
        echo "<ul>";
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            // notera att länken nu går till delItem
            echo '<li>id: ' . $row['id'] . ' type: ' . $row['name'] .
                 ' desc: ' . $row['description'] .
                 ' <a href="dblabb3.php?delItem=' . $row['id'] .
                 '">Delete item</a></li>';
        }
        echo "</ul>";
    }
?>
</body>
</html>