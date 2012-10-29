typeoform - A lightweight ORM. Better than handling with raw sql queries.
==============================================================================

[![Build Status](https://secure.travis-ci.org/schokocappucino/typeoform.png)](http://travis-ci.org/schokocappucino/typeoform)

typeoform querys your database and maps tables to classes. It's perfect
if your project is small and you just wan't to fill objects with records from
the database. It relies on Doctrine DBAL and consists of 2 classes and 2 interfaces.

Creating the entity class and repository class (in combination with Silex/Pimple)
---------------------------------------------------------------------------------
The table looks like this:

```sql
CREATE TABLE user (
	userID int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(255) NOT NULL DEFAULT '',
	password VARCHAR(255) NOT NULL DEFAULT '',
	isAdmin tinyint(1) NOT NULL DEFAULT 0
);
```

Your model extends typeoform\Entity. You can provide default values for fields by adding properties.
The repository gives you access to all records. If you don't wan't to add additional methods to your
repository you can instantiate typeoform\EntityRepository directly, too. 

```php
class User extends \typeoform\Entity {
	protected static $_primaryKey = 'userID';
	public $userID = 0;
	public $username = ''
	public $password = '';
	public $isAdmin = 0;
	
	public function __toString() {
		return $this->username;
	}
}

class UserRepository extends \typeoform\EntityRepository {
	
}
```

Now you can register/instantiate your repository (using Silex/Pimple):

```php
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
	'db.options' => array(
		// ...
	)
));

$app['user.class'] = 'User';
$app['user.repository'] = $app->share(function ($app) {
	// Doctrine\DBAL\Connection, entity class, table name
	return new UserRepository($app['db'], $app['user.class'], 'user');
});
```

Creating records
----------------

```php
// create user
$user = $app['user.repository']->create();
$user->username = 'Chris';
$user->password = password_hash('foo', PASSWORD_DEFAULT);
$user->isAdmin = 1;
$user->save();
echo 'userID: ', $user->userID; // primary key is set

// create user alternative
$app['user.repository']->create(array(
	'user' => 'Max',
	'password' => password_hash('bar', PASSWORD_DEFAULT)
))->save();

// just another user for the examples
$app['user.repository']->create(array(
	'user' => 'Lilly',
	'password' => password_hash('baz', PASSWORD_DEFAULT)
))->save();
```

Fetching records
----------------

```php
// get user by primary key
$user = $app['user.repository']->find(1);
echo $user->userID, ': ', $user->username; // 1: Chris

// get user where
$user = $app['user.repository']->find(array('username' => 'Max'));
echo $user->userID, ': ', $user->username; // 2: Max

// get all users
$users = $app['user.repository']->findAll();
echo implode(', ', $users); // Chris, Max, Lilly

// get all users where
$user = $app['user.repository']->findAll(array('isAdmin' => 0));
echo implode(', ', $users); // Max, Lilly

// get all users where, in order
$user = $app['user.repository']->findAll(array('isAdmin' => 0), 'ORDER BY username ASC');
echo implode(', ', $users); // Lilly, Max
```

Queries
-------

```php
$users = $app['user.repository']->query('WHERE LENGTH(username) > 4');
echo implode(', ', $users); // Chris, Lilly

$username = 'Chris';
$users = $app['user.repository']->query('WHERE username = ?', array($username));
echo implode(', ', $users); // Chris

$username = 'Chris';
$users = $app['user.repository']->query('WHERE username = ?', array($username), \PDO::PARAM_STR));
echo implode(', ', $users); // Chris
```

Counting
-------

```php
$count = $app['user.repository']->count('WHERE LENGTH(username) > 4');
echo $count; // 2

$username = 'Chris';
$count = $app['user.repository']->count('WHERE username = ?', array($username));
echo $count; // 1

$username = 'Chris';
$count = $app['user.repository']->count('WHERE username = ?', array($username), \PDO::PARAM_STR));
echo $count; // 1
```

Updating records
----------------

```php
$user = $app['user.repository']->find(array('username' => 'Max'));
$user->isAdmin = 1
$user->save();
```

Deleting
--------

```php
$user = $app['user.repository']->find(array('username' => 'Max'));
$user->delete();
echo $user->userID; // 0, the primary key gets deleted after removing the entity
```

Extensibility
-------------

typeoform is very flexible. For example if a plugin adds columns to a table,
they are accessible in the entity, too, without editing the entity class.

Plugins can also change the entity class of repositories:

```php
class AwesomeUser extends User {
	// ...
}

$app['user.repository']->setEntityClass('AwesomeUser');
```