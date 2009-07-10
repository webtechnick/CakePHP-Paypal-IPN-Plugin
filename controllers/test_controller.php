<?php
class TestController extends PaypalIpnAppController{
  var $uses = array();

  function index(){
    echo 'Hello world';
    exit;
  }
}
?>