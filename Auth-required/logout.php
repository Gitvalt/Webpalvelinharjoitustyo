<?php
require("../API/sql-connect.php");
Logout($_COOKIE["token"]);
header("Location: ../login.php");
?>