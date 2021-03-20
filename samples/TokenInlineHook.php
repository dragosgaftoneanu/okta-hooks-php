<?php
include "../src/TokenInlineHook.php";
use Okta\Hooks\TokenInlineHook;

try{
	$hook = new TokenInlineHook();
	$hook->modifyIDTokenLifetime(86400);
	$hook->modifyAccessTokenClaim("aud","new_audience");
	echo $hook->display();
}catch (Exception $e){
        echo $e->getMessage();
}