<?php

namespace App\Models;

use App\Framework\Libs\Model;
use App\Framework\Logs\ActivityLog;
use App\Framework\Logs\ErrorLog;

class Secret extends Model
{
	public function getLog(string $log)
	{
		if ($log == 'activity') {
			return ActivityLog::read();
		} else if ($log == 'error') {
			return ErrorLog::read();
		}
	}


	// 
	// Todo List
	//

	public function addTodo($data)
	{
		$this->db->into('todos')->insert([
			'name' 			=> $data['name'],
			'details' 		=> $data['details'],
			'created_at' 	=> date(config('app')['date_format'])
		])->execute();

		return $this->db->lastInsertId();
	}

	public function listTodos() : \stdClass
	{
		$query = $this->db->select(['id', 'name', 'done_at', 'created_at'])->from('todos')
			->orderBy('created_at', 'desc');

		$result = new \stdClass();
		$result->undone = $query->where(['done_at', 'IS NULL', ''])->get('stdClass', false);
		$query->flushWheres();
		$result->done = $query->where(['done_at', 'IS NOT NULL', ''])->get();

		return $result;
	}

	public function update(int $todoId, $data)
	{
		return $this->db->table('todos')
			->update($data)
			->where('id', $todoId)
			->execute();
	}

	public function isDone(int $todoId)
	{
		$todo = $this->db->select(['done_at'])
			->from('todos')
			->where('todos.id', $todoId)
			->first();

		if ($todo) {
			return !empty($todo->done_at);
		} else {
			return false;
		}
	}

	public function delete(int $todoId)
	{
		return $this->db->delete()->from('todos')->where('id', $todoId)->execute();
	}
}
