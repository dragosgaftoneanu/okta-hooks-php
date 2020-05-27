# Okta Hooks PHP
This repository contains the source for the Okta Hooks PHP SDK that can be used for integrating the new [Okta Hooks](https://www.okta.com/hooks/) feature inside your PHP application.

:warning: **Disclaimer:** This is not an official product and does not qualify for Okta Support.

## Installation
You can install this SDK by running the following command through Composer

```
composer require dragosgaftoneanu/okta-hooks-php
```

## Requirements
* An Okta account, called an _organization_ (you can sign up for a free [developer organization](https://developer.okta.com/signup/))
* A local web server that runs PHP 5.6+
* [getallheaders()](https://www.php.net/manual/en/function.getallheaders.php) function available for usage
* The following features enabled on your Okta organization (you can request them through an email to [support@okta.com](mailto:support@okta.com))
	* [Event Hook](https://developer.okta.com/docs/concepts/event-hooks/): `CALLBACKS`, `WEBHOOKS`
	* [Token Inline Hook](https://developer.okta.com/docs/reference/token-hook/): `CALLBACKS`, `API_ACCESS_MANAGEMENT_EXTENSIBILITY`
	* [Import Inline Hook](https://developer.okta.com/use_cases/inline_hooks/import_hook/import_hook/): `CALLBACKS`, `IMPORT_SYNC_CALLBACKS`
	* [SAML Assertion Inline Hook](https://developer.okta.com/use_cases/inline_hooks/saml_hook/saml_hook/): `CALLBACKS`, `SAML_EXTENSIBILITY`
	* [Registration Inline Hook](https://developer.okta.com/use_cases/inline_hooks/registration_hook/registration_hook/): `CALLBACKS`
	* [Password Import Inline Hook](https://developer.okta.com/docs/reference/password-hook/): `CALLBACKS`