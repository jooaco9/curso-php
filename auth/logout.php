<?php 
// Tomamos las session y la destruimos para cuando haga el logout
session_start();
session_destroy();

header("Location: ../views/index.php")
?>