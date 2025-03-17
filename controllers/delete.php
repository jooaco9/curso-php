<?php
require "../config/db.php";

// Inicio de la session, entonces si existe la session la toma
session_start();

if (!isset($_SESSION["user"])) {
  header("Location: ../auth/login.php");
  return;
}

$contactId = $_GET["id"];

// Verificacion de si existe un usuario con ese id
$stmt = $conn->prepare("SELECT * FROM contacts WHERE id=:id LIMIT 1;");
$stmt->bindParam(":id", $contactId);
$stmt->execute();

if ($stmt->rowCount() == 0) {
  http_response_code(404);
  echo("HTTP 404 NOT FOUND");
  return;
}

$contact = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificacion de que el usuario logueado sea el que puede borrar al contacto
if ($contact["user_id"] !== $_SESSION["user"]["id"]) {
  http_response_code(403);
  echo("GTTP 403 UNAUTHORIZED");
  return;
}

// Borramos primero las direcciones asociadas al contacto a borrar
$stmt = $conn->prepare("DELETE FROM adress WHERE contact_id=:contact_id");
$stmt->bindParam(":contact_id", $contactId);
$stmt->execute();

// Preparamos la sentencia sql
$stmt = $conn->prepare("DELETE FROM contacts WHERE id=:id;");

// Control de inyecciones sql
$stmt->bindParam(":id", $contactId);

// Ejecucion de la sentencia sql
$stmt->execute();

$_SESSION["flash"] = ["message" => "Conctac {$contact["name"]} deleted."];

header("Location: ../views/home.php");
return;