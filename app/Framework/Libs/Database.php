<?php

namespace App\Framework\Libs;

use App\Framework\Exceptions\DatabaseException;

use PDO;

class Database extends PDO
{
	protected $pdo;

	public $query = '';

	public function __construct($config)
	{
		// Connect to database
		$this->pdo = $this->connect($config);
	}

	protected function connect($config)
	{
		try {
			return new PDO(
				$config['connection'] .';dbname='. $config['name'],
                $config['user'],
                $config['password'],
                $config['options']
			);
		} catch (\PDOException $e) {
			throw new DatabaseException($e->getMessage());
		}
	}

	/**
	 * Description
	 *
	 * @todo 	Return instance of the class, for example App\Models\User
				And use array of wheres!
	 * @param string string
	 * @return void
	 */
	public function select($table, $where = '')
	{
		$whereClause = $where ? " WHERE {$where}" : "";

		$this->query = $this->pdo->prepare("SELECT * FROM {$table}{$whereClause}");
		return $this;
	}

	public function first()
	{
		$this->query->execute();
		$this->query->setFetchMode(PDO::FETCH_CLASS, 'App\Models\User'); 

		return $this->query->fetch();
	}

	public function get()
	{
		$this->query->execute();

		return $this->query->fetchAll(PDO::FETCH_CLASS);
	}
}
