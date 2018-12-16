<?php
 // Инициализируем cURL 
$curl = curl_init('localhost/webnet/');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($curl);
echo $result;