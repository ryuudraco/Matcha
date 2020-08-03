<?php 

namespace Src\Beans;

class LikesBean {

    private $id;
    private $origin;
    private $target;
    private $created_at;
    private $updated_at;

    public function getId(): ?int {
		return $this->id;
	}

	public function setId(int $id){
		$this->id = $id;
	}

	public function getOrigin(): ?String {
		return $this->origin;
	}

	public function setOrigin(String $origin){
		$this->origin = $origin;
	}

	public function getTarget(): ?String {
		return $this->target;
	}

	public function setTarget(String $target){
		$this->target = $target;
	}
	
	public function getCreated_at(): ?String {
		return $this->created_at;
	}

	public function setCreated_at(String $created_at){
		$this->created_at = $created_at;
	}

	public function getUpdated_at(): ?String {
		return $this->updated_at;
	}

	public function setUpdated_at(String $updated_at){
		$this->updated_at = $updated_at;
	}
    

}
