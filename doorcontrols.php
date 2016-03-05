<?php
 
function curlcall($param){

 $url = "http://192.168.1.50/?$param";
 $ch = curl_init($url);

 curl_setopt ($c, CURLOPT_CONNECTTIMEOUT , 0);
 curl_setopt ($c, CURLOPT_TIMEOUT  , 20);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($ch, CURLOPT_HEADER, 1);
 $response = curl_exec($ch);

 if(!curl_errno($ch))
  {
    $info = curl_getinfo($ch);
  }else{
    echo 'Curl error: ' . curl_error($ch);
  }

  curl_close($ch);
  return $response;
}

if($_REQUEST['trigger']){
 $status = curlcall(6); 
 echo json_encode("triggering");
}

if($_REQUEST['status']){
  $status = curlcall(9);
  if(strpos($status,"Garage door closed")){
    echo json_encode("closed");
  }else{
    echo json_encode("open");
  } 

}



?>