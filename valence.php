<?php

function connect($options) { 
  global $connection;
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
  return $out;
}

function cd($directory, $func) {
  global $current_directory;
  $previous_directory = $current_directory;
  $current_directory = $directory;
  $func();
  $current_directory = $previous_directory;
}

