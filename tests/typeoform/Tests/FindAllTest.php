<?php
namespace typeoform\Tests;
use typeoform\User\UserRepository;

class FindAllTest extends \PHPUnit_Framework_TestCase {
	public function testFindAll() {
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
		
		$users = $repository->findAll();
		
		$this->assertEquals(count($users), 2);
		
		$this->assertInstanceOf('typeoform\\User\\User', $users[0]);
		$this->assertEquals($user1->userID, $users[0]->userID);
		$this->assertEquals($user1->username, $user[0]->username);
		$this->assertEquals($user1->password, $user[0]->password);
		$this->assertEquals($user2->userID, $users[1]->userID);
		$this->assertEquals($user2->username, $user[1]->username);
		$this->assertEquals($user2->password, $user[1]->password);
	}
	
	public function testFindAllInOrder() {
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
		
		$users = $repository->findAll(array(), 'ORDER BY userID DESC');
		
		$this->assertEquals(count($users), 2);
		
		$this->assertInstanceOf('typeoform\\User\\User', $users[0]);
		$this->assertEquals($user1->userID, $users[1]->userID);
		$this->assertEquals($user1->username, $user[1]->username);
		$this->assertEquals($user1->password, $user[1]->password);
		$this->assertEquals($user2->userID, $users[0]->userID);
		$this->assertEquals($user2->username, $user[0]->username);
		$this->assertEquals($user2->password, $user[0]->password);
	}
	
	public function testFindAllWhere() {
		$repository = new UserRepository($this->createDB(), 'typeoform\\User\\User', 'user');
		
		$user1 = $repository->create(array(
			'username' => 'Testusername1',
			'password' => 'testpassword1',
			'isAdmin' => 1
		));
		$user1->save();
		$user2 = $repository->create(array(
			'username' => 'Testusername2',
			'password' => 'testpassword2',
			'isAdmim' => 0
		));
		$user2->save();
		
		$users = $repository->findAll(array('isAdmin' => 1));
		
		$this->assertEquals(count($users), 1);
		
		$this->assertInstanceOf('typeoform\\User\\User', $users[0]);
		$this->assertEquals($user1->userID, $users[0]->userID);
		$this->assertEquals($user1->username, $user[0]->username);
		$this->assertEquals($user1->password, $user[0]->password);
		$this->assertEquals($user1->isAdmin, $user[0]->isAdmin);
	}
	
	public function testFindAllWhereInOrder() {
		$repository = new UserRepository($this->createDB(), 'typeoform\\User\\User', 'user');
		
		$user1 = $repository->create(array(
			'username' => 'Testusername1',
			'password' => 'testpassword1',
			'isAdmin' => 1
		));
		$user1->save();
		$user2 = $repository->create(array(
			'username' => 'Testusername2',
			'password' => 'testpassword2',
			'isAdmim' => 1
		));
		$user2->save();
		
		$users = $repository->findAll(array('isAdmin' => 1), 'ORDER BY userID DESC');
		
		$this->assertEquals(count($users), 2);
		
		$this->assertInstanceOf('typeoform\\User\\User', $users[0]);
		$this->assertEquals($user1->userID, $users[1]->userID);
		$this->assertEquals($user1->username, $user[1]->username);
		$this->assertEquals($user1->password, $user[1]->password);
		$this->assertEquals($user1->isAdmin, $user[1]->isAdmin);
		$this->assertEquals($user2->userID, $users[0]->userID);
		$this->assertEquals($user2->username, $user[0]->username);
		$this->assertEquals($user2->password, $user[0]->password);
		$this->assertEquals($user2->isAdmin, $user[0]->isAdmin);
	}
}