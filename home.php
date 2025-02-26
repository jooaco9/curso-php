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
    <div class="row justify-content-center"> 
      <?php if (count($contacts) == 0): ?> 
        <div class="col-md-4 mx-auto"> 
          <div class="card card-body text-center"> 
            <p>No contacts saved yet</p> 
            <a href="add.php" class="btn btn-primary">Add One!</a> 
          </div> 
        </div> 
      <?php endif ?> 
 
      <?php foreach ($contacts as $contact): ?> 
        <div class="col-md-5 col-lg-4 mb-4"> <!-- Cambiado de col-md-6 a col-md-5 col-lg-4 para hacer las cards más pequeñas -->
          <div class="card shadow-sm" style="max-width: 320px;"> <!-- Añadido max-width para limitar el tamaño -->
            <div class="card-body p-3"> <!-- Padding reducido de p-4 a p-3 -->
              <h3 class="card-title text-capitalize fs-4"><?php echo $contact["name"]; ?></h3> <!-- Reducción de tamaño del título -->
              <p class="text-muted mb-2"><?php echo $contact["phone_number"]; ?></p> 
 
              <?php  
                $stmt = $conn->query("SELECT adress FROM adress WHERE contact_id = {$contact["id"]}"); 
                $adresses = $stmt->fetchAll(PDO::FETCH_ASSOC); 
              ?> 
 
              <div class="dropdown mb-2"> <!-- Cambiado a dropdown para ahorrar espacio -->
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
 
              <div class="d-flex justify-content-between mt-2"> 
                <a href="edit.php?id=<?php echo $contact["id"]; ?>" class="btn btn-sm btn-outline-primary">Edit</a> 
                <a href="delete.php?id=<?php echo $contact["id"]; ?>" class="btn btn-sm btn-outline-danger">Delete</a> 
              </div> 
            </div> 
          </div> 
        </div> 
      <?php endforeach ?> 
    </div> 
  </div> 
<?php require "partials/footer.php" ?>
