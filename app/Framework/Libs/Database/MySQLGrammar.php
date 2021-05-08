<?php

namespace App\Framework\Libs\Database;

use App\Framework\Libs\Database\Grammar;

/**
 * MySQL Grammar. Takes care translating the queries into MySQL string.
 */
class MySQLGrammar implements Grammar
{
	protected $vocalbulary = [];

	/**
	 * The entry point for the builder. Calls all the sub builders.
	 *
	 * @param array $operation		The columns to select.
	 * @param string $table 		The table to select from.
	 * @param array $whereClauses 	The where clauses.
	 * @param array $orderBy 		The order by clause.
	 * @param array $limit 			The limit clause.
	 * @return string 				The complete SQL query.
	 */
	public function buildQuery(array $operation, string $table, array $whereClauses, array $orderBy, array $limit) : string
	{
		$this->vocalbulary = $this->validateVocalbulary($operation, $table, $whereClauses, $orderBy, $limit);

		$query  = $this->buildOperation();

		$query .= $this->buildWheres();
		$query .= $this->buildOrderBy();
		$query .= $this->buildLimit();

		return $query;
	}

	/**
	 * Validate the vocalbulary and throw InvalidQueryException if data is not valid.
	 *
	 * @param array $operation 		The columns to select.
	 * @param string $table 		The table to select table.
	 * @param array $whereClauses 	The where clauses.
	 * @param array $orderBy 		The order by clause.
	 * @param array $limit 			The limit clause.
	 * @return string 				Array containing the vocalbulary.
	 */
	protected function validateVocalbulary(array $operation, string $table, array $whereClauses, array $orderBy, array $limit) : array
	{
		// @todo Validate vocalbulary!
		$this->queryType = $operation['type'];

		/**
		* SELECT:
		* SELECT [columns] FROM [table]
		* type: 'select', columns: [], table: 'table'
		*
		* UPDATE:
		* UPDATE [table] SET [data]
		* type: 'update', data: [], table: 'table'
		*
		* INSERT:
		* INSERT INTO [table] [columns] VALUES [values]
		* type: 'isnert', columns: [], table: 'table'
		*/

		// recognize operation type
		// delegate to corresponding handler (e.g. selectHandler)

		return [
			'operation' 	=> $operation,
			'table' 		=> $table, 
			'whereClauses' 	=> $whereClauses, 
			'orderBy' 		=> $orderBy, 
			'limit' 		=> $limit
		];
	}

	/**
	 * Build the operation clause.
	 * @todo Clean up and separate this to smaller chunks!
	 *
	 * @return string
	 */
	protected function buildOperation() : string
	{
		$operation = $this->vocalbulary['operation'];
		$table = $this->vocalbulary['table'];

		switch($operation['type']) {
			case 'select':
				$colsEscaped = array_map(function($col) {
					return "`{$col}`";
				}, $operation['data']);

				return 'SELECT ' . implode(', ', $colsEscaped) . ' FROM ' . $table;
				break;

			case 'insert':
				$placeholders = '';
				$prefix = '';

				foreach ($operation['data'] as $column => $value) {
					$placeholders .=  $prefix . '?';
					$prefix = ', ';
				}

				return "INSERT INTO {$table} (". implode(', ', array_keys($operation['data'])) .") VALUES ({$placeholders})";
				break;

			case 'update':
				$updates = '';
				$prefix = '';

				foreach($operation['data'] as $column => $value) {
					$updates .= $prefix . $column .'=?';
					$prefix = ', ';
				}

				return "UPDATE {$table} SET {$updates}";
				break;

			case 'delete':
				return "DELETE FROM {$table}";
				break;

			default:
				// throw new MySQLException();
				dd('Virheellinen operaatio: '. $operation['type']);
				break;
		}
	}

	// /**
	//  * Build the select clause.
	//  *
	//  * @return string
	//  */
	// protected function buildSelect() : string
	// {
	// 	return 'SELECT ' . implode(', ', $this->vocalbulary['selects']);
	// }

	// /**
	//  * Build the from clause.
	//  *
	//  * @return string
	//  */
	// protected function buildFrom() : string
	// {
	// 	return ' FROM ' . $this->vocalbulary['from'];
	// }

	/**
	 * Build the where clauses.
	 *
	 * @return string
	 */
	protected function buildWheres() : string
	{
		$result = '';
		$first = true;

		foreach ($this->vocalbulary['whereClauses'] as $clause) {
			$keyword = $first ? 'WHERE' : $clause[3];
			$result .=  " {$keyword} {$clause[0]} {$clause[1]} '{$clause[2]}'";
			$first = false;
		}

		return $result;
	}

	/**
	 * Build the order by clause.
	 *
	 * @return string
	 */
	protected function buildOrderBy() : string
	{
		return (count($this->vocalbulary['orderBy']) == 2)
			? " ORDER BY {$this->vocalbulary['orderBy'][0]} {$this->vocalbulary['orderBy'][1]}"
			: '';
	}

	/**
	 * Build the limit clause.
	 *
	 * @return string
	 */
	protected function buildLimit() : string
	{
		if (count($this->vocalbulary['limit']) !== 2) return '';

		$result = " LIMIT {$this->vocalbulary['limit'][0]}";

		// add offset if larger than 0
		if ($this->vocalbulary['limit'][1] > 0)
			$result .= ", {$this->limit[1]}";

		return $result;
	}
}
