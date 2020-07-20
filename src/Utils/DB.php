<?php

namespace Src\Utils;

class DB
{
	private $connection;

	private $db_name;
	private $db_host;
	private $db_user;
	private $db_pass;

	private static $instance;

	private function __construct()
	{
		$this->loadCredentials();
		$this->connect();
	}

	private function loadCredentials()
	{
		$this->db_name = getenv('DB_NAME');
		$this->db_host = getenv('DB_HOST');
		$this->db_user = getenv('DB_USER');
		$this->db_pass = getenv('DB_PASS');
	}

	private function connect() 
	{
		$dsn = 'mysql:dbname=' . $this->db_name . ';host=' . $this->db_host;
		$this->connection = new \PDO($dsn, $this->db_user, $this->db_pass);
		$this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

	# SINGLETON PATTERN
	private static function getInstance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new DB();
		}

		return self::$instance;
	}

	public static function execute($sql, $params = [])
	{
		return self::getInstance()->executeSql($sql, $params);
	}

	public static function select($sql, $params = [])
	{
		return self::getInstance()->selectSql($sql, $params);
	}

	/**
	 * By relying on the PDO driver and parametised queries, we're offloading SQL injection protection to the database server.
	 * This should provide sufficient protection against any SQLInjection attacks that we may receive, provided that we only pass parametized queries.
	 */
	private function executeSql($sql, $params)
	{
		$statement = $this->connection->prepare($sql);

		$statement->execute($params);
	}

	private function selectSql($sql, $params)
	{
		$statement = $this->connection->prepare($sql);

		$statement->execute($params);

		return $statement->fetchAll();
	}

	/**
	 * Other methods would do just fine, but the preference is to work with objects not arrays :)
	 */
	private function selectSqlClass($sql, $params, $clazz) {
		$statement = $this->connection->prepare($sql);
		$statement->execute($params);
		return $statement->fetchAll(\PDO::FETCH_CLASS, $clazz);
	}

	public static function selectQuery($sql, $clazz, $params = []) {
		return self::getInstance()->selectSqlClass($sql, $params, $clazz);
	}
}