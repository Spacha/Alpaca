<?php

namespace App\Framework\Libs\Database;

use \App\Framework\Libs\Database\{
    QueryBuilderException,
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
    protected $inserts = [];
    protected $updates = [];
    protected $delete = false;

    protected $table = '';
    protected $joins = [];
    protected $whereClauses = [];
    protected $orderBy = [];
    protected $limit = [];

    /**
     * Creates the connection to the database specified in given config set.
     *
     * @param  string $config   The database config file.
     * @return void
     */
    public function __construct(string $config = 'dbConfig')
    {
        $this->connect($config);
    }

    /**
     * Creates the connection to the database and inits the correct
     * grammar specified in the config set.
     * @todo Support other types of databases too.
     *
     * @param string $config    The database config file.
     * @return void
     */
    protected function connect(string $config)
    {
        $this->connection = new DatabaseConnection(dbConfig($config));
        $this->grammar = new MySQLGrammar();
    }

    /**
     * Build the query using the grammar.
     *
     * @param string $resetState    Whether to reset the query builder's state.
     * @return array
     */
    protected function buildQuery($resetState = false) : array
    {
        $operation = $this->getOperation();

        $result = [
            $this->grammar->buildQuery(
                $operation,
                $this->table, 
                $this->joins,
                $this->whereClauses,
                $this->orderBy,
                $this->limit
            ),
            $operation['data']
        ];

        if ($resetState)
            $this->resetState();

        return $result;
    }

    /**
     * Resets the query builder.
     * 
     * @return void
     */
    protected function resetState()
    {
        $this->selects = [];
        $this->inserts = [];
        $this->updates = [];
        $this->delete = false;

        $this->table = '';
        $this->whereClauses = [];
        $this->orderBy = [];
        $this->limit = [];
    }

    protected function getOperation() : array
    {
        if (!empty($this->selects))
            return ['type' => 'select', 'data' => $this->selects];

        if (!empty($this->inserts))
            return ['type' => 'insert', 'data' => $this->inserts];

        if (!empty($this->updates))
            return ['type' => 'update', 'data' => $this->updates];

        if (!empty($this->delete))
            return ['type' => 'delete', 'data' => []];

        throw new QueryBuilderException("Cannot build a query without an operation");
    }


    /**----------------
     * GRAMMAR methods
     *---------------*/

    /**
     * Make a select query.
     * type: 'select'
     * data: ['col1', 'col2']
     *
     * @param mixed $columns    string or array
     * @return \App\Framework\Libs\Database\QueryBuilder
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
     * Make an insert.
     * type: 'insert'
     * data: ['col1' => 'value1', 'col2' => 'value2']
     *
     * @param array $values     Key-value pairs to insert into the table.
     * @return \App\Framework\Libs\Database\QueryBuilder
     */
    public function insert(array $values) : QueryBuilder
    {
        foreach($values as $column => $value) {
            $this->inserts[$column] = $value;
        }

        return $this;
    }

    /**
     * Make an update.
     * type: 'update'
     * data: ['col1' => 'value1', 'col2' => 'value2']
     *
     * @param array $values     Key-value pairs to update into the table.
     * @return \App\Framework\Libs\Database\QueryBuilder
     */
    public function update(array $values) : QueryBuilder
    {
        foreach($values as $column => $value) {
            $this->updates[$column] = $value;
        }

        return $this;
    }

    /**
     * Make a delete.
     *
     * @return \App\Framework\Libs\Database\QueryBuilder
     */
    public function delete() : QueryBuilder
    {
        $this->delete = true;
        return $this;
    }

    /**
     * Choose which table to query (select). Alias of table().
     *
     * @param string $table
     * @return \App\Framework\Libs\Database\QueryBuilder
     */
    public function from(string $table) : QueryBuilder
    {
        return $this->table($table);
    }

    /**
     * Choose which table to query (insert). Alias of table().
     *
     * @param string $table
     * @return \App\Framework\Libs\Database\QueryBuilder
     */
    public function into(string $table) : QueryBuilder
    {
        return $this->table($table);
    }

    /**
     * Choose which table to query (any).
     *
     * @param string $table
     * @return \App\Framework\Libs\Database\QueryBuilder
     */
    public function table(string $table) : QueryBuilder
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Make a left join clause.
     * @author Miika Sikala <miikasikala96@gmail.com>
     *
     * @param  string $table
     * @param  string $condition
     * @return \App\Framework\Libs\Database\QueryBuilder
     */
    public function leftJoin(string $table, string $condition) : QueryBuilder
    {
        $this->joins[] = ['LEFT', $table, $condition];
        return $this;
    }

    /**
     * Make a where clause.
     * @example $query->where('id', 1)
     * @example $query->where(['id', '=', 1])
     *
     * @param mixed $arg1       string/array
     * @param string $arg2
     * @return \App\Framework\Libs\Database\QueryBuilder
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
     * Make an OR where clause.
     *
     * @param mixed $arg1       string/array
     * @param string $arg2
     * @return \App\Framework\Libs\Database\QueryBuilder
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
     * @param string $by        What column to order by.
     * @param string $order     In what order to order (ASC/DESC).
     * @return \App\Framework\Libs\Database\QueryBuilder
     */
    public function orderBy(string $by, string $order = 'ASC') : QueryBuilder
    {
        $this->orderBy = [$by, $order];
        return $this;
    }

    /**
     * Make a limit clause.
     *
     * @param int $limit    The limit.
     * @param int $offset   The limit offset.
     * @return \App\Framework\Libs\Database\QueryBuilder
     */
    public function limit(int $limit, int $offset = 0) : QueryBuilder
    {
        $this->limit = [$limit, $offset];
        return $this;
    }

    /**
     * Execute and return the result set.
     *
     * @param string $returnType    What type of collection we want to return.
     * @param string $resetState    Whether to reset the query builder's state.
     * @return mixed                stdClass/array depending on $returnType
     */
    public function get(string $returnType = 'stdClass', $resetState = true)
    {
        list($query, $params) = $this->buildQuery($resetState);

        $sth = $this->connection->prepare($query);
        $sth->execute();
        // TODO: Use parameter binding!
        // $sth->execute($params);

        if ($returnType == 'stdClass') {
            return $sth->fetchAll($this->connection::FETCH_CLASS);
        }

        return $sth->fetchAll();
    }

    /**
     * Get the first result.
     *
     * @param string $returnType    What type of collection we want to return.
     * @return mixed                stdClass/array depending on $returnType
     */
    public function first(string $returnType = 'stdClass')
    {
        $result = $this->limit(1)->get();

        return array_key_exists(0, $result) ? $result[0] : $result;
    }

    /**
     * Execute insert, update or delete action.
     *
     * @return bool
     */
    public function execute()
    {
        list($query, $params) = $this->buildQuery();

        $sth = $this->connection->prepare($query);
        
        return $sth->execute(array_values($params));
    }

    /**
     * Return the SQL query without actually executing it.
     *
     * @return string
     */
    public function toSql() : string
    {
        return $this->buildQuery()[0];
    }

    /**
     * Return the SQL query and the data values without actually executing it.
     *
     * @return array
     */
    public function toSqlWithData() : array
    {
        $query = $this->buildQuery();

        return [
            'query' => $query[0],
            'data'  => $query[1]
        ];
    }

    /**
     * Get last inserted id.
     *
     * @return int/false
     */
    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }

    /*
    protected function sanitize(string $str) : string
    {
        //
    }
    */

    /**
     * Flushes all where clauses from the buffer.
     */
    public function flushWheres()
    {
        $this->whereClauses = [];
    }
}
