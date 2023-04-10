<?php

// Create a server socket
$socket = socket_create(AF_INET , SOCK_STREAM, SOL_TCP);
socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
socket_bind($socket, 'localhost', 8080);
socket_listen($socket);

$clients = array($socket);

// manter a conexão do servidor ativa
while(true) {
    // Check for new clients
    $read = $clients;
    $write = $except = null;
    socket_select($read, $write, $except, 0, 200000);

    foreach($read as $client) {
        if ($client === $socket) { // Handle incoming connections
            $new_socket = socket_accept($socket);
            $clients[] = $new_socket;
        } else { //Handle incoming message
            $data = socket_read($client, 1024);
            if($data === false) { // Handle sockets closeds
                $index = array_search($client, $clients);
                unset($clients[$index]);
                socket_close($client);
                continue;
            }
            $message = trim($data);
            if(!empty($message)) { //Broadcast message to other clients
                foreach($clients as $other_client) {
                    if($other_client !== $socket && $other_client !== $client) {
                        socket_write($other_client, $message);
                    }
                }
            }
        }
    }
}