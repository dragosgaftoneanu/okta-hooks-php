<?php
/** Copyright © 2019, Okta, Inc.
 *
 *  Licensed under the Apache License, Version 2.0 (the 'License');
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an 'AS IS' BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */
namespace Okta\Hooks;
use Exception;

class TokenInlineHook extends Exception
{
	protected $request = "";
	protected $response = array();
	
	protected $id_token = array();
	protected $access_token = array();
	
	public function __construct()
	{
		$request = json_decode(file_get_contents('php://input'),true);
		
		if($request['eventType'] != "com.okta.oauth2.tokens.transform")
			return $this->error("Incorrect event type is selected in the request.");
		else
			$this->request = $request;
	}
	
	public function verifyAuthorizationHeader($authorization)
	{
		if(getallheaders()['Authorization'] != $authorization)
			return $this->error("Authorization header is invalid.");
	}
	
	public function display()
	{
		if(!empty($this->id_token))
			$this->response['commands'][] = array(
				'type' => 'com.okta.identity.patch',
				'value' => $this->id_token
			);
			
		if(!empty($this->access_token))
		$this->response['commands'][] = array(
			'type' => 'com.okta.access.patch',
			'value' => $this->access_token
		);
		
		return json_encode($this->response,JSON_UNESCAPED_SLASHES);
	}
	
	public function addIDTokenClaim($name, $value)
	{
		$this->id_token[] = array(
			"op" => "add",
			"path" => "/claims/" . $name,
			"value" => $value
		);
	}
	
	public function modifyIDTokenClaim($name, $value)
	{
		$this->id_token[] = array(
			"op" => "replace",
			"path" => "/claims/" . $name,
			"value" => $value
		);		
	}
	
	
	public function removeIDTokenClaim($name)
	{
		$this->id_token[] = array(
			"op" => "remove",
			"path" => "/claims/" . $name
		);		
	}
	
	public function modifyIDTokenLifetime($value)
	{
		if($value < 300 || $value > 86400)
			return;
		
		$this->id_token[] = array(
			"op" => "replace",
			"path" => "/token/lifetime/expiration",
			"value" => $value
		);			
	}
	
	
	public function addAccessTokenClaim($name, $value)
	{
		$this->access_token[] = array(
			"op" => "add",
			"path" => "/claims/" . $name,
			"value" => $value
		);
	}	
	
	public function modifyAccessTokenClaim($name, $value)
	{
		$this->access_token[] = array(
			"op" => "replace",
			"path" => "/claims/" . $name,
			"value" => $value
		);
	}
	
	public function removeAccessTokenClaim($name)
	{
		$this->access_token[] = array(
			"op" => "remove",
			"path" => "/claims/" . $name
		);
	}
	
	public function modifyAccessTokenLifetime($value)
	{
		if($value < 300 || $value > 86400)
			return;
	
		$this->access_token[] = array(
			"op" => "replace",
			"path" => "/token/lifetime/expiration",
			"value" => $value
		);			
	}
	
	public function getUser()
	{
		return $this->request['data']['context']['user'];
	}
	
	public function getSession()
	{
		return $this->request['data']['context']['session'];
	}
	
	public function getRequest()
	{
		return $this->request['data']['context']['request'];
	}
	
	public function getProtocol()
	{
		return $this->request['data']['context']['protocol'];
	}
	
	public function getPolicy()
	{
		return $this->request['data']['context']['policy'];
	}
	
	public function getIDTokenClaims()
	{
		return $this->request['data']['identity'];
	}
	
	public function getAccessTokenClaims()
	{
		return $this->request['data']['access'];
	}
	
	public function getScopes()
	{
		return $this->request['data']['scopes'];
	}
	
	private function error($message)
	{		
		throw new \Exception(
			json_encode(array(
				"error" => array(
					"errorSummary" => $message
				)
			),JSON_UNESCAPED_SLASHES)
		);
	}
}