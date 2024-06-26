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
     * @param array $operation      The columns to select.
     * @param string $table         The table to select from.
     * @param array $joins          Joined tables with join conditions.
     * @param array $whereClauses   The where clauses.
     * @param array $orderBy        The order by clause.
     * @param array $limit          The limit clause.
     * @return string               The complete SQL query.
     */
    public function buildQuery(array $operation, string $table, array $joins, array $whereClauses, array $orderBy, array $limit) : string
    {
        $this->vocalbulary = $this->validateVocalbulary($operation, $table, $joins, $whereClauses, $orderBy, $limit);

        $query  = $this->buildOperation();

        $query .= $this->buildJoins();
        $query .= $this->buildWheres();
        $query .= $this->buildOrderBy();
        $query .= $this->buildLimit();

        return $query;
    }

    /**
     * Validate the vocalbulary and throw InvalidQueryException if data is not valid.
     *
     * @param array $operation      The columns to select.
     * @param string $table         The table to select table.
     * @param array $joins          Joined tables with join conditions.
     * @param array $whereClauses   The where clauses.
     * @param array $orderBy        The order by clause.
     * @param array $limit          The limit clause.
     * @return string               Array containing the vocalbulary.
     */
    protected function validateVocalbulary(array $operation, string $table, array $joins, array $whereClauses, array $orderBy, array $limit) : array
    {
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
            'operation'     => $operation,
            'table'         => $table,
            'joins'         => $joins,
            'whereClauses'  => $whereClauses,
            'orderBy'       => $orderBy,
            'limit'         => $limit
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
                return 'SELECT ' . implode(', ', $operation['data']) . ' FROM ' . $table;

            case 'insert':
                $placeholders = '';
                $prefix = '';

                foreach ($operation['data'] as $column => $value) {
                    $placeholders .=  $prefix . '?';
                    $prefix = ', ';
                }

                return "INSERT INTO {$table} (". implode(', ', array_keys($operation['data'])) .") VALUES ({$placeholders})";

            case 'update':
                $updates = '';
                $prefix = '';

                foreach($operation['data'] as $column => $value) {
                    $updates .= $prefix . $column .'=?';
                    $prefix = ', ';
                }

                return "UPDATE {$table} SET {$updates}";

            case 'delete':
                return "DELETE FROM {$table}";

            default:
                // throw new MySQLException();
                return dd('Virheellinen operaatio: '. $operation['type']);
        }
    }

    // /**
    //  * Build the select clause.
    //  *
    //  * @return string
    //  */
    // protected function buildSelect() : string
    // {
    //  return 'SELECT ' . implode(', ', $this->vocalbulary['selects']);
    // }

    // /**
    //  * Build the from clause.
    //  *
    //  * @return string
    //  */
    // protected function buildFrom() : string
    // {
    //  return ' FROM ' . $this->vocalbulary['from'];
    // }

    /**
     * Build the join clauses.
     *
     * @return string
     */
    protected function buildJoins() : string
    {
        $result = '';

        foreach ($this->vocalbulary['joins'] as list($joinType, $table, $condition)) {
            $result .= " $joinType JOIN $table ON $condition";
        }

        return $result;
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
            $compVal = strlen($clause[2]) > 0 ? "'{$clause[2]}'" : $clause[2];
            $result .=  " {$keyword} {$clause[0]} {$clause[1]} {$compVal}";
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
            $result .= ", {$this->vocalbulary['limit'][1]}";

        return $result;
    }
}
