<?php
require_once __DIR__ . '/../config.php';

$body = $_POST ?: json_decode(file_get_contents('php://input'), true) ?: [];
$name  = trim($body['name'] ?? '');
$phone = trim($body['phone'] ?? '');
$email = trim($body['email'] ?? '');
$pass  = (string)($body['password'] ?? '');

if ($name==='' || $email==='' || $pass==='') {
  json(['success'=>false,'message'=>'Campos obrigatórios ausentes'], 400);
}

$pdo = pdo();
$st = $pdo->prepare("SELECT id FROM users WHERE email = :e LIMIT 1");
$st->execute([':e'=>$email]);
if ($st->fetch()) {
  json(['success'=>false,'message'=>'E-mail já cadastrado'], 409);
}

$hash = password_hash($pass, PASSWORD_BCRYPT);
$st = $pdo->prepare("INSERT INTO users (name, phone, email, password_hash, role, created_at)
                     VALUES (:n,:p,:e,:h,:r,:c)");
$st->execute([
  ':n'=>$name, ':p'=>$phone, ':e'=>$email, ':h'=>$hash,
  ':r'=>'cliente', ':c'=>now()
]);

json(['success'=>true,'message'=>'ok']);
