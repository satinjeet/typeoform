<?php
namespace typeoform\Tests;
use typeoform\User\UserRepository;

class FindTest extends Test {
	public function testFindByPrimaryKey() {
		$repository = new UserRepository($this->createDB(), 'typeoform\\User\\User', 'user');
		
		$user1 = $repository->create(array(
			'username' => 'Testusername',
			'password' => 'testpassword'
		));
		$success = $user1->save();
		
		$user2 = $repository->find(1);
		
		$this->assertInstanceOf('typeoform\\User\\User', $user2);
		$this->assertEquals($user1->userID, $user2->userID);
		$this->assertEquals($user1->username, $user2->username);
		$this->assertEquals($user1->password, $user2->password);
	}
	
	public function testFindByWhere() {
		$repository = new UserRepository($this->createDB(), 'typeoform\\User\\User', 'user');
		
		$user1 = $repository->create(array(
			'username' => 'Testusername',
			'password' => 'testpassword'
		));
		$success = $user1->save();
		
		$user2 = $repository->find(array(
			'username' => 'Testusername',
			'password' => 'testpassword'
		));
		
		$this->assertInstanceOf('typeoform\\User\\User', $user2);
		$this->assertEquals($user1->userID, $user2->userID);
		$this->assertEquals($user1->username, $user2->username);
		$this->assertEquals($user1->password, $user2->password);
	}
}