<?php

namespace App\Framework\Libs\Database;

/**
 * Interface for database grammars.
 */
interface Grammar
{
	public function buildQuery(array $selects, string $from, array $whereClauses, array $orderBy, array $limit) : string;
}
