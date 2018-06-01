<?php
$queue = json_decode(file_get_contents('queue.json'), true);
$token = 'replace0me0with0real0vk0access0token';
require_once 'sdk.php';
$sdk = new SDK();
$sdk->requestByGet("https://api.vk.com/method/messages.send?v=5.78&attachment={$queue['queue'][$queue['qi']]}&peer_id=/*real peer_id*/&access_token={$token}");
$queue['qi']++;
$sdk->sendToFile('queue.json', json_encode($queue));
?>
