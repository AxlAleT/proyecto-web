<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../../admin.html");
    exit();
}
session_unset();
session_destroy();
exit();
?>
