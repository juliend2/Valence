<?php

$opts = array();

function _format_command($host, $function, $command) {
  return "[$host] $function: $command\n";
}

function _get_remote_host_string() {
  global $opts;
  return "{$opts['user']}@{$opts['host']}";
}

function connect($options) { 
  global $connection, $opts;
  $defaults = array(
    'host' => 'yourhost.com',
    'user' => 'youruser',
    'publickeyfile'=> '/home/youruser/.ssh/id_rsa.pub',
    'privatekeyfile'=> '/home/youruser/.ssh/id_rsa',
    'port' => 22,
    'passphrase' => ''
  );
  $opts = array_merge($defaults, $options);
  $connection = ssh2_connect($opts['host'], $opts['port']);
  if (!ssh2_auth_pubkey_file($connection, $opts['user'],
                            $opts['publickeyfile'],
                            $opts['privatekeyfile'])) {
    die('Public Key Authentication Failed');
  }
}

function run($command) {
  global $current_directory, $connection;
  $stream = ssh2_exec($connection, "cd $current_directory && $command");
  stream_set_blocking($stream, true);
  $out = stream_get_contents($stream);
  fclose($stream);
  print _format_command(_get_remote_host_string(), 'run', $command);
  return $out;
}

function local($command) {
  print _format_command(gethostname(), 'local', $command);
  exec($command, $out);
  print implode("\n", $out)."\n";
  return $out;
}

function cd($directory, $func) {
  global $current_directory;
  print _format_command(_get_remote_host_string(), 'cd', $directory);
  $previous_directory = $current_directory;
  $current_directory = $directory;
  $func();
  $current_directory = $previous_directory;
}

