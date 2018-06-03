<?php

/**
 * FOUNTAIN
 */

Users::all()->except('id < 12')->get();

Posts::whereDefined('id')
	->with('user as author')
	->orderByTimestamp()
	->get();

$this->db->users('users');

/**
 * QUERY BUILDER
 */

// INSERT
$this->db->insert('users', [
	'name' 	=> 'Jussi',
	'email' => 'jussi@pussi.fi',
	'age' 	=> 18
]);

// SELECT
$this->db->select('users')->get();

// SELECT * FROM users

$this->db->select('users', ['name', 'email'])
	->where('id', 3);
	->orWhere('access_level', '>', 3)
	->limit(100)
	->orderBy('name', 'DESC')
	->get();

// SELECT name, email
// FROM users
// WHERE id = 3
// OR WHERE access_level > 3
// LIMIT 100
// ORDER BY name DESC

// Left join
$this->db->select('organizations')
	->where('user.id', 3)
	->leftJoin('organizations.id', 'users.organization_id')
	->first();

// SELECT organizations
// WHERE user.id = 3
// LEFT JOIN organizations ON users.organization_id = organizations.id
// LIMIT 1

// Join using a pivot table
$this->db->select('teams', ['id', 'name', 'organization_id'])
	->where('users.id', 3)
	->pivot('user_team', [
		'teams.id' => 'user_team.team_id',
		'users.id' => 'user_team.user_id'
	]);

// SELECT id, name, organization_id 
// FROM teams
// WHERE users.id = 3
// LEFT JOIN user_team ON teams.id = user_team.team_id
// LEFT JOIN users ON user_team.user_id = users.id




$this->db
	->select(['name', 'email'])
	->from('users')
	->where('id', 1)
	->whereNotNull('visible')
	->get();