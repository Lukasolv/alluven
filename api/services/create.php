<?php
include("../db_connect.php");

$nome = $_POST['nome'] ?? '';
$preco = $_POST['preco'] ?? 0;

if (empty($nome)) {
    echo json_encode(["success" => false, "message" => "Nome obrigatório."]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO servicos (nome, preco) VALUES (?, ?)");
$stmt->execute([$nome, $preco]);

echo json_encode(["success" => true, "message" => "Serviço adicionado."]);
?>
