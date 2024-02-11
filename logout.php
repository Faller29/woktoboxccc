<?php

    unset($_SESSION['name']);
    unset($_SESSION['username']);
    unset($_SESSION['role']);
    unset($_SESSION['email']);
    unset($_SESSION['mobile']);
    unset($_SESSION['password']);
    session_destroy();
    header('location: index.html');
  

?>