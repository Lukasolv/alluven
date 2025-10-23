<?php
require_once __DIR__.'/config.php';

/**
 * Lê o header Authorization: Bearer <token>,
 * valida na tabela api_tokens e retorna o usuário.
 */
function require_auth(): array {
  $hdr = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
  if (!preg_match('/Bearer\s+(.+)/i', $hdr, $m)) {
    json(['success'=>false,'message'=>'Missing token'], 401);
  }
  $token = trim($m[1]);

  $pdo = pdo();
  $sql = "SELECT u.id, u.name, u.email, u.role, t.expires_at
          FROM api_tokens t
          JOIN users u ON u.id = t.user_id
          WHERE t.token = :t LIMIT 1";
  $st = $pdo->prepare($sql);
  $st->execute([':t'=>$token]);
  $row = $st->fetch();
  if (!$row) json(['success'=>false,'message'=>'Invalid token'], 401);

  if (strtotime($row['expires_at']) < time()) {
    json(['success'=>false,'message'=>'Token expired'], 401);
  }
  return $row; // ['id','name','email','role','expires_at']
}

function require_admin(array $user) {
  if (strtolower($user['role']) !== 'admin') {
    json(['success'=>false,'message'=>'Admin only'], 403);
  }
}
