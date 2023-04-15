<?php

// Create a server socket
$socket = socket_create(AF_INET , SOCK_STREAM, SOL_TCP);
socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
socket_bind($socket, 'localhost', 8080);
socket_listen($socket);

$clients = array($socket);

// manter a conexão do servidor ativa
while(true) {
    $read = $clients;
    $write = $except = null;
    socket_select($read, $write, $except, 0, 200000);

    foreach($read as $client) {
        if($client === $socket) {
            $new_socket = socket_accept($socket);
            $clients[] = $new_socket;
        } else {
            $data = socket_read($new_socket, 1024);
            $message = trim($data);
            if(!empty($message)) { //Broadcast message to other clients
                socket_write($new_socket, $data);
            }
        }
    }
}