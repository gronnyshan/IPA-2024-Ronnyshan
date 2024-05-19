<?php

require_once('../php/db_verbindung.php');

// Überprüfen, ob der Benutzer eingeloggt ist
session_start();
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    // Weiterleitung zur Loginseite
    header("location: ../index.php");
    exit;
}

if (isset($_GET["vertrag_id"])) {
    $vertrag_id = $_GET["vertrag_id"];

    // Beginne eine Transaktion
    $connection->begin_transaction();

    try {
        // Zuerst die zugehörigen Daten in der 'vd'-Tabelle löschen
        $sql_vd = "DELETE FROM vd WHERE vertrag_id = ?";
        if ($stmt_vd = $connection->prepare($sql_vd)) {
            $stmt_vd->bind_param("i", $vertrag_id);
            if (!$stmt_vd->execute()) {
                throw new Exception("Fehler beim Löschen der Daten in der 'vd'-Tabelle.");
            }
            $stmt_vd->close();
        } else {
            throw new Exception("Fehler beim Vorbereiten der Abfrage für die 'vd'-Tabelle.");
        }

        // Dann den Vertrag in der 'vertraege'-Tabelle löschen
        $sql_vertraege = "DELETE FROM vertraege WHERE vertrag_id = ?";
        if ($stmt_vertraege = $connection->prepare($sql_vertraege)) {
            $stmt_vertraege->bind_param("i", $vertrag_id);
            if (!$stmt_vertraege->execute()) {
                throw new Exception("Fehler beim Löschen des Vertrags in der 'vertraege'-Tabelle.");
            }
            $stmt_vertraege->close();
        } else {
            throw new Exception("Fehler beim Vorbereiten der Abfrage für die 'vertraege'-Tabelle.");
        }

        // Commit der Transaktion
        $connection->commit();
        
        // Erfolgreich gelöscht
        if (isset($_SERVER['HTTP_REFERER'])) {
            header("location: " . $_SERVER['HTTP_REFERER']);
        } else {
            header("location: ../verkaufer.php");
        }
        
        exit;
    } catch (Exception $e) {
        // Bei einem Fehler die Transaktion zurückrollen
        $connection->rollback();
        echo "Fehler: " . $e->getMessage();
    }
} else {
    echo "Keine Vertrags-ID angegeben.";
}

$connection->close();
?>
