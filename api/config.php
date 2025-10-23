<?php

const DB_HOST = "www.thyagoquintas.com.br";
const DB_NAME = "engenharia_72";
const DB_USER = "engenharia_72";
const DB_PASS = "piranhavermelha";

const TOKEN_TTL_SECONDS = 7 * 24 * 60 * 60;

// CORS básico (ajuste se quiser restringir)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

function json($data, int $code = 200) {
  http_response_code($code);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  exit;
}

function pdo(): PDO {
  static $pdo = null;
  if ($pdo) return $pdo;
  $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4';
  $pdo = new PDO($dsn, DB_USER, DB_PASS, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);
  return $pdo;
}

function now(): string { return gmdate('Y-m-d H:i:s'); }

function rand_token(int $len = 40): string {
  return bin2hex(random_bytes(intval($len/2)));
}
