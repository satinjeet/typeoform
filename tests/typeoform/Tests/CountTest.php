<?php
namespace typeoform\Tests;
use typeoform\User\UserRepository;

class CountTest extends Test {
	public function testQueryAll() {
		$repository = new UserRepository($this->createDB(), 'typeoform\\User\\User', 'user');
		
		$repository->create(array(
			'username' => 'Testusername1',
			'password' => 'testpassword1'
		))->save();
		$repository->create(array(
			'username' => 'Testusername2',
			'password' => 'testpassword2'
		))->save();
		
		$count = $repository->count();
		
		$this->assertEquals($count, 2);
	}
	
	public function testQuery() {
		$repository = new UserRepository($this->createDB(), 'typeoform\\User\\User', 'user');
		
		$repository->create(array(
			'username' => 'Testusername1',
			'password' => 'testpassword1'
		))->save();
		$repository->create(array(
			'username' => 'Testusername2',
			'password' => 'testpassword2'
		))->save();
		
		$count = $repository->count('ORDER BY userID DESC');
		
		$this->assertEquals($count, 2);
	}
	
	public function testQueryWithParams() {
		$repository = new UserRepository($this->createDB(), 'typeoform\\User\\User', 'user');
		
		$repository->create(array(
			'username' => 'Testusername1',
			'password' => 'testpassword1'
		))->save();
		$repository->create(array(
			'username' => 'Testusername2',
			'password' => 'testpassword2'
		))->save();
		
		$count = $repository->count('WHERE username = ?', array('Testusername1'));
		
		$this->assertEquals($count, 1);
	}
	
	public function testQueryWithParamsAndTypes() {
		$repository = new UserRepository($this->createDB(), 'typeoform\\User\\User', 'user');
		
		$repository->create(array(
			'username' => 'Testusername1',
			'password' => 'testpassword1'
		))->save();
		$repository->create(array(
			'username' => 'Testusername2',
			'password' => 'testpassword2'
		))->save();
		
		$count = $repository->count('WHERE username = ?', array('Testusername1'), array(\PDO::PARAM_STR));
		
		$this->assertEquals($count, 1);
	}
}