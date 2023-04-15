<?php
/*unlink(dirname(__FILE__).'/server.sock');
die();*/
$socket = socket_create(AF_UNIX, SOCK_DGRAM, 0);
$server_side_sock = dirname(__FILE__).'/server.sock';
socket_bind($socket, $server_side_sock);

$vector = new \Ds\Vector();
$vector->allocate(10);
$time = new DateTime();

while(true) {
    $buf = '';
    $from = '';
    $messaqe = socket_recvfrom($socket, $buf, 65536, 0, $from);
    $new_time = new DateTime();

    if(( (int) $new_time->diff($time)->format('%s') ) > 30) {
        $vector->clear();
        $time = new DateTime();
    }

    $values = explode(',', $buf);
    $sendMessage = 'ACK';

    foreach($values as $value) {
        if($vector->count() > 10) {
            $sendMessage = 'Buffer full';
            $vector->shift();
        }
        $vector->push($value);
    };

    $len = strlen($sendMessage);

    $bytes_sent = socket_sendto($socket, $sendMessage, $len, 0, $from);
}

