
<?php
$conn = new mysqli(
    "sql112.infinityfree.com",
    "if0_43395741",
    "Bareilly12345",
    "if0_43395741_XXX"
);

if ($conn->connect_error) {
    die("Database connection failed.");
}

$conn->set_charset("utf8mb4");
session_start();
?>