<?php
  require "db.php";

  // Inicio de la session, entonces si existe la session la toma
  session_start();

  $error = null;

  // Verificar si el formulario fue enviado
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $pass = $_POST["password"];

    // Pequeñas validaciones 
    if (empty($email) || empty($pass)) {
      $error = "Please fill all the fields.";
    } else if (!str_contains($_POST["email"], "@")) {
      $error = "Email format is incorrect.";
    } else {
      // Preparar la consulta SQL para buscar el usuario por email
      $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
      $stmt->bindParam(":email", $email);
      $stmt->execute();

      // Pongo invalid credentials y no especificamente lo que esta mal, ya que alguien probando emails o passwords puede saber que esta mal o si hay un mail registrado
      if ($stmt->rowCount() == 0) {
        $error = "Invalid credentials.";
      } else {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // Verificar la contraseña
        if (!password_verify($pass, $user["password"])) {
          $error = "Invalid credentials.";
        } else {
          // Creamos la sesion para mantener una estado del user
          session_start();

          // Quitamos la password de user ya que no la necesitamos despues de haber hecho el login
          unset($user["password"]);

          // Guardamos lo que queremos en la session
          $_SESSION["user"] = $user;

          header("Location: home.php");
        }
      }
    }
  }
?>

<?php require "partials/header.php" ?>
    <div class="container pt-5">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">Login</div>
            <div class="card-body">
              <!-- Mostrar mensaje de error si existe -->
              <?php if ($error): ?>
                <p class="text-danger">
                  <?php echo $error; ?>
                </p>
              <?php endif ?>

               <!-- Formulario de login -->
              <form method="POST" action="login.php">
    
                <div class="mb-3 row">
                  <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>
    
                  <div class="col-md-6">
                    <input id="email" type="email" class="form-control" name="email" autocomplete="email" autofocus>
                  </div>
                </div>

                <div class="mb-3 row">
                  <label for="password" class="col-md-4 col-form-label text-md-end">Password</label>
    
                  <div class="col-md-6">
                    <input id="password" type="password" class="form-control" name="password" autocomplete="password" autofocus>
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