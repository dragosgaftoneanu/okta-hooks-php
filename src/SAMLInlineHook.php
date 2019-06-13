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

class SAMLInlineHook extends Exception
{
	protected $request = "";
	protected $response = array();
	
	public function __construct()
	{
		$request = json_decode(file_get_contents('php://input'),true);
		
		if($request['eventType'] != "com.okta.saml.tokens.transform")
			return $this->error("Incorrect event type is selected in the request.");
		else
			$this->request = $request;
	}
	
	public function display()
	{
		return json_encode(array("commands" => array(array("type" => "com.okta.assertion.patch", "value" => $this->response))),JSON_UNESCAPED_SLASHES);
	}
	
	public function addClaim($name, $nameFormat, $xsiType, $value)
	{
		$this->response[] = array(
			"op" => "add",
			"path" => "/claims/" . $name,
			"value" => array(
				"attributes" => array(
					"NameFormat" => $nameFormat
				),
				"attributeValues" => array(array(
					"attributes" => array(
						"xsi:type" => $xsiType
					),
					"value" => $value
				))
			)
		);
	}
	
	public function modifyClaim($name, $newValue)
	{
		$this->response[] = array(
			"op" => "replace",
			"path" => "/claims/" . $name . "/attributeValues/value",
			"value" => $newValue
		);
	}
	
	public function modifyClaimArray($name, $position, $newValue)
	{
		$this->response[] = array(
			"op" => "replace",
			"path" => "/claims/" . $name . "/attributeValues/" . $position . "/value",
			"value" => $newValue
		);
	}
	
	public function modifyAssertion($path, $newValue)
	{
		$this->response[] = array(
			"op" => "replace",
			"path" => $path,
			"value" => $newValue
		);
	}
	
	public function getRequest()
	{
		return $this->request['data']['context']['request'];
	}
	
	public function getProtocol()
	{
		return $this->request['data']['context']['protocol'];
	}
	
	public function getSession()
	{
		return $this->request['data']['context']['session'];
	}

	public function getUser()
	{
		return $this->request['data']['context']['user'];
	}
	
	public function getAssertionSubject()
	{
		return $this->request['data']['assertion']['subject'];
	}
	
	public function getAssertionClaims()
	{
		return $this->request['data']['assertion']['claims'];
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
