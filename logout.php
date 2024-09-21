<?php
// Start the session
session_start();

// Destroy all session data to log the user out
session_destroy();

// Redirect the user to the login page or home page
header("Location: index.php");
exit();
