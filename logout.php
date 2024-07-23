<?php
session_start();  // Start the session

// Destroy the session.
session_destroy();

// Redirect to index.html
header("Location: index.html");
exit();
?>
