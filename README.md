# Okta Hooks PHP
This repository contains the source for the Okta Hooks PHP library that can be used for integrating the new [Okta Hooks](https://www.okta.com/hooks/) feature inside your PHP application.

:warning: **Disclaimer:** This is not an official product and does not qualify for Okta Support.

## Installation
You can install this library by running the following command through Composer

```
composer require dragosgaftoneanu/okta-hooks-php
```

## Requirements
* An Okta account, called an _organization_ (you can sign up for a free [developer organization](https://developer.okta.com/signup/))
* A local web server that runs PHP 5.0+
* [getallheaders()](https://www.php.net/manual/en/function.getallheaders.php) function available for usage
* The following features enabled on your Okta organization (you can request them through an email to [support@okta.com](mailto:support@okta.com))
	* [Event Hook](https://developer.okta.com/docs/concepts/event-hooks/): `CALLBACKS`, `WEBHOOKS`
	* [Token Inline Hook](https://developer.okta.com/docs/reference/token-hook/): `CALLBACKS`, `API_ACCESS_MANAGEMENT_EXTENSIBILITY`
	* [Import Inline Hook](https://developer.okta.com/use_cases/inline_hooks/import_hook/import_hook/): `CALLBACKS`, `IMPORT_SYNC_CALLBACKS`
	* [SAML Assertion Inline Hook](https://developer.okta.com/use_cases/inline_hooks/saml_hook/saml_hook/): `CALLBACKS`, `SAML_EXTENSIBILITY`
	* [Registration Inline Hook](https://developer.okta.com/use_cases/inline_hooks/registration_hook/registration_hook/): `CALLBACKS`
	* [Password Import Inline Hook](https://developer.okta.com/docs/reference/password-hook/): `CALLBACKS`
	
## Table of Contents
  * [Event Hook](#event-hook)
    + [Methods available](#methods-available)
      - [getEvent()](#getevent)
      - [oneTimeVerification()](#onetimeverification)
      - [display()](#display)
    + [Example](#example)
  * [Token Inline Hook](#token-inline-hook)
    + [Methods available](#methods-available-1)
      - [getAccessTokenClaims()](#getaccesstokenclaims)
      - [getIDTokenClaims()](#getidtokenclaims)
      - [getScopes()](#getscopes)
      - [getPolicy()](#getpolicy)
      - [getProtocol()](#getprotocol)
      - [getRequest()](#getrequest)
      - [getSession()](#getsession)
      - [getUser()](#getuser)
      - [addAccessTokenClaim($name, $value)](#addaccesstokenclaimname-value)
      - [modifyAccessTokenClaim($name, $value)](#modifyaccesstokenclaimname-value)
      - [removeAccessTokenClaim($name)](#removeaccesstokenclaimname)
      - [modifyAccessTokenLifetime($value)](#modifyaccesstokenlifetimevalue)
      - [addIDTokenClaim($name, $value)](#addidtokenclaimname-value)
      - [modifyIDTokenClaim($name, $value)](#modifyidtokenclaimname-value)
      - [removeIDTokenClaim($name)](#removeidtokenclaimname)
      - [modifyIDTokenLifetime($value)](#modifyidtokenlifetimevalue)
      - [display()](#display-1)
    + [Example](#example-1)
  * [Import Inline Hook](#import-inline-hook)
    + [Methods available](#methods-available-2)
      - [getAction()](#getaction)
      - [getAppUser()](#getappuser)
      - [getContext()](#getcontext)
      - [getUser()](#getuser-1)
      - [updateAppProfile($attribute, $value)](#updateappprofileattribute-value)
      - [updateProfile($attribute, $value)](#updateprofileattribute-value)
      - [action($status)](#actionstatus)
      - [linkWith($user)](#linkwithuser)
      - [display()](#display-2)
    + [Example](#example-2)
  * [SAML Assertion Inline Hook](#saml-assertion-inline-hook)
    + [Methods available](#methods-available-3)
      - [getAssertionClaims()](#getassertionclaims)
      - [getAssertionSubject()](#getassertionsubject)
      - [getProtocol()](#getprotocol-1)
      - [getRequest()](#getrequest-1)
      - [getSession()](#getsession-1)
      - [getUser()](#getuser-2)
      - [addClaim($name, $nameFormat, $xsiType, $value)](#addclaimname-nameformat-xsitype-value)
      - [modifyClaim($name, $newValue)](#modifyclaimname-newvalue)
      - [modifyAssertion($path, $newValue)](#modifyassertionpath-newvalue)
      - [display()](#display-3)
    + [Example](#example-3)
  * [Registration Inline Hook](#registration-inline-hook)
    + [Methods available](#methods-available-4)
      - [getRequest()](#getrequest-2)
      - [getUser()](#getuser-3)
      - [changeProfileAttribute($attribute, $value)](#changeprofileattributeattribute-value)
      - [allowUser($status)](#allowuserstatus)
      - [display()](#display-4)
      - [error($message, $errorCode, $reason, $locationType, $location, $domain)](#errormessage-errorcode-reason-locationtype-location-domain)
    + [Example](#example-4)
  * [Password Import Inline Hook](#password-import-inline-hook)
    + [Methods available](#methods-available-5)
      - [getCredentials()](#getcredentials)
      - [getRequest()](#getrequest-3)
      - [allow()](#allow)
      - [deny()](#deny)
    + [Example](#example-5)

## Event Hook
### Methods available
You can find below the methods implemented for the class in order to successfully execute the hook.

#### getRaw()
This method returns the full request coming from Okta as an array.

#### oneTimeVerification()
This method checks for `X-Okta-Verification-Challenge` header in the request and replies with the verification JSON in order to complete the verification step for Okta.

#### display()
This method displays the final response to the request coming from Okta.

### Example
You can find below an example script for verifying an event hook.

```php
use Okta\Hooks\EventHook;

try{
        $hook = new EventHook();
        $hook->oneTimeVerification();
        echo $hook->display();
}catch (Exception $e){
        echo $e->getMessage();
}
```

The answer that the library will return will look like the following.

```
{
    "verification": "T8tTyt5x9WobwSh0np41HlSF6lwl9elP0-cpcmNU"
}
```

## Token Inline Hook
### Methods available
You can find below the methods implemented for the class in order to successfully execute the hook.

#### getRaw()
This method returns the full request coming from Okta as an array.

#### addAccessTokenClaim($name, $value)
This method tells Okta to add a claim inside the access token that will be returned.

#### modifyAccessTokenClaim($name, $value)
This method tells Okta to modify a claim inside the access token that will be returned with a new value.

#### removeAccessTokenClaim($name)
This method tells Okta to remove a claim inside the access token that will be returned.

#### modifyAccessTokenLifetime($value)
This method tells Okta to modify the access token's lifetime. The token can have a lifetime of minimum 5 minutes (300 seconds) and a maximum of 24 hours (86400 seconds).

#### addIDTokenClaim($name, $value)
This method tells Okta to add a claim inside the ID token that will be returned.

#### modifyIDTokenClaim($name, $value)
This method tells Okta to modify a claim inside the ID token that will be returned with a new value.

#### removeIDTokenClaim($name)
This method tells Okta to remove a claim inside the ID token that will be returned.

#### modifyIDTokenLifetime($value)
This method tells Okta to modify the ID token's lifetime. The token can have a lifetime of minimum 5 minutes (300 seconds) and a maximum of 24 hours (86400 seconds).

#### display()
This method displays the final response to the request coming from Okta.

### Example
You can find below an example script for adding a new claim inside an ID token, modifying an ID token's lifetime expiration to 1 day and changing an access token's audience.

```php
use Okta\Hooks\TokenInlineHook;

try{
	$hook = new TokenInlineHook();
	$hook->modifyIDTokenLifetime(86400);
	$hook->modifyAccessTokenClaim("aud","new_access_token_audience");
	echo $hook->display();
}catch (Exception $e){
        echo $e->getMessage();
}
```

The answer that the library will return is the following.

```
{
    "commands": [
        {
            "type": "com.okta.identity.patch",
            "value": [
                {
                    "op": "add",
                    "path": "/claims/claim",
                    "value": "test_value"
                },
                {
                    "op": "replace",
                    "path": "/token/lifetime/expiration",
                    "value": 86400
                }
            ]
        },
        {
            "type": "com.okta.access.patch",
            "value": [
                {
                    "op": "replace",
                    "path": "/claims/aud",
                    "value": "new_access_token_audience"
                }
            ]
        }
    ]
}
```

## Import Inline Hook
### Methods available
You can find below the methods implemented for the class in order to successfully execute the hook.

#### getRaw()
This method returns the full request coming from Okta as an array.

#### updateAppProfile($attribute, $value)
This method tells Okta to update a profile attribute from the ones available under data.appUser in the request coming from Okta.

#### updateProfile($attribute, $value)
This method tells Okta to update a profile attribute from the ones available under data.user in the request coming from Okta.

#### action($status)
This method tells Okta for the current imported user to either create it as a new user inside of Okta (`$status = "create";`) or to link it with an existing one (`$status = "link";`).

#### linkWith($user)
If action is set to link the user with an existing one, with this method you can mention the user ID with which the current imported user will be linked.

#### display()
This method displays the final response to the request coming from Okta.

### Example
You can find below an example script for modifying the first name and last name for both user profile and app user profile and to link the user with an existing Okta user that has user ID set to 00uozbgc03wzqoaXp2p6.

```php
use Okta\Hooks\ImportInlineHook;

try{
	$hook = new ImportInlineHook();
	$hook->updateProfile("firstName","John");
	$hook->updateProfile("lastName","Doe");
	$hook->updateAppProfile("firstName","Doe");
	$hook->updateAppProfile("lastName","John");
	$hook->action("link");
	$hook->linkWith("00uozbgc03wzqoaXp2p6");
	echo $hook->display();
}catch (Exception $e){
        echo $e->getMessage();
}
```

The answer that the library will return is the following.

```
{
    "commands": [
        {
            "type": "com.okta.action.update",
            "value": {
                "result": "LINK_USER"
            }
        },
        {
            "type": "com.okta.user.update",
            "value": {
                "id": "00uozbgc03wzqoaXp2p6"
            }
        },
        {
            "type": "com.okta.user.profile.update",
            "value": {
                "firstName": "John",
                "lastName": "Doe"
            }
        },
        {
            "type": "com.okta.appUser.profile.update",
            "value": {
                "firstName": "Doe",
                "lastName": "John"
            }
        }
    ]
}
```

## SAML Assertion Inline Hook
### Methods available
You can find below the methods implemented for the class in order to successfully execute the hook.

#### getRaw()
This method returns the full request coming from Okta as an array.

#### addClaim($name, $nameFormat, $xsiType, $value)
This method tells Okta to add a claim inside the assertion.

#### modifyClaim($name, $newValue)
This method tells Okta to modify a specific claim value inside the assertion.

#### modifyAssertion($path, $newValue)
This method tells Okta to modify a specific value inside the assertion.

#### display()
This method displays the final response to the request coming from Okta.

### Example
You can find below an example script for adding a new claim inside the assertion.

```php
use Okta\Hooks\SAMLInlineHook;

try{
	$hook = new SAMLInlineHook();
	$hook->addClaim("test","urn:oasis:names:tc:SAML:2.0:attrname-format:basic","xs:string","test");
	echo $hook->display();
}catch (Exception $e){
        echo $e->getMessage();
}
```

The answer that the library will return is the following.

```
{
    "commands": [
        {
            "type": "com.okta.assertion.patch",
            "value": [
                {
                    "op": "add",
                    "path": "/claims/test",
                    "value": {
                        "attributes": {
                            "NameFormat": "urn:oasis:names:tc:SAML:2.0:attrname-format:basic"
                        },
                        "attributeValues": [
                            {
                                "attributes": {
                                    "xsi:type": "xs:string"
                                },
                                "value": "test"
                            }
                        ]
                    }
                }
            ]
        }
    ]
}
```

## Registration Inline Hook
### Methods available
You can find below the methods implemented for the class in order to successfully execute the hook.

#### getRaw()
This method returns the full request coming from Okta as an array.

#### changeProfileAttribute($attribute, $value)
This method tells Okta to update a profile attribute from the ones available under data.user.profile in the request coming from Okta.

#### allowUser($status)
This method tells Okta to either allow the user to be registered (`$status = TRUE;`) or not (`$status = FALSE;`).

#### display()
This method displays the final response to the request coming from Okta.

#### error($message, $errorCode, $reason, $locationType, $location, $domain)
This method displays an error message for the end-user upon registration, as exemplified in the documentation [here](https://developer.okta.com/use_cases/inline_hooks/registration_hook/registration_hook/#sample-json-payload-of-request). 

### Example
You can find below an example script for modifying the first name and last name for user profile and then block the user.

```php
use Okta\Hooks\RegistrationInlineHook;

try{
	$hook = new RegistrationInlineHook();
	$hook->changeProfileAttribute("firstName", "John");
	$hook->changeProfileAttribute("lastName", "Doe");
	$hook->allowUser(FALSE);
	echo $hook->display();
}catch (Exception $e){
        echo $e->getMessage();
}
```

The answer that the library will return is the following.

```
{
    "commands": [
        {
            "type": "com.okta.action.update",
            "value": {
                "action": "DENY"
            }
        },
        {
            "type": "com.okta.user.profile.update",
            "value": {
                "firstName": "John",
                "lastName": "Doe"
            }
        }
    ]
}
```

## Password Import Inline Hook
### Methods available
You can find below the methods implemented for the class in order to successfully execute the hook.

#### getRaw()
This method returns the full request coming from Okta as an array.

#### getCredentials()
This method returns data.context.credential from the request coming from Okta as an array.

#### getRequest()
This method returns data.context.request from the request coming from Okta as an array.

#### allow()
This method displays a VERIFIED response that will tell Okta that the credentials are correct and allow the user to authenticate.

#### deny()
This method displays an UNVERIFIED response that will tell Okta that the credentials are incorrect and will not allow the user to authenticate.

### Example
You can find below an example script for checking the username and a password received from Okta and allowing the request if they contain specific values.

```php
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
```

The answer that the library will return is the following.

```
{
    "commands": [{
        "type": "com.okta.action.update",
        "value": {
            "credential": "VERIFIED"
        }
    }]
}
```

## Bugs?
If you find a bug or encounter an issue when using the library, please open an issue on GitHub [here](https://github.com/dragosgaftoneanu/okta-hooks-php/issues) and it will be further investigated.