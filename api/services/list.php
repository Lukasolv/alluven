<?php
require_once __DIR__ . '/../config.php';

$pdo = pdo();
$st = $pdo->query("SELECT id, name, price, duration_minutes
                   FROM services ORDER BY name ASC");
$out = [];
while ($r = $st->fetch()) {
  $out[] = [
    'id'=>(int)$r['id'],
    'name'=>$r['name'],
    'price'=>(float)$r['price'],
    'duration_minutes'=>(int)$r['duration_minutes']
  ];
}
json($out);
