<?php
namespace typeoform\User;
use typeoform\Entity;

class User extends Entity {
	protected static $_primaryKeyField = 'userID';
	public $userID = 0;
	public $username = '';
	public $password = '';
	public $isAdmin = 0;
}