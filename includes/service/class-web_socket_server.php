<?
include 'class-yousen_websocket.php';

class WebSocketServer{
  protected $config;
  public function __construct($config){
    $config = $config;
}

  function run(){
    $websocket = new websocket($config);
    $websocket->run();
  }
  function WSevent($type,$event){
    global $websocket;
      if('msg'==$type){
        handleMessage($event['sign'],$event['msg']);
      }
  }
  
  function handleMessage($sign,$t){
    global $websocket;
    //利用cache_ip处理逻辑。
  
    $websocket->write($sign,'done.');
  }
}
?>
