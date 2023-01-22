<?php
namespace App\Framework\Libs\Database;

use App\Framework\Exceptions\DatabaseException;

use PDO;

/**
 * Connect to database of given type.
 * Currently supports following databases:
 * - MySQL
 *
 * @todo Handle, not only MySQL connections but others also.
 */
class DatabaseConnection extends PDO
{
    /**
     * Description
     *
     * @param array $config     The database config.
     * @return mixed            PDO or DatabaseException on fail.
     */
    public function __construct(array $config)
    {
        try {
            parent::__construct(
                $config['connection'] .';dbname='. $config['name'],
                $config['user'],
                $config['password'],
                $config['options']
            );

            $this->exec('SET NAMES utf8');
            $this->setAttribute(self::ATTR_ERRMODE, self::ERRMODE_WARNING);
        } catch (\PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }
}
