<?php
include("../db_connect.php");

$id = $_POST['id'] ?? 0;

$stmt = $conn->prepare("DELETE FROM servicos WHERE id = ?");
$stmt->execute([$id]);

echo json_encode(["success" => true, "message" => "Serviço excluído."]);
?>
