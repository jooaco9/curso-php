<?php
  require "db.php";

  // Inicio de la session, entonces si existe la session la toma
  session_start();

  if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    return;
  }

  $userId = $_SESSION["user"]["id"];

  // Ejecutar la consulta y obtener los resultados como un array
  $stmt = $conn->query("SELECT * FROM contacts WHERE user_id = $userId");

  // Array asociativo para recorrer los campos
  $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php require "partials/header.php" ?> 
  <div class="container pt-4 p-3"> 
    <div class="row justify-content-center" id="contacts-container"> 
      <?php if (count($contacts) == 0): ?> 
        <div class="col-md-4 mx-auto"> 
          <div class="card card-body text-center"> 
            <p>No contacts saved yet</p> 
            <a href="add.php" class="btn btn-primary">Add One!</a> 
          </div> 
        </div> 
      <?php endif ?> 
 
      <?php foreach ($contacts as $contact): ?> 
        <div class="col-md-5 col-lg-4 mb-4"> 
          <div class="card mx-auto shadow-sm" style="max-width: 320px;"> 
            <div class="card-body p-3"> 
              <h3 class="card-title text-capitalize fs-4"><?php echo $contact["name"]; ?></h3> 
              <p class="text-muted mb-2"><?php echo $contact["phone_number"]; ?></p> 
 
              <?php  
                // Obtener todas las direcciones del contacto
                $stmt = $conn->query("SELECT adress FROM adress WHERE contact_id = {$contact["id"]}"); 
                $adresses = $stmt->fetchAll(PDO::FETCH_ASSOC); 
              ?> 
 
              <div class="dropdown mb-2"> 
                <!-- Botón desplegable para mostrar las direcciones -->
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="addressDropdown<?php echo $contact["id"]; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                  Addresses
                </button>
                <ul class="dropdown-menu" aria-labelledby="addressDropdown<?php echo $contact["id"]; ?>">
                  <?php if (count($adresses) > 0): ?> 
                    <?php foreach ($adresses as $adress): ?> 
                      <li><a class="dropdown-item"><?php echo $adress["adress"]; ?></a></li>
                    <?php endforeach; ?> 
                  <?php else: ?> 
                    <li><a class="dropdown-item text-muted">No addresses available</a></li>
                  <?php endif; ?> 
                </ul>
              </div>
 
              <div class="d-flex justify-content-between mb-2">

                <!-- Enlace para editar el contacto -->
                <a href="edit.php?id=<?php echo $contact["id"]; ?>" class="btn btn-sm btn-outline-primary">Edit Contact</a> 

                <!-- Enlace para eliminar el contacto -->
                <a href="delete.php?id=<?php echo $contact["id"]; ?>" class="btn btn-sm btn-outline-danger">Delete Contact</a> 
              </div>
              <div class="d-flex justify-content-between">

                <!-- Enlace para editar las direcciones del contacto -->
                <a href="editAdresses.php?id=<?php echo $contact["id"]; ?>" class="btn btn-sm btn-outline-primary">Edit Adresses</a> 

                <!-- Enlace para eliminar las direcciones del contacto -->
                <a href="deleteAdresses.php?id=<?php echo $contact["id"]; ?>" class="btn btn-sm btn-outline-danger">Delete Adress</a> 
              </div>

            </div> 
          </div> 
        </div> 
      <?php endforeach ?> 
    </div> 
  </div> 

  <!-- JS para las busquedas -->
  <script src="static/js/search-contacts.js"></script>
  
<?php require "partials/footer.php" ?>
