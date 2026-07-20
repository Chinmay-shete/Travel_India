<?php
function env($key, $default = null) {
  $env_file = __DIR__ . '/../.env';
  if (!file_exists($env_file)) {
    return $default;
  }
  $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($lines as $line) {
    if (strpos(trim($line), '#') === 0) continue;
    if (strpos($line, '=') === false) continue;
    [$k, $v] = explode('=', $line, 2);
    if (trim($k) === $key) return trim($v);
  }
  return $default;
}
?>
