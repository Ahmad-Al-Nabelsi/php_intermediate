<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "petshop";

// Databaseverbinding
$conn = new mysqli($servername, $username, $password, $dbname);

// Controleer verbinding
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

// Variabelen initialiseren
$errors = [];
$pet_name = $pet_type = $birth_date = $owner_name = "";

// Bij het indienen van het formulier
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pet_name = ucfirst(strtolower($_POST['pet_name']));
    $pet_type = ucfirst(strtolower($_POST['pet_type']));
    $birth_date = $_POST['birth_date'];
    $owner_name = ucfirst(strtolower($_POST['owner_name']));

   // controleer van de invoer.
    if (empty($pet_name) || strlen($pet_name) < 3) {
        $errors['pet_name'] = "Het dier moet uit meer dan 3 letters bestaan";
    }
    if (empty($pet_type) || strlen($pet_type) < 3) {
        $errors['pet_type'] = "Het diertype moet uit meer dan 3 letters bestaan";
    }
    if (empty($birth_date) || !strtotime($birth_date) || strtotime($birth_date) > time()) {
        $errors['birth_date'] = "Ongeldige geboortedatum of geboortedatum mag niet in de toekomst liggen";
    }
    if (empty($owner_name) || strlen($owner_name) < 3) {
        $errors['owner_name'] = "De naam van de eigenaar moet meer dan 3 tekens lang zijn";
    }

    // Controleer duplicatie in de database
    if (empty($errors)) {
        $pet_name_lower = strtolower($pet_name);
        $pet_type_lower = strtolower($pet_type);
        $owner_name_lower = strtolower($owner_name);

        /*Controleert of er een 'edit'-parameter in de URL staat, wat betekent dat de gebruiker een specifiek 
        dier wil bewerken en geen nieuw dier wil invoegen.*/
        if (isset($_GET['edit'])) {

            /*Als de 'edit'-parameter aanwezig is, haal dan het ID van het huisdier op dat moet worden bewerkt
            en voeg het toe aan de query om duplicaten te controleren.*/
            $pet_id = $_GET['edit'];
            /*Met deze query wordt gecontroleerd of er al een ander huisdier met dezelfde gegevens in de database 
            bestaat, met uitzondering van het huidige huisdier (id != ?).*/
            $check_query = $conn->prepare("SELECT * FROM pets WHERE LOWER(pet_name) = ? AND LOWER(pet_type) = ? AND birth_date = ? AND LOWER(owner_name) = ? AND id != ?");
            /*Waarden op een veilige manier doorgeven aan de query*/
            $check_query->bind_param("ssssi", $pet_name_lower, $pet_type_lower, $birth_date, $owner_name_lower, $pet_id);
        } else {
            //Als de 'edit'-parameter niet aanwezig is, betekent dit dat de gebruiker een nieuw huisdier wil toevoegen.
            $check_query = $conn->prepare("SELECT * FROM pets WHERE LOWER(pet_name) = ? AND LOWER(pet_type) = ? AND birth_date = ? AND LOWER(owner_name) = ?");
            $check_query->bind_param("ssss", $pet_name_lower, $pet_type_lower, $birth_date, $owner_name_lower);
        }

        $check_query->execute();
        $result = $check_query->get_result();
        /*Controleer of er al een huisdier met dezelfde gegevens bestaat
        Als er een duplicaat wordt gevonden, voeg dan een foutmelding toe aan de $errors-array.*/
        if ($result->num_rows > 0) {
            $errors['duplicate'] = "Dit huisdier is al toegevoegd";
        } else {
            // Gegevens invoeren of wijzigen
            if (isset($_GET['edit'])) {
                $stmt = $conn->prepare("UPDATE pets SET pet_name=?, pet_type=?, birth_date=?, owner_name=? WHERE id=?");
                $stmt->bind_param("ssssi", $pet_name, $pet_type, $birth_date, $owner_name, $pet_id);
            } else {
                $stmt = $conn->prepare("INSERT INTO pets (pet_name, pet_type, birth_date, owner_name) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $pet_name, $pet_type, $birth_date, $owner_name);
            }

            if ($stmt->execute()) {
                echo "<p style='color: green;'> Huisdier succesvol toegevoegd! </p>";

                // Maak velden leeg na het verzenden
                $_POST = [];
                $pet_name = $pet_type = $birth_date = $owner_name = "";
            } else {
                echo "<p style='color: red;'> Er is een fout opgetreden: " . $stmt->error . "</p>";
            }
            $stmt->close();
        }
        $check_query->close();
    }
}

//Verwijder het dier
if (isset($_GET['delete'])) {
    $pet_id = $_GET['delete'];
    $delete_stmt = $conn->prepare("DELETE FROM pets WHERE id = ?");
    $delete_stmt->bind_param("i", $pet_id);
    if ($delete_stmt->execute()) {
        echo "<p style='color: green;'> Het huisdier is succesvol verwijderd! </p>";
    } else {
        echo "<p style='color: red;'> Er is een fout opgetreden: " . $delete_stmt->error . "</p>";
    }
    $delete_stmt->close();
}

// Wijzig het huisdier
if (isset($_GET['edit'])) {
    $pet_id = $_GET['edit'];
    $edit_query = $conn->prepare("SELECT * FROM pets WHERE id = ?");
    $edit_query->bind_param("i", $pet_id);
    $edit_query->execute();
    $result = $edit_query->get_result();
    $pet = $result->fetch_assoc();
    $pet_name = $pet['pet_name'];
    $pet_type = $pet['pet_type'];
    $birth_date = $pet['birth_date'];
    $owner_name = $pet['owner_name'];
    $edit_query->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Voeg een huisdier toe </title>
</head>
<body>
    <h1> Voeg een huisdier toe </h1>
    <form method="POST">
        <label> Het huisdier:</label>
        <input type="text" name="pet_name" value="<?php echo $pet_name; ?>"><br>

        <label> De type van het huisdier:</label>
        <input type="text" name="pet_type" value="<?php echo $pet_type; ?>"><br>

        <label> Geboortedatum:</label>
        <input type="date" name="birth_date" value="<?php echo $birth_date; ?>"><br>

        <label> De naam van de eigenaar:</label>
        <input type="text" name="owner_name" value="<?php echo $owner_name; ?>"><br>

        <button type="submit"> Verzenden </button>
    </form>

    <h2>Huisdierenlijst</h2>
    <table border="1">
        <tr>
            <th>Huisdier</th>
            <th>Type</th>
            <th>Geboortedatum</th>
            <th>Eigenaar</th>
            <th>Wijzigen</th>
            <th>Verwijderen</th>
        </tr>
        <?php
        $conn = new mysqli($servername, $username, $password, $dbname);
        $result = $conn->query("SELECT * FROM pets");

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['pet_name']}</td>
                <td>{$row['pet_type']}</td>
                <td>{$row['birth_date']}</td>
                <td>{$row['owner_name']}</td>
                <td><a href='?edit={$row['id']}'>Wijzigen</a></td>
                <td><a href='?delete={$row['id']}' onclick='return confirm(\"Weet u zeker?\")'>Verwijderen</a></td>
            </tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>
