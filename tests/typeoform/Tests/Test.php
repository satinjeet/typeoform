<?php
namespace typeoform\Tests;

abstract class Test extends \PHPUnit_Framework_TestCase {
	public function createDB() {
		$db = DriverManager::getConnection(array(
			'driver' => 'pdo_sqlite',
			'memory' => true
		), new Configuration());
		
		$schema = $db->getSchemaManager()->createSchema();
		$table = $schema->createTable('user');
		$table->addColumn('userID', 'integer', array('unsigned' => true));
		$table->addColumn('username', 'string', array('length' => 255));
		$table->addColumn('password', 'string', array('length' => 255));
		$table->addColumn('isAdmin', 'integer');
		$table->setPrimaryKey(array('userID'));
		$queries = $schema->toSql($db->getDatabasePlatform());
		
		foreach ($queries as $query)
			$db->executeQuery($query);
		
		return $db;
	}
}