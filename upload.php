<?php 
require_once 'sdk.php';
$sdk = new SDK();
$token = '041beedff31c88e7dc3f224c085b1f8779d5f11913fd23aefc6bf10f1e715d204ab37b4a704a379082322'; 
$upload_url = json_decode($sdk->requestByGet("https://api.vk.com/method/photos.getMessagesUploadServer?access_token={$token}&v=5.78"), true)['response']['upload_url'];
if(isset($_POST['response'])) {
    $queue = json_decode(file_get_contents('denfoxes_queue.json'), true);
    $response = json_decode($_POST['response'], true);
    $rp = $sdk->requestByPost("https://api.vk.com/method/photos.saveMessagesPhoto", "access_token={$token}&v=5.78&server={$response['server']}&hash={$response['hash']}&photo={$response['photo']}");
    $resp = json_decode($rp, true);
    var_dump($queue);
    $queue['queue'][] = 'photo'.$resp['response'][0]['owner_id'].'_'.$resp['response'][0]['id'].'_'.$resp['response'][0]['access_key'];
    var_dump($queue);
    $sdk->sendToFile('denfoxes_queue.json', json_encode($queue));
}
if(isset($_GET['qiset'])) {
    $qi = json_decode(file_get_contents('denfoxes_queue.json'), true);
    $qi['qi'] = $_GET['qiset'];
    $sdk->sendToFile('denfoxes_queue.json', json_encode($qi));
}
if(isset($_GET['queclear'])) {
    $queue = json_decode(file_get_contents('denfoxes_queue.json'), true);
    $queue['queue'] = array();
    $sdk->sendToFile('denfoxes_queue.json', json_encode($queue));
}
?>
<!DOCTYPE html>
<html><head><title>Upload files</title></head>
<body>
    <?= isset($_POST['response'])?'Ваш файл добавлен в очередь!<br> /Сервер вернул: "'.$rp.'"/':'';?><br><br>
    <form action="<?=$upload_url?>" enctype="multipart/form-data" method="post">Ваш файл:<input type="file" name="file"><br><input type="submit"></form><br>
<b>Уже загрузили?</b><form action="denfoxes_upload.php" method="post">Скопируйте и вставьте ответ от сервера здесь: <input type="text" name="response"><input type="submit"></form><br>
На данный момент очередь выглядит так:<?php $queue = json_decode(file_get_contents('denfoxes_queue.json'), true); var_dump($queue);?></body></html>
