<?php
namespace typeoform\Tests;
use typeoform\User\UserRepository;

class QueryTest extends Test {
	public function testQueryAll() {
		$repository = new UserRepository($this->createDB(), 'typeoform\\User\\User', 'user');
		
		$user1 = $repository->create(array(
			'username' => 'Testusername1',
			'password' => 'testpassword1'
		));
		$user1->save();
		$user2 = $repository->create(array(
			'username' => 'Testusername2',
			'password' => 'testpassword2'
		));
		$user2->save();
		
		$users = $repository->query();
		
		$this->assertEquals(count($users), 2);
		
		$this->assertInstanceOf('typeoform\\User\\User', $users[0]);
		$this->assertEquals($user1->userID, $users[0]->userID);
		$this->assertEquals($user1->username, $users[0]->username);
		$this->assertEquals($user1->password, $users[0]->password);
		$this->assertEquals($user2->userID, $users[1]->userID);
		$this->assertEquals($user2->username, $users[1]->username);
		$this->assertEquals($user2->password, $users[1]->password);
	}
	
	public function testQuery() {
		$repository = new UserRepository($this->createDB(), 'typeoform\\User\\User', 'user');
		
		$user1 = $repository->create(array(
			'username' => 'Testusername1',
			'password' => 'testpassword1'
		));
		$user1->save();
		$user2 = $repository->create(array(
			'username' => 'Testusername2',
			'password' => 'testpassword2'
		));
		$user2->save();
		
		$users = $repository->query('ORDER BY userID DESC');
		
		$this->assertEquals(count($users), 2);
		
		$this->assertInstanceOf('typeoform\\User\\User', $users[0]);
		$this->assertEquals($user1->userID, $users[1]->userID);
		$this->assertEquals($user1->username, $users[1]->username);
		$this->assertEquals($user1->password, $users[1]->password);
		$this->assertEquals($user2->userID, $users[0]->userID);
		$this->assertEquals($user2->username, $users[0]->username);
		$this->assertEquals($user2->password, $users[0]->password);
	}
	
	public function testQueryWithParams() {
		$repository = new UserRepository($this->createDB(), 'typeoform\\User\\User', 'user');
		
		$user1 = $repository->create(array(
			'username' => 'Testusername1',
			'password' => 'testpassword1'
		));
		$user1->save();
		$user2 = $repository->create(array(
			'username' => 'Testusername2',
			'password' => 'testpassword2'
		));
		$user2->save();
		
		$users = $repository->query('WHERE username = ?', array('Testusername1'));
		
		$this->assertEquals(count($users), 1);
		
		$this->assertInstanceOf('typeoform\\User\\User', $users[0]);
		$this->assertEquals($user1->userID, $users[0]->userID);
		$this->assertEquals($user1->username, $users[0]->username);
		$this->assertEquals($user1->password, $users[0]->password);
	}
	
	public function testQueryWithParamsAndTypes() {
		$repository = new UserRepository($this->createDB(), 'typeoform\\User\\User', 'user');
		
		$user1 = $repository->create(array(
			'username' => 'Testusername1',
			'password' => 'testpassword1'
		));
		$user1->save();
		$user2 = $repository->create(array(
			'username' => 'Testusername2',
			'password' => 'testpassword2'
		));
		$user2->save();
		
		$users = $repository->query('WHERE username = ?', array('Testusername1'), array(\PDO::PARAM_STR));
		
		$this->assertEquals(count($users), 1);
		
		$this->assertInstanceOf('typeoform\\User\\User', $users[0]);
		$this->assertEquals($user1->userID, $users[0]->userID);
		$this->assertEquals($user1->username, $users[0]->username);
		$this->assertEquals($user1->password, $users[0]->password);
	}
}