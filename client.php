<?php

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_connect($socket, 'localhost', 8080);
socket_set_nonblock($socket);
$stdin = [STDIN];
stream_set_blocking($stdin[0], 0);

// Listen for incoming messages
while(true) {
    $data = @socket_read($socket, 1024);

    $message = trim($data);
    if(!empty($message)) {
        echo "$message\n";
    }

    $read = $stdin;
    $write = $except = null;
    if(stream_select($read, $write, $except, 0, 200000)) {
        $name = trim(fgets($read[0]));
        socket_write($socket, $name);
    }
}

socket_close($socket);