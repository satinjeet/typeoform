<?php
namespace typeoform\Tests;
use typeoform\User\UserRepository;

class UpdateTest extends Test {
	public function testUpdate() {
		$repository = new UserRepository($this->createDB(), 'typeoform\\User\\User', 'user');
		
		$user = $repository->create(array(
			'username' => 'Testusername',
			'password' => 'testpassword'
		));
		$user->save();
		
		$user->isAdmin = 1;
		$success = $user->save();
		
		$user2 = $repository->find(1);
		
		$this->assertTrue($success);
		$this->assertEquals($user2->isAdmin, 1);
	}
}