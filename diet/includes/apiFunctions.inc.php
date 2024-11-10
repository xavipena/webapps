<?php 
/*
    loadFoods($db)
    params
        $url --> url to load foods from
    return array($output,$error,$info)
*/
function loadFoods($url) {

    // create curl resource
    $ch = curl_init();

    // set url
    curl_setopt($ch, CURLOPT_URL, $url);

    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // $output contains the output string
    $output = curl_exec($ch);
    $error = curl_error($ch);
    $info = curl_getinfo($ch);

    // close curl resource to free up system resources
    curl_close($ch);
    return array($output,$error,$info);
}
?>