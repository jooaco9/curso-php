<?php
require "db.php";

$contactId = $_GET["id"];

// Verificacion de si existe un usuario con ese id
$stmt = $conn->prepare("SELECT * FROM contacts WHERE id=:id;");
$stmt->bindParam(":id", $contactId);
$stmt->execute();

if ($stmt->rowCount() == 0) {
  http_response_code(404);
  echo("HTTP 404 NOT FOUND");
  return;
}

// Peraramos la sentencia sql
$stmt = $conn->prepare("DELETE FROM contacts WHERE id=:id;");

// Control de inyecciones sql
$stmt->bindParam(":id", $contactId);

// Ejecucion de la sentencia sql
$stmt->execute();

header("Location: index.php");