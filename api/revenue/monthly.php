<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth_guard.php';

$user = require_auth();
require_admin($user); // somente admin

$pdo = pdo();
$sql = "SELECT DATE_FORMAT(a.date, '%Y-%m') AS ym,
               SUM(s.price) AS total
        FROM appointments a
        JOIN appointment_services aps ON aps.appointment_id = a.id
        JOIN services s ON s.id = aps.service_id
        GROUP BY ym
        ORDER BY ym DESC";
$st = $pdo->query($sql);

$out = [];
while ($r = $st->fetch()) {
  $out[] = [
    'month'=>$r['ym'],                // ex.: 2025-10
    'expected_amount'=>(float)$r['total']
  ];
}
json($out);
