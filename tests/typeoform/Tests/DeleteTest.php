<?php
namespace typeoform\Tests;
use typeoform\User\UserRepository;

class DeleteTest extends \PHPUnit_Framework_TestCase {
	public function testDelete() {
		$repository = new UserRepository($this->createDB(), 'typeoform\\User\\User', 'user');
		
		$user = $repository->create(array(
			'username' => 'Testusername',
			'password' => 'testpassword'
		));
		$user->save();
		
		$count_before = $repository->count();
		$success = $user->delete();
		$count_after = $repository->count();
		
		$this->assertTrue($success);
		$this->assertEquals($count_before, 1);
		$this->assertEquals($count_after, 0);
		$this->assertEquals($user->userID, 0);
	}
}