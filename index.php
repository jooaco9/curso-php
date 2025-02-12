<?php
  require "db.php";

  // Ejecutar la consulta y obtener los resultados como un array
  $stmt = $conn->query("SELECT * FROM contacts");
  $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap -->
  <link rel="stylesheet" 
    href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/5.3.3/darkly/bootstrap.min.css" integrity="sha512-HDszXqSUU0om4Yj5dZOUNmtwXGWDa5ppESlX98yzbBS+z+3HQ8a/7kcdI1dv+jKq+1V5b01eYurE7+yFjw6Rdg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script 
    defer
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"                   integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
    crossorigin="anonymous"></script>

  <!-- Styles - Static content -->
  <link rel="stylesheet" href="static/css/index.css">
  <title>Contacts App</title>
</head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand font-weight-bold" href="#">
          <img class="mr-2" src="./static/img/logo.png" />
          ContactsApp
        </a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="/contacts-app/">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/contacts-app/add.php">Add Contact</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <main>
      <div class="container pt-4 p-3">
        <div class="row">
          <?php if (count($contacts) == 0): ?>
            <div class="col-md-4 mx-auto">
              <div class="card card-body text-center">
                <p>No contacts saved yet</p>
                <a href="add.php">Add One!</a>
              </div>
            </div>
          <?php endif ?>

          <?php foreach ($contacts as $contact): ?>
          <div class="col-md-4 mb-3">
            <div class="card text-center">
              <div class="card-body">
                <h3 class="card-title text-capitalize"><?php echo $contact["name"]; ?></h3>
                <p class="m-2"><?php echo $contact["phone_number"]; ?></p>
                <a href="edit.php?id=<?php echo $contact["id"]; ?>" class="btn btn-secondary mb-2">Edit Contact</a>
                <a href="delete.php?id=<?php echo $contact["id"]; ?>" class="btn btn-danger mb-2">Delete Contact</a>
              </div>
            </div>
          </div>
          <?php endforeach ?>
        </div>
      </div>
    </main>
  </body>
</html>