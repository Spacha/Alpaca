<?php

namespace App\Framework\Libs\Database;

/**
 * Interface for database grammars.
 */
interface Grammar
{
    public function buildQuery(array $operation, string $table, array $joins, array $whereClauses, array $orderBy, array $limit) : string;
}
