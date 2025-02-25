<?php
  require "db.php";

  $error = null;

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $pass = $_POST["password"];

    if (empty($name) || empty($email) || empty($pass)) {
      $error = "Please fill all the fields.";
    } else if (!str_contains($_POST["email"], "@")) {
      $error = "Email format is incorrect.";
    } else {
      $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
      $stmt->bindParam(":email", $email);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        $error = "This email is taken.";
      } else {
        // Debemos hashear la password para que no quede en texto plano en la db
        $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)")->execute([":name" => $name, ":email" => $email, ":password" => password_hash($pass, PASSWORD_BCRYPT)]);

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Iniciamos la session
        session_start();
        unset($user["password"]);

        $_SESSION["user"] = $user;

        header("Location: home.php");
      }
    }
  }
?>

<?php require "partials/header.php" ?>
    <div class="container pt-5">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">Register</div>
            <div class="card-body">
              <?php if ($error): ?>
                <p class="text-danger">
                  <?php echo $error; ?>
                </p>
              <?php endif ?>
              <form method="POST" action="register.php">
                <div class="mb-3 row">
                  <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>

                  <!-- 
                    Explicacion de los distintos campos del input:
                      - id proporciona un identificador único para el elemento <input>. Este identificador puede ser utilizado por etiquetas <label> para asociar la etiqueta con el campo de entrada, y también puede ser utilizado en scripts JavaScript para manipular el elemento.
                      
                      - type especifica el tipo de entrada que se espera. Algunos ejemplos comunes son text, email, password, tel, etc. Este atributo determina cómo se comportará el campo de entrada y qué tipo de datos aceptará.

                      - name proporciona un nombre para el campo de entrada. Este nombre se utiliza cuando se envía el formulario para identificar el valor del campo en el servidor. Es esencial para que el servidor pueda procesar los datos del formulario.

                      - autocomplete sugiere al navegador si debe autocompletar el campo de entrada basado en valores previamente ingresados por el usuario. Los valores comunes son on (habilitar autocompletar) y off (deshabilitar autocompletar). También puede aceptar valores específicos como name, email, password, etc., para indicar el tipo de datos que se espera.

                      - autofocus indica que el campo de entrada debe recibir el foco automáticamente cuando se carga la página. Esto es útil para mejorar la experiencia del usuario, ya que permite que el usuario comience a escribir en el campo de entrada sin tener que hacer clic en él primero
                  -->
                  <div class="col-md-6">
                    <input id="name" type="text" class="form-control" name="name" autocomplete="name" autofocus>
                  </div>
                </div>
    
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