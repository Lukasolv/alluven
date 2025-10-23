<?php
include("../db_connect.php");

$id = $_POST['id'] ?? 0;
$service_id = $_POST['service_id'] ?? 0;
$data = $_POST['data'] ?? '';
$hora = $_POST['hora'] ?? '';

$stmt = $conn->prepare("UPDATE agendamentos SET service_id=?, data=?, hora=? WHERE id=?");
$stmt->execute([$service_id, $data, $hora, $id]);

echo json_encode(["success" => true, "message" => "Agendamento atualizado."]);
?>
