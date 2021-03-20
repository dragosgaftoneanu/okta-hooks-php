<?php
include "../src/SAMLInlineHook.php";
use Okta\Hooks\SAMLInlineHook;

try{
	$hook = new SAMLInlineHook();
	$hook->addClaim("test","urn:oasis:names:tc:SAML:2.0:attrname-format:basic","xs:string","test");
	echo $hook->display();
}catch (Exception $e){
        echo $e->getMessage();
}