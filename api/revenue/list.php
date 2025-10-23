<?php
include("../db_connect.php");

$stmt = $conn->query("
    SELECT s.nome as titulo, s.preco as valor
    FROM agendamentos a
    JOIN servicos s ON s.id = a.service_id
");

$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($dados);
?>
