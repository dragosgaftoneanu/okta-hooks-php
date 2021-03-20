<?php
include "../src/RegistrationInlineHook.php";
use Okta\Hooks\RegistrationInlineHook;

try{
	$hook = new RegistrationInlineHook();
	$hook->allowUser(TRUE);
	echo $hook->display();
}catch (Exception $e){
        echo $e->getMessage();
}