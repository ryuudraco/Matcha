<?php 

namespace Src\Beans;

class MessagesBean {

    private $thread_id;
    private $origin;
	private $target;
	private $message;

    public function getThread_id(): ?int {
		return $this->thread_id;
	}

	public function setThread_id(int $thread_id){
		$this->id = $thread_id;
	}

	public function getOrigin(): ?UserBean {
		return $this->origin;
	}

	public function setOrigin(UserBean $origin){
		$this->origin = $origin;
	}

	public function getTarget(): ?UserBean {
		return $this->target;
	}

	public function setTarget(UserBean $target){
		$this->target = $target;
	}

	public function getMessage(): ?String {
		return $this->message;
	}

	public function setMessage(String $message){
		$this->message = $message;
	}
    

}
