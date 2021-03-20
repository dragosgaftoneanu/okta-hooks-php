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
	
	public function getRaw()
	{
		return $this->request;
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