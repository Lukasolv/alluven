<?php
require_once __DIR__ . '/../config.php';

$body = $_POST ?: json_decode(file_get_contents('php://input'), true) ?: [];
$email = trim($body['email'] ?? '');
$pass  = (string)($body['password'] ?? '');

if ($email==='' || $pass==='') {
  json(['success'=>false,'message'=>'E-mail e senha são obrigatórios'], 400);
}

$pdo = pdo();
$st = $pdo->prepare("SELECT id, name, email, role, password_hash FROM users WHERE email = :e LIMIT 1");
$st->execute([':e'=>$email]);
$u = $st->fetch();
if (!$u || !password_verify($pass, $u['password_hash'])) {
  json(['success'=>false,'message'=>'Credenciais inválidas'], 401);
}

$token = rand_token(40);
$expires = date('Y-m-d H:i:s', time()+TOKEN_TTL_SECONDS);
$pdo->prepare("INSERT INTO api_tokens (user_id, token, created_at, expires_at)
               VALUES (:uid,:t,:c,:x)")
    ->execute([':uid'=>$u['id'], ':t'=>$token, ':c'=>now(), ':x'=>$expires]);

json([
  'success'=>true,
  'token'=>$token,
  'user'=>[
    'id'=>(int)$u['id'],
    'name'=>$u['name'],
    'email'=>$u['email'],
    'role'=>$u['role']
  ]
]);
