<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>dblabb4</title>
</head>
<body>
<?php
/* dblabb4.php */

include('view/header.php');
include 'dblabb2.php';
$db = new DB();

// Låna ett föremål och lägg till rad för detta i loan
if (isset($_POST['addLoan'])) {
    // filtrera input, float för datum fältet tillåter - och .
    $return = filter_input(INPUT_POST, 'returndate', FILTER_SANITIZE_NUMBER_FLOAT);
    $item = filter_input(INPUT_POST, 'item', FILTER_SANITIZE_NUMBER_INT);
    $user = filter_input(INPUT_POST, 'users', FILTER_SANITIZE_NUMBER_INT);
    
    /* Eftersom det är möjligt att föremål ska lånas innan de finns i 
     * låntabellen så behöver vi kontrollera om active är 0 eller inte satt.
     */
    $query = "SELECT active FROM loan WHERE item = :id AND active = 0 OR active = ''";

    $sth = $db->prepare($query);

    if ($sth->execute(array(':id' => $item))) {
        // lägg till lånet, vi anger inte active då det automatisk sätts till 1
        $query = "INSERT INTO loan(item, users, returndate)
                      VALUES (:item, :users, :returndate)";

        $values = array(':item' => $item,
                        ':users' => $user,
                        ':returndate' => $return);

        $sth = $db->prepare($query);

        if ($sth->execute($values)) {
            echo "Loan for item with id " . $item . " registered.";
        } else {
            // om något går fel skriv ut PDO felmeddelande
            echo "<h4>Error</h4>";
            echo "<pre>" . print_r($sth->errorInfo(), 1) . "</pre>";
        }
    } else {
        echo "A loan for item with id: " . $item . " is already active.";
    }
}

/* Vid återlämning av föremål
 * Sätter loan.active till 0 för att visa att det är ett inaktivt lån.
 * Detta för att arkivera alla lån.
 */
if (isset($_GET['returnItem'])) {
    $id = filter_input(INPUT_GET, 'returnItem', FILTER_SANITIZE_NUMBER_INT);
    
    // query för att uppdatera active fältet
    $query = "UPDATE loan SET active='0' WHERE id=(:id)";

    $sth = $db->prepare($query);

    if ($sth->execute(array(':id' => $id))) {
        echo "<h4>Item type with id: " . $id . " returned.";
    } else {
        echo "<h4>Error</h4>";
        echo "<pre>" . print_r($sth->errorInfo(), 1) . "</pre>";
    }
}
?>
    <h4>Register loan</h4>
    <form action="dblabb4.php" method="post">
        <label>Item: </label><select name="item">
        <?php
        /* Vi använder en SQL JOIN på items, itemtypes och loan för att 
         * hämta data för select fältet.
         * Vi vill inte visa föremål som redan är utlånade så vi 
         * hämtar active fältet från loan.
         *
         * Detta illustrerar ett problem med att vi sparar all data, även
         * inaktiva lån, då det kan bli en väldigt stor hämtning utan att
         * datan används. Därför kan det vara en god ide att spara arkiverade
         * lån i en separat tabell.
         */ 
        $query = "SELECT items.id, items.description AS name, loan.active
                  FROM items
                  LEFT JOIN loan ON loan.item = items.id
                  LEFT JOIN itemtypes ON itemtypes.id = items.type";

            if ($result = $db->query($query)) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    // kontrollera att active inte är 1
                    if ($row['active'] != 1) {
                        echo '<option value="' . $row['id'] . '">' .
                              $row['name'] . '</option>';
                    }
                }
            }
        ?>
        </select>
        <label>users: </label><select name="users">
        <?php
            // Query för att hämta användare och skriva ut dem.
            $query = "SELECT id, users_uid FROM users ORDER BY users_uid ASC";

            if ($result = $db->query($query)) {
                while ($row = $result->fetch(PDO::FETCH_NUM)) {
                    echo '<option value="' . $row['0'] . '">' . $row['1'] . '</option>';
                }
            }
        ?>
        </select>
        <label>Return before: </label><input type="date" name="returndate" placeholder="yyyy-mm-dd">
        <input type="submit" name="addLoan" value="Submit">
    </form>

    <?php
    /* Lista över de lån som finns i databasen, både aktiva och inaktiva
     * Skrivs ut med möjlighet att lämna tillbaka föremålet.
     */
    $query = "SELECT loan.id AS lid, active, item, loandate, returndate,
                     description, name AS itemtype, loan.users AS loaner
              FROM loan
              LEFT JOIN items ON items.id = loan.item
              LEFT JOIN itemtypes ON itemtypes.id = items.type
              LEFT JOIN users AS loaner ON loaner.id = loan.users
              ORDER BY loandate DESC";

    if ($result = $db->query($query)) {

        echo "<h2>Registered loans</h2>";

        // En tabell för överskådlig utskrift av exempeldata
        echo '<table border="1"><tr><th>item id</th><th>item type</th><th>description</th>' .
             '<th>users</th><th>loan date</th><th>return date</th><th>return item</th></tr>';
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['item'] . "</td>";
            echo "<td>" . $row['itemtype'] . "</td>";
            echo "<td>" . $row['description'] . "</td>";
            echo "<td>" . $row['loaner'] . "</td>";
            echo "<td>" . $row['loandate'] . "</td>";
            echo "<td>" . $row['returndate'] . "</td>";
            // Fält för att visa länk för återlämning av föremål
            echo "<td>";
            if($row['active'] == '1') {
                echo '<a href="dblabb4.php?returnItem=' . $row['lid'] . '">Return item</a>';    
            } else {
                echo "No active loan";
            }
            echo "</td></tr>";
        }
        echo "</table>";
    }
?>
</body>
</html>