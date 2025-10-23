<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth_guard.php';

$user = require_auth(); // se quiser permitir público, remova esta linha

$q = trim($_GET['q'] ?? '');
if ($q === '') json([]);

// Campos básicos do salão
// Ajuste os nomes de coluna conforme sua tabela real.
$pdo = pdo();
$sql = "SELECT id, name, phone, address, city, image_url
          FROM salons
         WHERE name LIKE :q
         ORDER BY name ASC
         LIMIT 50";
$st = $pdo->prepare($sql);
$st->execute([':q' => '%'.$q.'%']);

$out = [];
while ($r = $st->fetch()) {
  $out[] = [
    'id' => (int)$r['id'],
    'name' => $r['name'],
    'phone' => $r['phone'],
    'address' => $r['address'],
    'city' => $r['city'],
    'image_url' => $r['image_url'] ?? null
  ];
}
json($out);
