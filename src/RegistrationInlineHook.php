<?php
/** Copyright © 2019-2021 Dragos Gaftoneanu
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
namespace Okta\Hooks;
use Exception;

class RegistrationInlineHook extends Exception
{
	protected $request = "";
	protected $response = array();
	protected $profile = array();
	protected $deny = FALSE;
	
	public function __construct()
	{
		$request = json_decode(file_get_contents('php://input'),true);
		
		if($request['eventType'] != "com.okta.user.pre-registration")
			return $this->error("Incorrect event type is selected in the request.");
		else
			$this->request = $request;
	}
	
	public function display()
	{
		if(!$this->deny && !empty($this->profile))
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
		{
			$action = "DENY";
			$this->deny = TRUE;
		}else
			$action = "ALLOW";
		
		$this->response[] = array(
			"type" => "com.okta.action.update",
			"value" => array(
				"registration" => "$action"
			)
		);
	}
	
	public function getRaw()
	{
		return $this->request;
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
