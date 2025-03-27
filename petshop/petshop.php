<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "petshop";

// Verbinding maken
$conn = new mysqli($servername, $username, $password, $dbname);

//Controleer de verbinding
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

//Gegevensvalidatie
$errors = [];
$pet_name = $pet_type = $birth_date = $owner_name = "";

//Valideer het formulier bij het indienen van gegevens
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Controleer naam van het dier
    if (empty($_POST['pet_name']) || strlen($_POST['pet_name']) < 3) {
        $errors['pet_name'] = "De naam van het dier moet uit meer dan 3 letters bestaan";
    } else {
        $pet_name = $_POST['pet_name'];
    }

    // Controleer het type van het dier
    if (empty($_POST['pet_type']) || strlen($_POST['pet_type']) < 3) {
        $errors['pet_type'] = "Het diertype moet uit meer dan 3 letters bestaan";
    } else {
        $pet_type = $_POST['pet_type'];
    }

    // Controleer geboortedatum
    if (empty($_POST['birth_date']) || !strtotime($_POST['birth_date'])) {
        $errors['birth_date'] = "Ongeldige geboortedatum";
    } else {
        $birth_date = $_POST['birth_date'];

        // Controleer of de geboortedatum niet in de toekomst ligt
        if (strtotime($birth_date) > time()) {
            $errors['birth_date'] = "Geboortedatum mag niet in de toekomst liggen";
        }
    }

    // Controleer de naam van de eigenaar
    if (empty($_POST['owner_name']) || strlen($_POST['owner_name']) < 3) {
        $errors['owner_name'] = "De naam van de eigenaar moet meer dan 3 tekens lang zijn";
    } else {
        $owner_name = $_POST['owner_name'];
    }

    //Controleer of het dier niet gedupliceerd is
    if (empty($errors)) {
        $check_query = $conn->prepare("SELECT * FROM pets WHERE pet_name = ? AND pet_type = ?");
        $check_query->bind_param("ss", $pet_name, $pet_type);
        $check_query->execute();
        $result = $check_query->get_result();

        if ($result->num_rows > 0) {
            $errors['duplicate'] = "Dit huisdier is al toegevoegd";
        } else {

            // Als er geen fouten zijn, voeren we de gegevens in de database in.
            $stmt = $conn->prepare("INSERT INTO pets (pet_name, pet_type, birth_date, owner_name) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $pet_name, $pet_type, $birth_date, $owner_name);

            // Voer de query uit
            if ($stmt->execute()) {
                echo "<p style='color: green;'> Huisdier succesvol toegevoegd! </p>";
            } else {
                echo "<p style='color: red;'> Er is een fout opgetreden bij het toevoegen van gegevens: " . $stmt->error . "</p>";
            }

            $stmt->close();
        }
    }
}

//Dier verwijderen
if (isset($_GET['delete'])) {
    $pet_id = $_GET['delete'];
    $delete_stmt = $conn->prepare("DELETE FROM pets WHERE id = ?");
    $delete_stmt->bind_param("i", $pet_id);
    if ($delete_stmt->execute()) {
        echo "<p style='color: green;'> Het huisdier is succesvol verwijderd! </p>";
    } else {
        echo "<p style='color: red;'> Er is een fout opgetreden bij het verwijderen: " . $delete_stmt->error . "</p>";
    }
    $delete_stmt->close();
}

// Controleer of een dier is bijgewerkt
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
}

// Sluit de verbinding nadat de bewerkingen zijn voltooid
$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Voeg een huisdier toe </title>
    <style>
        .valid { background-color: #d4edda; }
        .invalid { background-color: #f8d7da; }
    </style>
</head>
<body>
    <h1> Voeg een huisdier toe </h1>
    <form id="petForm" method="POST" action="petshop.php">
        <label for="pet_name"> De naam van het huisdier:</label>
        <input type="text" id="pet_name" name="pet_name" value="<?php echo isset($_POST['pet_name']) ? $_POST['pet_name'] : $pet_name; ?>" class="<?php echo isset($errors['pet_name']) ? 'invalid' : ''; ?>"><br><br>

        <label for="pet_type"> De type van het huisdier: </label>
        <input type="text" id="pet_type" name="pet_type" value="<?php echo isset($_POST['pet_type']) ? $_POST['pet_type'] : $pet_type; ?>" class="<?php echo isset($errors['pet_type']) ? 'invalid' : ''; ?>"><br><br>

        <label for="birth_date"> geboortedatum: </label>
        <input type="date" id="birth_date" name="birth_date" value="<?php echo isset($_POST['birth_date']) ? $_POST['birth_date'] : $birth_date; ?>" class="<?php echo isset($errors['birth_date']) ? 'invalid' : ''; ?>"><br><br>

        <label for="owner_name"> De naam van de eigenaar: </label>
        <input type="text" id="owner_name" name="owner_name" value="<?php echo isset($_POST['owner_name']) ? $_POST['owner_name'] : $owner_name; ?>" class="<?php echo isset($errors['owner_name']) ? 'invalid' : ''; ?>"><br><br>

        <button type="submit"> Verzinden </button>
    </form>

    <?php
    // Weergavefouten na gegevensvalidatie
    if (!empty($errors)) {
        if (isset($errors['duplicate'])) {
            echo "<h3 style='color: red;'>".$errors['duplicate']."</h3>";
        } else {
            echo "<h3 style='color: red;'> Er staan​​ fouten in de gegevens: </h3>";
            foreach ($errors as $error) {
                echo "<p style='color: red;'>$error</p>";
            }
        }
    }
    ?>

    <h2>Huisdierenlijst</h2>
    <table border="1">
        <tr>
            <th>Naam van het huisdier</th>
            <th>Type van het huisdier</th>
            <th>Geboortedatum</th>
            <th>Naam van de eigenaar</th>
            <th>Wijziging</th>
            <th>Verwijderen</th>
        </tr>
        <?php
        // Query om alle huisdieren terug te halen
        $conn = new mysqli($servername, $username, $password, $dbname);
        $result = $conn->query("SELECT * FROM pets");

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['pet_name'] . "</td>";
            echo "<td>" . $row['pet_type'] . "</td>";
            echo "<td>" . $row['birth_date'] . "</td>";
            echo "<td>" . $row['owner_name'] . "</td>";
            echo "<td><a href='petshop.php?edit=" . $row['id'] . "'>Wijzigen</a></td>";
echo "<td><a href='petshop.php?delete=" . $row['id'] . "' onclick='return confirm(\"Weet u zeker dat u dit dier wilt verwijderen?\")'>Verwijderen</a></td>";
            echo "</tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>