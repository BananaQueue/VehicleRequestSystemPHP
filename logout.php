<?php
// Start the session so we can access and destroy session data
session_start();

// Destroy all session variables to log the user out
session_destroy();

// Redirect the user to the public dashboard after logout
header('Location: dashboard.php');
exit;
