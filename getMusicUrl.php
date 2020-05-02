<?php
$id = $_GET['id'];
$reg = '/^[0-9][0-9]*$/';
if(preg_match($reg, $id)) {
    $outer_url = 'https://music.163.com/song/media/outer/url?id=' . $id . '.mp3';
    $real_url = get_real_url($outer_url);
    $ssl_url = str_replace('http://', 'https://', $real_url);
    $ssl_url = $ssl_url==='https://music.163.com/404'?$outer_url:$ssl_url;
    header('Location: ' . $ssl_url);
    exit;
}
echo 'ID错误';

function get_real_url($url) {
    $header = get_headers($url, 1);
    if (strpos($header[0],'301') || strpos($header[0],'302')) {
        if(is_array($header['Location'])) {
            return $header['Location'][count($header['Location'])-1];
        }else{
            return $header['Location'];
        }
    }else {
        return $url;
    }
}
?>