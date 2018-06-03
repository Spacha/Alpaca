<?php

namespace App\Framework\Libs\Database;

use App\Framework\Libs\Database\{
	DatabaseConnection,
	MySQLGrammar
};

/**
 * QueryBuilder.
 */
class QueryBuilder
{
	protected $connection;
	protected $grammar;

	protected $selects = [];
	protected $from = '';
	protected $whereClauses = [];
	protected $orderBy = [];
	protected $limit = [];

	/**
	 * Creates the connection to the database specified in given config set.
	 *
	 * @param array  $config 	The database config set.
	 * @return void
	 */
	public function __construct(array $config = [])
	{
		$this->connect($config);
	}

	/**
	 * Creates the connection to the database and inits the correct
	 * grammar specified in the config set.
	 * @todo Support other types of databases too.
	 *
	 * @param array $config 	The database config set.
	 * @return void
	 */
	protected function connect(array $config)
	{
		$this->connection = new DatabaseConnection(dbConfig($config));
		$this->grammar = new MySQLGrammar();
	}

	/**
	 * Build the query using the grammar.
	 *
	 * @return string
	 */
	protected function buildQuery()
	{
		return $this->grammar->buildQuery(
			$this->selects,
			$this->from, 
			$this->whereClauses,
			$this->orderBy,
			$this->limit
		);
	}


	/**----------------
	 * GRAMMAR methods
	 *---------------*/

	/**
	 * Make a select query.
	 *
	 * @param mixed $columns 	string or array
	 * @return App\Framework\Libs\Database\QueryBuilder
	 */
	public function select($columns = '*') : QueryBuilder
	{
		$columns = (is_string($columns)) ? [$columns] : $columns;

		foreach ($columns as $column) {

			// let's not add duplicates
			if (in_array($column, $this->selects)) continue; 
			
			$this->selects[] = $column;
		}

		return $this;
	}

	/**
	 * Choose which table to query.
	 *
	 * @param string $table
	 * @return App\Framework\Libs\Database\QueryBuilder
	 */
	public function from(string $table) : QueryBuilder
	{
		$this->from = $table;
		return $this;
	}

	/**
	 * Make a where clause.
	 * @example $query->where('id', 1)
	 * @example $query->where(['id', '=', 1])
	 *
	 * @param mixed $arg1 		string/array
	 * @param string $arg2
	 * @return App\Framework\Libs\Database\QueryBuilder
	 */
	public function where($arg1, $arg2 = null) : QueryBuilder
	{
		$clause = is_string($arg1)
			? [$arg1, '=' ,$arg2, 'AND']
			: [$arg1[0], $arg1[1], $arg1[2], 'AND'];

		$this->whereClauses[] = $clause;
		return $this;
	}

	/**
	 * Make a OR where clause.
	 *
	 * @param mixed $arg1 		string/array
	 * @param string $arg2
	 * @return App\Framework\Libs\Database\QueryBuilder
	 */
	public function orWhere($arg1, $arg2 = null) : QueryBuilder
	{
		$clause = is_string($arg1)
			? [$arg1, '=' ,$arg2, 'OR']
			: [$arg1[0], $arg1[1], $arg1[2], 'OR'];

		$this->whereClauses[] = $clause;
		return $this;
	}

	/**
	 * Make an order by clause.
	 *
	 * @param string $by 		What column to order by.
	 * @param string $order 	In what order to order (ASC/DESC).
	 * @return App\Framework\Libs\Database\QueryBuilder
	 */
	public function orderBy(string $by, string $order = 'ASC') : QueryBuilder
	{
		$this->orderBy = [$by, $order];
		return $this;
	}

	/**
	 * Make a limit clause.
	 *
	 * @param int $limit 	The limit.
	 * @param int $offset 	The limit offset.
	 * @return App\Framework\Libs\Database\QueryBuilder
	 */
	public function limit(int $limit, int $offset = 0) : QueryBuilder
	{
		$this->limit = [$limit, $offset];
		return $this;
	}

	/**
	 * Make a limit clause.
	 *
	 * @param string $returnType 	What type of collection we want to return.
	 * @return mixed 				stdClass/array depending on $returnType
	 */
	public function get(string $returnType = 'stdClass')
	{
		$query = $this->buildQuery();

		$sth = $this->connection->prepare($query);
		$sth->execute();

		if ($returnType == 'stdClass') {
			return $sth->fetchAll($this->connection::FETCH_CLASS);
		}

		return $sth->fetchAll();
	}

	public function toSql() : string
	{
		return $this->buildQuery();
	}
}
