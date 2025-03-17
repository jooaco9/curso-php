<?php
require "db.php";

// Inicio de la session, entonces si existe la session la toma
session_start();

if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  return;
}

$contactId = $_GET["id"];

// Verificacion de si existe un usuario con ese id
$stmt = $conn->prepare("SELECT * FROM contacts WHERE id = :id LIMIT 1;");
$stmt->bindParam(":id", $contactId);
$stmt->execute();

// Si no existe, 404
if ($stmt->rowCount() == 0) {
  http_response_code(404);
  echo("HTTP 404 NOT FOUND");
  return;
}

// Guardar los datos del contacto en la variable $contact
$contact = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificacion para que no se puede ditar un contacto de otro usuario
if ($contact["user_id"] !== $_SESSION["user"]["id"]) {
  http_response_code(403);
  echo("GTTP 403 UNAUTHORIZED");
  return;
}

$error = null;

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $totalAdresses = $_POST["adresses"];
  $empty = false;

  foreach ($totalAdresses as $id => $adress) {
    // Me fijo si alguna de las direcciones esta vacia
    if (empty($adress)) {
      $empty = true;
      $error = "Please fill all fields.";
      break;
    }

    // Actualizo cada direccion del contacto seleccionado
    $stmt = $conn->prepare("UPDATE adress SET adress = :new_adress WHERE contact_id = :contact_id AND id = :id");
    $stmt->bindParam(":new_adress", $adress);
    $stmt->bindParam(":contact_id", $contactId);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
  }

  if (!$empty) {
    // Obtener el nombre del contacto seleccionado
    $stmt = $conn->prepare("SELECT * FROM contacts WHERE id = :id");
    $stmt->bindParam(":id", $contactId);
    $stmt->execute();
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);

    $_SESSION["flash"] = ["message" => "Adress from {$contact["name"]} updated."];

    // Redirigir a home.php
    header("Location: home.php");
    return;
  }
}
?>

<?php require "partials/header.php" ?>
<div class="container pt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Edit Adress Contact</div>
        <div class="card-body">
          <!-- Mostrar mensaje de error si existe -->
          <?php if ($error): ?>
            <p class="text-danger">
              <?php echo $error; ?>
            </p>
          <?php endif ?>

          <!-- Formulario para editar direcciones -->
          <form method="POST" action="editAdresses.php?id=<?php echo $contactId; ?>">
            <?php 
              // Obtener todas las direcciones del contacto
              $stmt = $conn->query("SELECT * FROM adress WHERE contact_id = {$contact["id"]}");
              if ($stmt->rowCount() == 0):
            ?>
              <div class="alert alert-danger text-center" role="alert">
                No addresses available
              </div>
            <?php else: ?>
              <?php 
                $adresses = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($adresses as $adress):
              ?>
                <div class="mb-3 row">
                  <label for="adress<?php echo $adress["id"]; ?>" class="col-md-4 col-form-label text-md-end">Adress</label>
                  <div class="col-md-6">
                    <!-- Con lo que esta puesto en name, hace que al enviarselo a PHP cree un array asociativo con el id de la adress como clave y el valor de adress asociado a esa clave -->
                    <input value="<?php echo $adress["adress"]; ?>" id="adress<?php echo $adress["id"]; ?>" type="text" class="form-control" name="adresses[<?php echo $adress["id"]; ?>]" autocomplete="off" autofocus>
                  </div>
                </div>
              <?php endforeach; ?>
              <div class="mb-3 row">
                <div class="col-md-6 offset-md-4">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </div>
            <?php endif ?>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require "partials/footer.php" ?>