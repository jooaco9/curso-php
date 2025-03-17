<?php
  require "db.php";

  // Inicio de la session, entonces si existe la session la toma
  session_start();

  // Redirigir al login si el usuario no está autenticado
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
    // Pequeñas validaciones
    if (empty($_POST["name"]) || empty($_POST["phone_number"])) {
      $error = "Please fill all fields.";
    } else if (strlen($_POST["phone_number"]) < 9) {
      $error = "Phone number must be at least 9 characters.";
    } else {
      $name = $_POST["name"];
      $phoneNumber = $_POST["phone_number"];

      // Preparar la sentencia sql para hacer el update
      $stmt = $conn->prepare("UPDATE contacts SET name = :name, phone_number = :phone_number WHERE id = :id;");

      // Control de inyecciones sql
      $stmt->bindParam(":name", $name);
      $stmt->bindParam(":phone_number", $phoneNumber);
      $stmt->bindParam(":id", $contactId);

      // Ejecucion de la sentencia sql
      $stmt->execute();

      $_SESSION["flash"] = ["message" => "Conctac $name updated."];

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
            <div class="card-header">Edit Contact</div>
            <div class="card-body">
              <!-- Mostrar mensaje de error si existe -->
              <?php if ($error): ?>
                <p class="text-danger">
                  <?php echo $error; ?>
                </p>
              <?php endif ?>

              <!-- Formulario para editar un contacto -->
              <form method="POST" action="edit.php?id=<?php echo $contact["id"]; ?>">
                <div class="mb-3 row">
                  <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>
    
                  <div class="col-md-6">
                    <input value="<?php echo $contact["name"]; ?>" id="name" type="text" class="form-control" name="name" autocomplete="name" autofocus>
                  </div>
                </div>
    
                <div class="mb-3 row">
                  <label for="phone_number" class="col-md-4 col-form-label text-md-end">Phone Number</label>
    
                  <div class="col-md-6">
                    <input value="<?php echo $contact["phone_number"]; ?>" id="phone_number" type="tel" class="form-control" name="phone_number" autocomplete="phone_number" autofocus>
                  </div>
                </div>
    
                <div class="mb-3 row">
                  <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php require "partials/footer.php" ?>