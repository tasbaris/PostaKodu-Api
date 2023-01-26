<?php
 include "class.php";
 $return_array = array();
 $konumlar = array();

 if(isset($_GET["kod"])){
    $kod = $_GET["kod"];
 }else {
    $return_array['result'] = "error"; 
    $return_array['msg'] = "Postcode not found!";
    echo json_encode($return_array);
    die();
 }

 $url = "https://www.postakodu.web.tr/".$kod."-posta-kodlari.html";
 $html = str_get_html(file_get_contents($url, false, stream_context_create(['http' => ['ignore_errors' => true]])));
 $http_response_code = http_response_code();
 if($http_response_code == 404){
    $return_array['result'] = "error"; 
    $return_array['msg'] = "Invalid Postcode!";
    echo json_encode($return_array);
    die();
 }

 try {
    $data = $html->getElementsById("links3")[1];
 } catch (Exception $e) {
    $return_array['result'] = "error"; 
    $return_array['msg'] = "Not Found!";
    echo json_encode($return_array);
    die();
 }

 foreach ($data->find("a") as $element) {
    if($element->innertext()!= "KÖYLER"){
        array_push($konumlar,$element->innertext());
    }
 }
 $konumlar = array_unique($konumlar);
 $return_array['result'] = "success"; 
 $return_array['count'] = count($konumlar);
 $return_array['data'] = $konumlar;
 echo json_encode($return_array);
?>
