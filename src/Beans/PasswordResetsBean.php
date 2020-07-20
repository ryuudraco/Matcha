<?php 

namespace Src\Beans;

class PasswordResetsBean {

    private $email;
    private $token;
    private $created_at;

	public function getEmail(): ?String {
		return $this->email;
	}

	public function setEmail(String $email){
		$this->email = $email;
	}

	public function getToken(): ?String {
		return $this->token;
	}

	public function setToken(String $token){
		$this->token = $token;
	}

	public function getCreated_at(): ?String {
		return $this->created_at;
	}

	public function setCreated_at(String $created_at){
		$this->created_at = $created_at;
	} 

}
