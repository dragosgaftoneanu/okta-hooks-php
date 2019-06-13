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

class RegistrationInlineHook extends Exception
{
	protected $request = "";
	protected $response = array();
	protected $profile = array();
	
	public function __construct()
	{
		$request = json_decode(file_get_contents('php://input'),true);
		
		if($request['eventType'] != "com.okta.user.pre-registration")
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
		$this->response[] = array(
			"type" => "com.okta.user.profile.update",
			"value" => $this->profile
		);
		return json_encode(array("commands" => $this->response),JSON_UNESCAPED_SLASHES);
	}
	
	public function changeProfileAttribute($attribute, $value)
	{
		$this->profile[$attribute]=$value;
	}
	
	public function allowUser($status)
	{
		if(!$status)
			$this->response[] = array(
				"type" => "com.okta.action.update",
				"value" => array(
					"action" => "DENY"
				)
			);
	}
	
	public function getUser()
	{
		return $this->request['data']['user'];
	}
	
	public function getRequest()
	{
		return $this->request['data']['context']['request'];
	}
	
	public function error($message, $reason="", $locationType="", $location="", $domain="")
	{
		throw new \Exception(json_encode(array(
			'error' => array(
			'errorSummary' => $message,
			'errorCauses' => array(array(
				'errorSummary' => $message,
				'reason' => $reason,
				'locationType' => $locationType,
				'location' => $location,
				'domain' => $domain
			)
		))),JSON_UNESCAPED_SLASHES));
	}
}
