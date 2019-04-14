<?php

namespace DipsAgency\Site\Model;

class InstagramAPI extends SettingsInstagramAPI
{
	var $clientID = "";
	var $clientSecret = "";
	var $redirectURI = "";

	public function __construct() {
        $settings = $this->instaConnect();
		$this->clientID = $settings['clientID'];
		$this->clientSecret = $settings['clientSecret'];
		$this->redirectURI = $settings['redirectURI'];
	}

	public function getAccessTokenAndUserDetails($code) {
		$postFields = array(
			"client_id" => $this->clientID,
			"client_secret" => $this->clientSecret,
			"grant_type" => "authorization_code",
			"redirect_uri" => $this->redirectURI,
			"code" => $code
        );

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api.instagram.com/oauth/access_token");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
		$response = curl_exec($ch);
		curl_close($ch);

		return json_decode($response, true);
	}
}