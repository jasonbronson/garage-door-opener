<?php

/**
 * Garage Door Opener using arduino and iphone
 * @author jbronson 2015
 */
class GarageDoor {

  protected $url = "http://192.168.1.50/?";
  const TIME_OUT = 20;
  const CONNECTION_TIMEOUT = 50;

  public function __construct(){

      $trigger = isset($_REQUEST['trigger'])?$_REQUEST['trigger']:false;
      $status = isset($_REQUEST['status'])?$_REQUEST['status']:false;
      if($trigger){
        $status = $this->remoteCall(6); 
        echo json_encode("triggering");
      }

      if($status){
        $status = $this->remoteCall(9);
        if(strpos($status,"Garage door closed")){
          echo json_encode("closed");
        }else{
          echo json_encode("open");
        } 
      }

  }

  private function remoteCall($param){
       
       $ch = curl_init($this->url.$param);
       curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT , self::CONNECTION_TIMEOUT);
       curl_setopt ($ch, CURLOPT_TIMEOUT  , self::TIME_OUT);
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


}
 
$door = new GarageDoor();



?>