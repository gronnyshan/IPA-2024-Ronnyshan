<!-- PHP -->
<?php 
 
    require_once('../php/db_verbindung.php'); 

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Informationen vom Formular holen
        $firma = $_POST['firma'];
        $vorname = $_POST['vorname'];
        $nachname = $_POST['nachname'];
        $tel = $_POST['tel'];
        $email = $_POST['email'];
        $adresse = $_POST['adresse'];
        $plz = $_POST['plz'];
        $ort = $_POST['ort'];
        $webseite = $_POST['webseite'];
        $dienstleistungen = $_POST['dienstleistungen'];
        
    
        // Kunde einfügen in Tabelle
        $stmt = $connection->prepare("INSERT INTO kunden (k_vorname, k_nachname, k_firmenname, k_strasse, k_plz, k_ort, k_email, k_telefon, k_webseite) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssissss", $vorname, $nachname, $firma, $adresse, $plz, $ort, $email, $tel, $webseite);

        if ($stmt->execute()) {
            $kunden_id = $stmt->insert_id;

            // Vertrag in die Tabelle "vertraege" einfügen
            $benutzer_id = "1";
            $bemerkungen = $vorname . " " . $nachname . " - Vertrag";
            $stmt = $connection->prepare("INSERT INTO vertraege (kunden_id, benutzer_id, bemerkungen) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $kunden_id, $benutzer_id, $bemerkungen);

            if ($stmt->execute()) {
                $vertrag_id = $stmt->insert_id;

                // Ausgewählte Dienstleistungen in "vd" speichern
                $stmt = $connection->prepare("INSERT INTO vd (vertrag_id, dienstleistung_id) VALUES (?, ?)");
                foreach ($dienstleistungen as $dienstleistung_id) {
                    $stmt->bind_param("ii", $vertrag_id, $dienstleistung_id);
                    $stmt->execute();
                }
                echo "Vertrag erfolgreich erstellt!";
            } else {
                echo "Fehler beim Einfügen des Vertrags: " . $stmt->error;
            }
        } else {
            echo "Fehler beim Einfügen des Kunden: " . $stmt->error;
        }

        // Verbindung schließen
        $stmt->close();

    }

?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="de">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/header.stylesheet.css">
    <title>Vertrag erstellen</title>
    <!-- Kopiert von Bootstrap-Seite -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> 

</head>

<body>

    <div class="div-header">

        <header>

            <!-- Navigationsleiste - Bootstrap -->
            <nav class="navbar bg-body-tertiary">

                <div class="container-fluid">
                    
                    <a class="navbar-brand" href="#">
                    <img src="../img/Marketingmaster-logo.png" alt="Logo" width="350" height="auto" class="d-inline-block align-text-top">
                    </a>

                </div>

            </nav>

        </header>

    </div>

    <div class="container mt-4">

        <form action="#" method="POST">
        
        <h1 class="h3 mb-3 fw-normal">Kundeninformationen:</h1>

            <div class="form-group">

                <label for="firma">Firmaname:</label>
                <input type="text" class="form-control" id="firma" name="firma" placeholder="Firmaname">

            </div>

            <div class="row">
                
                <div class="col">
                    <label for="vorname">Vorname:*</label>
                    <input type="text" id="vorname" name="vorname" class="form-control" placeholder="Vorname" required>
                </div>

                <div class="col">
                    <label for="nachname">Nachname:*</label>
                    <input type="text" class="form-control" id="nachname" name="nachname" placeholder="Nachname" required>
                </div>

            </div>

            <div class="row">
                
                <div class="col">
                    <label for="tel">Telefonnummer:*</label>
                    <input type="tel" class="form-control" id="tel" name="tel" placeholder="Telefonnummer" required>
                </div>

                <div class="col">
                    <label for="email">E-Mail:*</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="E-Mail" required>
                </div> 

            </div>

            <div class="form-group">

                <label for="adresse">Adresse:*</label>
                <input type="text" class="form-control" id="adresse" name="adresse" placeholder="Adresse" required>

            </div>

            <div class="row">
                
                <div class="col">
                    <label for="plz">PLZ:*</label>
                    <input type="number" id="plz" name="plz" class="form-control" placeholder="PLZ" required>
                </div>

                <div class="col">
                    <label for="ort">Ort:*</label>
                    <input type="text" class="form-control" id="ort" name="ort" placeholder="ort" required>
                </div>

            </div>

            <div class="form-group">

                <label for="webseite">Webseite:</label>
                <input type="text" class="form-control" id="webseite" name="webseite" placeholder="Webseite">

            </div>

        <h1 class="h3 mb-3 fw-normal" style="margin-top:10px;">Dienstleistungen:</h1>

        <?php 
            include '../php/dienstleistungen_anzeigen.php';
            dienstleistungen_anzeigen();  
        ?>


        <!-- Das wird dann im Skript gebraucht -->
        
        <button type="submit" class="btn btn-primary" style="margin-top:15px;background-color:  #e041dc;">Vertrag erstellen</button>

        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>    

</body>

</html>