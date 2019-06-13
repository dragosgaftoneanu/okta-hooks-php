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

class ImportInlineHook extends Exception
{
	protected $request = "";
	protected $response = array();
	
	protected $profile = array();
	protected $appprofile = array();
	
	public function __construct()
	{
		$request = json_decode(file_get_contents('php://input'),true);
		
		if($request['eventType'] != "com.okta.import.transform")
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

		$this->response[] = array(
			"type" => "com.okta.appUser.profile.update",
			"value" => $this->appprofile
		);

		return json_encode(array("commands" => $this->response),JSON_UNESCAPED_SLASHES);
	}
	
	public function updateProfile($attribute, $value)
	{
		$this->profile[$attribute]=$value;
	}
	
	public function updateAppProfile($attribute, $value)
	{
		$this->appprofile[$attribute]=$value;
	}	
	
	public function action($status)
	{
		if($status == "create")
			$this->response[] = array(
				"type" => "com.okta.action.update",
				"value" => array(
					"result" => "CREATE_USER"
				)
			);
		elseif($status =="link")
			$this->response[] = array(
				"type" => "com.okta.action.update",
				"value" => array(
					"result" => "LINK_USER"
				)
			);
	}
	
	public function linkWith($user)
	{
		$this->response[] = array(
			"type" => "com.okta.user.update",
			"value" => array(
				"id" => $user
			)
		);
	}
	
	public function getUser()
	{
		return $this->request['data']['user'];
	}
	
	public function getAppUser()
	{
		return $this->request['data']['appuser'];
	}
	
	public function getAction()
	{
		return $this->request['data']['action'];
	}
	
	public function getContext()
	{
		return $this->request['data']['context'];
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