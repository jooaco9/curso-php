    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
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
          <div class="d-flex justify-content-between w-100">
            <ul class="navbar-nav">
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
            <?php if (isset($_SESSION["user"])):?>
            <div class="p-2">
              <?= $_SESSION["user"]["email"]; ?>
            </div>
            <?php endif ?>
          </div>
        </div>
      </div>
    </nav>