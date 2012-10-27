<?php
namespace typeoform\Tests;
use typeoform\User\UserRepository;

class InsertTest extends \PHPUnit_Framework_TestCase {
	public function testInsert() {
		$repository = new UserRepository($this->createDB(), 'typeoform\\User\\User', 'user');
		
		$user = $repository->create();
		$user->username = 'Testusername';
		$user->password = 'testpassword';
		$success = $user->save();
		
		$this->assertTrue($success);
		$this->assertInstanceOf('typeoform\\User\\User', $user);
		$this->assertEquals($user->username, 'Testusername');
		$this->assertEquals($user->password, 'testpassword');
		$this->assertEquals($user->userID, 1);
		$this->assertEquals($user->getPrimaryKey(), $user->userID);
	}
	
	public function testAlternativeInsert() {
		$repository = new UserRepository($this->createDB(), 'typeoform\\User\\User', 'user');
		
		$user = $repository->create(array(
			'username' => 'Testusername',
			'password' => 'testpassword'
		));
		$success = $user->save();
		
		$this->assertTrue($success);
		$this->assertInstanceOf('typeoform\\User\\User', $user);
		$this->assertEquals($user->username, 'Testusername');
		$this->assertEquals($user->password, 'testpassword');
		$this->assertEquals($user->userID, 1);
		$this->assertEquals($user->getPrimaryKey(), $user->userID);
	}
}