<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth_guard.php';

$user = require_auth(); // pega o user do token

$pdo = pdo();
$sql = "SELECT a.id, a.date, a.time,
               u.name AS customer_name, u.phone AS customer_phone,
               GROUP_CONCAT(s.name ORDER BY s.name SEPARATOR ', ') AS services
        FROM appointments a
        JOIN users u ON u.id = a.user_id
        JOIN appointment_services aps ON aps.appointment_id = a.id
        JOIN services s ON s.id = aps.service_id
        WHERE a.user_id = :uid
        GROUP BY a.id
        ORDER BY a.date DESC, a.time DESC";
$st = $pdo->prepare($sql);
$st->execute([':uid' => (int)$user['id']]);

$out = [];
while ($r = $st->fetch()) {
  $out[] = [
    'id'             => (int)$r['id'],
    'service_name'   => $r['services'],
    'customer_name'  => $r['customer_name'],
    'customer_phone' => $r['customer_phone'],
    'date'           => $r['date'],
    'time'           => $r['time']
  ];
}
json($out);
