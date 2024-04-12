<?php

namespace App\Models;

use App\Framework\Libs\Model;

class Test extends Model
{
    /**
     * 
     */
    public function test()
    {
        // return $this->db
        // 	->select(['id', 'name'])
        // 	->from('users')
        // 	->where('id', 1)
        // 	->where(['access_level', '>=', 2])
        // 	->orWhere('is_admin', 1)
        // 	->orderBy('id')
        // 	->limit(1)
        // 	->get();

        return $this->db
            ->select(['id', 'name'])
            ->from('users')
            ->where('id', 1)
            ->get();
    }
}
