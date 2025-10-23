<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth_guard.php';

$user = require_auth();

$q = trim($_GET['q'] ?? '');
if ($q === '') json([]);

$pdo = pdo();
$st = $pdo->prepare(
  "SELECT id, name, phone, email
     FROM users
    WHERE name LIKE :q OR email LIKE :q OR phone LIKE :q
    ORDER BY name ASC
    LIMIT 50"
);
$st->execute([':q'=>'%'.$q.'%']);

$out = [];
while ($r = $st->fetch()) {
  $out[] = [
    'id'=>(int)$r['id'],
    'name'=>$r['name'],
    'phone'=>$r['phone'],
    'email'=>$r['email']
  ];
}
json($out);
