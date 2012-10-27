<?php
namespace typeoform\User;
use typeoform\Entity;

class User extends Entitiy {
	protected static $_primaryKey = 'userID';
	public $userID = 0;
	public $username = '';
	public $password = '';
	public $isAdmin = 0;
}