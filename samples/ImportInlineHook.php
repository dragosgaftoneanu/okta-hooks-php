<?php
include "../src/ImportInlineHook.php";
use Okta\Hooks\ImportInlineHook;

try{
	$hook = new ImportInlineHook();
	$hook->updateProfile("firstName","John");
	$hook->updateProfile("lastName","Doe");
	$hook->updateAppProfile("firstName","Doe");
	$hook->updateAppProfile("lastName","John");
	$hook->action("create");
	echo $hook->display();
}catch (Exception $e){
        echo $e->getMessage();
}