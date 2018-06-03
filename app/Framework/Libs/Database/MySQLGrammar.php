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
	 * @param array $selects 		The columns to select.
	 * @param string $from 			The table to select from.
	 * @param array $whereClauses 	The where clauses.
	 * @param array $orderBy 		The order by clause.
	 * @param array $limit 			The limit clause.
	 * @return string 				The complete SQL query.
	 */
	public function buildQuery(array $selects, string $from, array $whereClauses, array $orderBy, array $limit) : string
	{
		$this->vocalbulary = $this->validateVocalbulary($selects, $from, $whereClauses, $orderBy, $limit);

		$query  = $this->buildSelect();
		$query .= $this->buildFrom();
		$query .= $this->buildWheres();
		$query .= $this->buildOrderBy();
		$query .= $this->buildLimit();

		return $query;
	}

	/**
	 * Validate the vocalbulary and throw InvalidQueryException if data is not valid.
	 *
	 * @param array $selects 		The columns to select.
	 * @param string $from 			The table to select from.
	 * @param array $whereClauses 	The where clauses.
	 * @param array $orderBy 		The order by clause.
	 * @param array $limit 			The limit clause.
	 * @return string 				Array containing the vocalbulary.
	 */
	protected function validateVocalbulary(array $selects, string $from, array $whereClauses, array $orderBy, array $limit) : array
	{
		// @todo Validate vocalbulary!

		return [
			'selects' 		=> $selects, 
			'from' 			=> $from, 
			'whereClauses' 	=> $whereClauses, 
			'orderBy' 		=> $orderBy, 
			'limit' 		=> $limit
		];
	}

	/**
	 * Build the select clause.
	 *
	 * @return string
	 */
	protected function buildSelect() : string
	{
		return 'SELECT ' . implode(', ', $this->vocalbulary['selects']);
	}

	/**
	 * Build the from clause.
	 *
	 * @return string
	 */
	protected function buildFrom() : string
	{
		return ' FROM ' . $this->vocalbulary['from'];
	}

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
			$result .=  " {$keyword} {$clause[0]} {$clause[1]} {$clause[2]}";
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
