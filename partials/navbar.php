    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <!-- Logo y nombre de la app -->
        <a class="navbar-brand font-weight-bold" 
        <?php 
          if (!isset($_SESSION["user"])):
        ?>
          href="/contacts-app/"
        <?php else: ?>
          href="/contacts-app/home.php"
        <?php endif ?>
        >
          <img class="mr-2" src="./static/img/logo.png" />
          ContactsApp
        </a>

        <!-- Boton burger para mobile -->
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

        <!-- Contenido colapsable -->
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <?php 
            if (!isset($_SESSION["user"])):
          ?>
            <li class="nav-item">
              <a class="nav-link" href="register.php">Register</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="login.php">Login</a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="/contacts-app/home.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/contacts-app/add.php">Add Contact</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/contacts-app/newAdress.php">Add Adress</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="logout.php">Logout</a>
            </li>
          <?php endif ?>
          </ul>

          <?php if (isset($_SESSION["user"])): ?>
            <div class="d-flex flex-column flex-lg-row align-items-lg-center">
              <!-- Email del usuario -->
              <div class="navbar-text text-light order-1 order-lg-2 mb-2 mb-lg-0">
                <?= $_SESSION["user"]["email"]; ?>
              </div>

            <!-- Barra de búsqueda -->
            <?php 
            // Obtener el nombre del script actual
            $currentPage = basename($_SERVER["PHP_SELF"]);

            // Solo mostrar la busqueda en el home
            if($currentPage === "home.php"):
            ?>
              <!-- segundo en móvil con las clases de order -->
              <form id="searchForm" class=" d-flex order-2 order-lg-1 mt-2 mt-lg-0 me-lg-3" role="search">
                <input id="search-input" class="form-control me-2" type="text" placeholder="Search contacts..." aria-label="Search" autocomplete="">
              </form>
            <?php endif; ?>
            </div>
          <?php endif ?>
        </div>
      </div>
    </nav>