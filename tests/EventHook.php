<?php
include "../src/EventHook.php";
use Okta\Hooks\EventHook;

try{
        $hook = new EventHook();
        $hook->oneTimeVerification();
        echo $hook->display();
}catch (Exception $e){
        echo $e->getMessage();
}