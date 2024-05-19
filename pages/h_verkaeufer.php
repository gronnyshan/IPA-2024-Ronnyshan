<?php

require_once('../php/db_verbindung.php');

// Überprüfen, ob der Benutzer eingeloggt ist
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    
    // Weiterleitung zur Loginseite
    header("location: ../index.php");
    exit;
}

$benutzer_id = $_SESSION["b_id"];

$sql = "SELECT vertrag_id, vertragsbeginndatum, bemerkungen FROM vertraege WHERE benutzer_id = $benutzer_id";
$result = $connection->query($sql);

?>

<!DOCTYPE html>
<html lang="de">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verkäufer</title>
    <link rel="stylesheet" href="../css/header.stylesheet.cs">
    <!-- Kopiert von Bootstrap-Seite -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> 

</head>

<body>
   
    </div class="div-header">

        <header>

            <!-- Navigationsleiste - Bootstrap -->
            <nav class="navbar bg-body-tertiary">

                <div class="container-fluid">

                    <a class="navbar-brand" href="#">
                        <img src="../img/Marketingmaster-logo.png" alt="Logo" width="350" height="auto" class="d-inline-block align-text-top">
                    </a>

                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    
                        <li class="nav-item">
                            <a class="nav-link" href="formular.php?benutzer_id=<?php echo $benutzer_id; ?>">Vetrag erstellen</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="../php/logout.php">Logout</a>
                        </li>
                        
                    </ul>

                </div>

            </nav>

        </header>

    </div>

    <div class="container mt-5">

        <h1>Verkäuferseite</h1>
        <p>Benutzer-ID: <?php echo $benutzer_id; ?></p>


        <table class="table table-striped">

            <thead>
                <tr>
                    <th>Vertrags ID</th>
                    <th>Vertragsbeginndatum</th>
                    <th>Bemerkungen</th>
                    <th>Details</th>
                </tr>
            </thead>

            <tbody>
                <?php
        
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["vertrag_id"] . "</td>";
                        echo "<td>" . $row["vertragsbeginndatum"] . "</td>";
                        echo "<td>" . $row["bemerkungen"] . "</td>";
                        echo '<td><a href="vertrag_detailiert.php?vertrag_id=' . $row["vertrag_id"] . '">Details anzeigen</a></td>';
                        echo '<td><a href="../php/vertrag_loeschen.php?vertrag_id=' . $row["vertrag_id"] . '" onclick="return confirm(\'Möchten Sie diesen Vertrag wirklich löschen?\')">Löschen</a></td>';
                        echo "</tr>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Keine Verträge gefunden.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>


</html>
