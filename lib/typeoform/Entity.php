<?php
namespace typeoform;

abstract class Entity implements EntityInterface {
	protected static $_primaryKeyField;
	protected $_repository;
	
	public static function getPrimaryKeyField() {
		if (empty(static::$_primaryKeyField)) 
			throw new \Exception('static::$_primaryKeyField musn\'t be empty');
		
		return static::$_primaryKeyField;
	}
	
	public function getPrimaryKey() {
		return isset($this->{static::getPrimaryKeyField()}) ? $this->{static::getPrimaryKeyField()} : 0;
	}
	
	public function setData(array $data) {
		foreach ($data as $field => $value)
			$this->$field = $value;
	}
	
	public function getData() {
		$data = get_object_vars($this);
		
		foreach ($data as $key => $value) {
			if (strpos($key, '_') === 0)
				unset($data[$key]);
		}
		
		return $data;
	}
	
	public function save() {
		if ($this->_repository === null) throw new \Exception('Entity has no repository');
		return $this->_repository->save($this);
	}
	
	public function update(array $data) {
		$this->setData($data);
		return $this->save();
	}
	
	public function delete() {
		if ($this->_repository === null) throw new \Exception('Entity has no repository');
		return $this->_repository->delete($this);
	}
	
	public function setEntityRepository(EntityRepositoryInterface $repository) {
		$this->_repository = $repository;
	}
	
	public function getEntityRepository() {
		return $this->_repository;
	}
}