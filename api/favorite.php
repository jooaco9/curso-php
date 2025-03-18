<?php 
require "../config/db.php";

// Inicio de session
session_start();

// Verificacion de usuario autenticado
if (!isset($_SESSION["user"])) {
  echo json_encode(["success" => false, "message" => "Unauthorized"]);
  exit();
}

$userId = $_SESSION["user"]["id"];

// Verifico que se mandan los parametros
if (!isset($_POST["contact_id"]) || !isset($_POST["favorite"])) {
  echo json_encode(["success" => false, "message" => "Missing parameters"]);
  exit();
}

$contactId = $_POST["contact_id"];
$favorite = $_POST["favorite"] ? 1 : 0;

// Verificacion de si existe un usuario con ese id
$stmt = $conn->prepare("SELECT * FROM contacts WHERE id=:id LIMIT 1;");
$stmt->bindParam(":id", $contactId);
$stmt->execute();

if ($stmt->rowCount() == 0) {
  echo json_encode(["success" => false, "message" => "Contact not found or unauthorized"]);
  exit();
}

$contact = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificacion de que el usuario logueado sea el que puede borrar al contacto
if ($contact["user_id"] !== $_SESSION["user"]["id"]) {
  echo json_encode(["success" => false, "message" => "Contact not found or unauthorized"]);
  exit();
}

$stmt = $conn->prepare("UPDATE contacts SET favorite = :favorite WHERE id = :contact_id AND user_id = :user_id");
$stmt->bindParam(":favorite", $favorite);
$stmt->bindParam(":contact_id", $contactId);
$stmt->bindParam(":user_id", $userId);
$stmt->execute();

echo json_encode(["success" => true]);
?>