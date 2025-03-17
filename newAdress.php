<?php
  require "db.php";

  // Inicio de la session, entonces si existe la session la toma
  session_start();

  // Redirigir al login si el usuario no está autenticado
  if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    return;
  }
  
  $userId = $_SESSION["user"]["id"];

  // Obtener todos los contactos del usuario autenticado
  $stmt = $conn->query("SELECT * FROM contacts WHERE user_id = $userId");

  // Array asociativo para recorrer los campos
  $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $error = null;

  // Verificar si el formulario fue enviado
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $adress = $_POST["adress"];
    $contactId = $_POST["contact_id"];

    // Verificacion para ver si alguno de los campos esta vacio
    if (empty($adress) || empty($contactId)) {
      $error = "Please fill all fields.";
    } else {

      // Obtener el nombre del contacto seleccionado
      $stmt = $conn->prepare("SELECT * FROM contacts WHERE id = :id");
      $stmt->bindParam(":id", $contactId);
      $stmt->execute();
      $contact = $stmt->fetch(PDO::FETCH_ASSOC);

      // Verificación para que no se pueda agregar una dirección a un contacto de otro usuario
      if ($contact["user_id"] !== $_SESSION["user"]["id"]) {
        http_response_code(403);
        echo("GTTP 403 UNAUTHORIZED");
        return;
      }

      // Peraramos la sentencia sql
      $stmt = $conn->prepare("INSERT INTO adress (adress, contact_id) VALUES (:adress, :contact_id)");

      // Control de inyecciones sql
      $stmt->bindParam(":adress", $adress);
      $stmt->bindParam(":contact_id", $contactId);

      // Ejecucion de la sentencia sql
      $stmt->execute();

      $_SESSION["flash"] = ["message" => "Adress for {$contact["name"]} added."];

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
            <div class="card-header">Add New Adress for a Conctact</div>
            <div class="card-body">
              <!-- Mostrar mensaje de error si existe -->
              <?php if ($error): ?>
                <p class="text-danger">
                  <?php echo $error; ?>
                </p>
              <?php endif ?>

              <!-- Formulario para agregar una nueva dirección -->
              <form method="POST" action="newAdress.php">
                  <div class="mb-3 row">
                    <label for="adress" class="col-md-4 col-form-label text-md-end">Adress</label>

                    <div class="col-md-6">
                      <input id="adress" type="text" class="form-control" name="adress" autocomplete="adress" autofocus>
                    </div>
                </div>

                <div class="mb-3 row">
                  <label for="contact_id" class="col-md-4 col-form-label text-md-end">Select User</label>
                  <div class="col-md-6">
                    <select id="contact_id" class="form-control" name="contact_id">
                      <option value="">Select a Conctat</option>
                      <?php foreach ($contacts as $contact): ?>
                        <option value="<?php echo $contact["id"] ?>"><?php echo $contact["name"]; ?></option>
                      <?php endforeach; ?>
                    </select>
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