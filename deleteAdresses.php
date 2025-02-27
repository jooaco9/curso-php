<?php
  require "db.php";

  // Inicio de la session, entonces si existe la session la toma
  session_start();

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

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idAdress = $_GET["id_adress"];

    // Borrar direccion
    $stmt = $conn->prepare("DELETE FROM adress WHERE id=:id AND contact_id = :contact_id;");
    $stmt->bindParam(":id", $idAdress);
    $stmt->bindParam(":contact_id", $contactId);

    $stmt->execute();

    $_SESSION["flash"] = ["message" => "Adress from {$contact["name"]} deleted."];

    header("Location: deleteAdresses.php?id=$contactId");
    return;
  }
?>

<?php require "partials/header.php" ?>
    <div class="container pt-5">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">Edit Adress Contact</div>
            <div class="card-body">
            <?php 
              $stmt = $conn->query("SELECT * FROM adress WHERE contact_id = {$contact["id"]}");
              if ($stmt->rowCount() == 0):
            ?>
              <div class="alert alert-danger text-center" role="alert">
                No addresses available
              </div>
            <?php else: ?>
              <?php
                $adresses = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $cont = 0;
                foreach ($adresses as $adress):
                  $cont++
              ?>
              <form method="POST" action="deleteAdresses.php?id=<?php echo $contactId; ?>&id_adress=<?php echo $adress["id"]?>">
                <div class="mb-3 row">
                  <label for="adress" class="col-md-4 col-form-label text-md-end">Adress</label>

                  <div class="col-md-4">
                    <input value="<?php echo $adress["adress"]; ?>" id="adress" type="text" class="form-control" name="adress" autocomplete="off" disabled>
                  </div>

                  <div class="col-md-4">
                    <button type="submit" class="btn btn-danger">Delete</button>
                  </div>
                </div>
              </form>
              <?php endforeach ?>
            <?php endif ?>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php require "partials/footer.php" ?>