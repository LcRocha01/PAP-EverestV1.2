<?php
session_start();

$role = $_SESSION['user_role'] ?? $_SESSION['user_tipo'] ?? null;
if (!isset($_SESSION['user_id']) || $role !== 'logistica') {
    header("Location: ../login.php");
    exit;
}
