<?php

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_connect($socket, 'localhost', 8080);
socket_set_nonblock($socket);

// Listen for incoming messages
while(true) {
    $data = socket_read($socket, 1024);

    $message = trim($data);
    if(!empty($message)) {
        echo "$message\n";
    }

    echo 'Enter your name: ';
    $name = trim(fgets(STDIN));

    // Send name to server
    socket_write($socket, $name);
}

socket_close($socket);