Valence
=======

Valence is a DSL for interacting with a server via SSH.

Installation
------------

1. Install the SSH2 php library
  (you might need sudo)
  
        $ pecl install ssh2 channel://pecl.php.net/ssh2-0.11.3

2. add `/path/to/valence/bin` to your shell `$PATH`

Usage
-----

1. create a `valencefile.php` file in your project root.

2. in this file, set up the connection like this:
```php
        $current_directory = '/home/remoteuser'; // this is your starting path. must be set before the connect() call
        connect(array(
          'host'=>'hostname.com', 
          'user'=>'remoteuser',
          'publickeyfile'=> '/home/username/.ssh/id_rsa.pub',
          'privatekeyfile'=> '/home/username/.ssh/id_rsa'
        ));
```
3. you can now use the Valence DSL:
```php
        echo run('ls');
        cd('/home/remoteuser/src', function() {
          echo run('ls');
          run("echo 'joie'");
        });
        echo run('ls');
```


