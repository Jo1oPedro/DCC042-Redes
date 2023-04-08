<?php

class Clientes
{
    static $clienteSockets = [];

    public function socket()
    {
        $socket = stream_socket_client("tcp://localhost:8000");
        stream_set_blocking($socket, false);
        stream_socket_accept($socket);
        array_push(self::$clienteSockets, $socket);
    }

    public function clienteSockets()
    {
        return self::$clienteSockets;
    }
}