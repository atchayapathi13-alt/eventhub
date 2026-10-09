<?php
// Override via environment variables in production; defaults suit XAMPP.
$pdo = new PDO('mysql:host='.(getenv('DB_HOST')?:'localhost').';dbname='.(getenv('DB_NAME')?:'eventhub_db').';charset=utf8mb4',
  getenv('DB_USER')?:'root', getenv('DB_PASS')?:'', [
  PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES=>false]);
