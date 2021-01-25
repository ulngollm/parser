<?php
$url = 'http://andimart.ru/catalog/otoplenie/komplektuyushchie_dlya_otopleniya/fitingi_dlya_otopleniya/';
$id = curl_init($url);
curl_setopt($id, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($id);
curl_close($id);
echo $response;