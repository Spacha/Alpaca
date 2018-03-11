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
	 * @todo This is not gonna be like this for long!
	 * @param string $table
	 * @param array $data
	 * @return bool
	 */
	public function insert(string $table, array $data)
	{
		ksort($data);
		$fieldNames = implode('`, `', array_keys($data));
		$fieldValues = ':'. implode(', :', array_keys($data));

		$this->query = $this->pdo->prepare("INSERT INTO $table (`$fieldNames`) VALUES($fieldValues)");
		
		foreach ($data as $key => $value) {
            $this->query->bindValue(":$key", $value);
        }
        
        return $this->query->execute();
	}

	/**
	 * Prepare a select query.
	 *
	 * @todo 	And use array of wheres!
	 * @param string string
	 * @return void
	 */
	public function select($table, $where = '')
	{
		$whereClause = $where ? " WHERE {$where}" : "";

		$this->query = $this->pdo->prepare("SELECT * FROM {$table}{$whereClause}");
		return $this;
	}

	/**
	 * Fetch first matching row.
	 *
	 * @param string $object Object to return
	 * @return object
	 */
	public function first($object = 'stdClass')
	{
		$this->query->execute();
		$this->query->setFetchMode(PDO::FETCH_CLASS, $object);

		return $this->query->fetch();
	}

	/**
	 * Return the results as an array of objects
	 *
	 * @return array
	 */
	public function get() : array
	{
		$this->query->execute();

		return $this->query->fetchAll(PDO::FETCH_CLASS);
	}
}
