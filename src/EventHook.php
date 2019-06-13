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

class EventHook extends Exception
{
	protected $request = array();
	protected $request_headers = array();
	protected $response = array();
	
	public function __construct()
	{
		$request = json_decode(file_get_contents('php://input'),true);
		$request_headers = getallheaders();
		
		if($request['eventType'] != "com.okta.event_hook" && empty($request_headers['X-Okta-Verification-Challenge']))
			return $this->error("Incorrect event type is selected in the request.");
		else
		{
			$this->request = $request;
			$this->request_headers = $request_headers;
		}
	}
	
	public function display()
	{
		if(!empty($this->response))
		{
			return json_encode($this->response,JSON_UNESCAPED_SLASHES);
		}
	}
	
	public function oneTimeVerification()
	{
		if(!empty($this->request_headers['X-Okta-Verification-Challenge']))
		{
			$this->response = array("verification" => $this->request_headers['X-Okta-Verification-Challenge']);
		}
	}
	
	public function getEvent()
	{
		return $this->request['data']['events'];
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