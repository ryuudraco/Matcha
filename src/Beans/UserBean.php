<?php 

namespace Src\Beans;

class UserBean {

    private $id;
    private $username;
    private $email;
    private $password;
    private $verify_token;
    private $verify_at;
    private $first_name;
    private $last_name;
    private $interests; //this will be removed
    private $biography;
    private $preference;
    private $age;
    private $ip_address;
    private $city;
    private $province;
    private $county;
    private $postal_code;
    private $avatar_image;
    private $gender_id;
    private $created_at;
	private $updated_at;
	private $likes;
	private $blocks;
	private $fame_rating;

    public function getId(): ?int {
		return $this->id;
	}

	public function setId(int $id){
		$this->id = $id;
	}

	public function getUsername(): ?String {
		return $this->username;
	}

	public function setUsername(String $username){
		$this->username = $username;
	}

	public function getEmail(): ?String {
		return $this->email;
	}

	public function setEmail(String $email){
		$this->email = $email;
	}

	public function getPassword(): ?String {
		return $this->password;
	}

	public function setPassword(String $password){
		$this->password = $password;
	}

	public function getVerify_token(): ?String {
		return $this->verify_token;
	}

	public function setVerify_token(String $verify_token){
		$this->verify_token = $verify_token;
	}

	public function getVerify_at(): ?String {
		return $this->verify_at;
	}

	public function setVerify_at(String $verify_at){
		$this->verify_at = $verify_at;
	}

	public function getFirst_name(): ?String {
		return $this->first_name;
	}

	public function setFirst_name(String $first_name){
		$this->first_name = $first_name;
	}

	public function getLast_name(): ?String {
		return $this->last_name;
	}

	public function setLast_name(String $last_name){
		$this->last_name = $last_name;
	}

	public function getInterests(): ?String {
		return $this->interests;
	}

	public function setInterests(String $interests){
		$this->interests = $interests;
	}

	public function getBiography(): ?String {
		return $this->biography;
	}

	public function setBiography(String $biography){
		$this->biography = $biography;
	}

	public function getPreference(): ?String {
		return $this->preference;
	}

	public function setPreference(String $preference){
		$this->preference = $preference;
	}

	public function getAge(): ?int {
		return $this->age;
	}

	public function setAge(int $age){
		$this->age = $age;
	}

	public function getIp_address(): ?String {
		return $this->ip_address;
	}

	public function setIp_address(String $ip_address){
		$this->ip_address = $ip_address;
	}

	public function getCity(): ?String {
		return $this->city;
	}

	public function setCity(String $city){
		$this->city = $city;
	}

	public function getProvince(): ?String {
		return $this->province;
	}

	public function setProvince(String $province){
		$this->province = $province;
	}

	public function getCounty(): ?String {
		return $this->county;
	}

	public function setCounty(String $county){
		$this->county = $county;
	}

	public function getPostal_code(): ?String {
		return $this->postal_code;
	}

	public function setPostal_code(String $postal_code){
		$this->postal_code = $postal_code;
	}

	public function getAvatar_image(): ?String {
		return $this->avatar_image;
	}

	public function setAvatar_image(String $avatar_image){
		$this->avatar_image = $avatar_image;
	}

	public function getGender_id(): ?int {
		return $this->gender_id;
	}

	public function setGender_id(int $gender_id){
		$this->gender_id = $gender_id;
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

	public function setLikes(int $likes) {
		$this->likes = $likes;
	}

	public function getLikes(): ?int {
		return $this->likes;
	}

	public function setBlocks(int $blocks) {
		$this->blocks = $blocks;
	}

	public function getBlocks(): ?int {
		return $this->blocks;
	}

	public function setFame_rating(int $fame_rating){
		$this->fame_rating = $fame_rating;
	}

	public function getFame_rating(): ?int {
		return $this->fame_rating;
	}

}
