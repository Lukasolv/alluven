<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth_guard.php';

$user = require_auth(); // garante usuário logado

$body = $_POST ?: json_decode(file_get_contents('php://input'), true) ?: [];
$userId   = (int)($body['user_id'] ?? 0);
$serviceCsv = trim($body['service_ids'] ?? '');
$dateIso  = trim($body['date'] ?? ''); // YYYY-MM-DD
$time24   = trim($body['time'] ?? ''); // HH:MM

if (!$userId || $serviceCsv==='' || $dateIso==='' || $time24==='') {
  json(['success'=>false,'message'=>'Dados incompletos'], 400);
}

// Segurança: usuário comum só pode criar para ele mesmo
if (strtolower($user['role']) !== 'admin' && $userId !== (int)$user['id']) {
  json(['success'=>false,'message'=>'Operação não permitida'], 403);
}

$ids = array_filter(array_map('intval', explode(',', $serviceCsv)));
if (!$ids) json(['success'=>false,'message'=>'services_ids inválidos'], 400);

$pdo = pdo();
$pdo->beginTransaction();

try {
  // valida se serviços existem
  $in = implode(',', array_fill(0, count($ids), '?'));
  $st = $pdo->prepare("SELECT id FROM services WHERE id IN ($in)");
  $st->execute($ids);
  $found = $st->fetchAll(PDO::FETCH_COLUMN);
  if (count($found) !== count($ids)) {
    throw new Exception('Algum serviço não existe');
  }

  // cria appointment
  $st = $pdo->prepare("INSERT INTO appointments (user_id, date, time, created_at)
                       VALUES (:u,:d,:t,:c)");
  $st->execute([':u'=>$userId, ':d'=>$dateIso, ':t'=>$time24, ':c'=>now()]);
  $apptId = (int)$pdo->lastInsertId();

  // vincula serviços
  $st = $pdo->prepare("INSERT INTO appointment_services (appointment_id, service_id)
                       VALUES (:a,:s)");
  foreach ($ids as $sid) {
    $st->execute([':a'=>$apptId, ':s'=>$sid]);
  }

  $pdo->commit();
  json(['success'=>true,'message'=>'created']);
} catch (Exception $e) {
  $pdo->rollBack();
  json(['success'=>false,'message'=>$e->getMessage()], 400);
}
