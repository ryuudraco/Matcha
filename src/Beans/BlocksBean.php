<?php 

namespace Src\Beans;

class BlocksBean {

    private $id;
    private $origin_id;
    private $target_id;
    private $created_at;
    private $updated_at;

    public function getId(): ?int {
		return $this->id;
	}

	public function setId(int $id){
		$this->id = $id;
	}

	public function getOrigin_id(): ?int {
		return $this->origin_id;
	}

	public function setOrigin_id(int $id){
		$this->origin_id = $id;
	}

	public function getTarget_id(): ?int {
		return $this->target_id;
	}

	public function setTarget_id(int $id){
		$this->target_id = $id;
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
