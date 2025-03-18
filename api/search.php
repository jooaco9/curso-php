<?php 
require "../config/db.php";

// Inicio de session
session_start();

// Verificacion de usuario autenticado
if (!isset($_SESSION["user"])) {
  // Devolver error en formato JSON
  header('Content-Type: application/json');
  echo json_encode(["error" => "Unauthorized"]);
  exit;
}

$userId = $_SESSION["user"]["id"];

// Tomo los parametros del GET
$searchTerm = isset($_GET["term"]) ? $_GET["term"]: "";
$favorite = isset($_GET["favorite"]) ? true : false;

// Preparar consulta con protección contra SQL Injection
$query = "SELECT * FROM contacts WHERE user_id = :userId";
$params = [":userId" => $userId];

if($favorite) {
  $query .= " AND favorite = 1";
}

// Añadir filtro de búsqueda si hay término
if (!empty($searchTerm)) {
  $query .= " AND (name LIKE :searchTerm OR phone_number LIKE :searchTerm)";
  $params[":searchTerm"] = "%" . $searchTerm . "%";
}

// Preparar y ejecutar la consulta
$stmt = $conn->prepare($query);
$stmt->execute($params);
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener direcciones para cada contacto
foreach ($contacts as &$contact) {
  $stmt = $conn->prepare("SELECT adress FROM adress WHERE contact_id = :contactId");
  $stmt->execute([":contactId" => $contact["id"]]);
  $contact["addresses"] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Devolver resultados en formato JSON
header('Content-Type: application/json');
echo json_encode($contacts);
exit;
?>