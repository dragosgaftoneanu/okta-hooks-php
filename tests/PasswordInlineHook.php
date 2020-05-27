<?php
include "../src/PasswordInlineHook.php";
use Okta\Hooks\PasswordInlineHook;

try{
	$hook = new PasswordInlineHook();
	if($hook->getCredentials()['username'] == "isaac.brock@example.com" && $hook->getCredentials()['password'] == "Okta")
		echo $hook->allow();
	else
		echo $hook->deny();
}catch (Exception $e){
        echo $e->getMessage();
}