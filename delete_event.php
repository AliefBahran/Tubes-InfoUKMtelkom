<?php
include 'connect.php';

$id = $_GET['id'];

$sql = "DELETE FROM events WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

header("Location: Beranda.php");
exit;
