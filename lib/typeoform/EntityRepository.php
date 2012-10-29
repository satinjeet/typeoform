<?php
namespace typeoform;
use Doctrine\DBAL\Connection as DB;

class EntityRepository implements EntityRepositoryInterface {
	protected $db;
	protected $entityClass;
	protected $tableName;
	
	public function __construct(DB $db, $entity_class, $table_name) {
		$this->db = $db;
		$this->setEntityClass($entity_class);
		$this->setTableName($table_name);
	}
	
	public function create(array $data = array()) {
		$entity = new $this->entityClass();
		$entity->setEntityRepository($this);
		$entity->setData($data);
		return $entity;
	}
	
	public function find($where) {
		if (!is_array($where)) $where = array($this->getPrimaryKeyField() => $where);
		
		$entities = $this->findAll($where, 'LIMIT 1');
		
		return count($entities) ? $entities[0] : null;
	}
	
	public function findAll(array $where = array(), $sql = '') {
		$criteria = '';
		foreach ($where as $field => $value)
			$criteria = (!empty($criteria) ? ' AND ' : '').$this->db->quoteIdentifier($field).' = ?';
		
		$sql = trim((!empty($criteria) ? "WHERE $criteria" : '').' '.$sql);
		
		return $this->query($sql, array_values($where));
	}
	
	public function query($sql = '', array $params = array(), array $types = array()) {
		$table = $this->db->quoteIdentifier($this->tableName);
		$sql = "SELECT $table.* FROM $table $sql";
		
		$rows = $this->db->executeQuery($sql, $params, $types)->fetchAll(\PDO::FETCH_ASSOC);
		$entities = array();
		
		foreach ($rows as $row)
			$entities[] = $this->create($row);
		
		return $entities;
	}
	
	public function count($sql = '', array $params = array(), array $types = array()) {
		$table = $this->db->quoteIdentifier($this->tableName);
		$sql = "SELECT COUNT(*) FROM $table $sql";
		
		return (int) $this->db->executeQuery($sql, $params, $types)->fetchColumn();
	}
	
	public function save(EntityInterface $entity) {
		return $entity->getPrimaryKey() ? $this->update($entity) : $this->insert($entity);
	}
	
	protected function update(EntityInterface $entity) {
		$data = $entity->getData();
		if (isset($data[$this->getPrimaryKeyField()]))
			unset($data[$this->getPrimaryKeyField()]);
		
		return $this->db->update($this->tableName, $this->quoteIdentifiers($data), array($this->getPrimaryKeyField() => $entity->getPrimaryKey()));
	}
	
	protected function insert(EntityInterface $entity) {
		$data = $entity->getData();
		if (isset($data[$this->getPrimaryKeyField()]))
			unset($data[$this->getPrimaryKeyField()]);
		
		$result = $this->db->insert($this->tableName, $this->quoteIdentifiers($data));
		$entity->{$this->getPrimaryKeyField()} = $this->db->lastInsertId();
		return $result;
	}
	
	public function delete(EntityInterface $entity) {
		if ($result = $this->db->delete($this->tableName, array($this->getPrimaryKeyField() => $entity->getPrimaryKey()))) {
			$entity->{$this->getPrimaryKeyField()} = 0;
		}
		
		return $result;
	}
	
	protected function getPrimaryKeyField() {
		$class = $this->entityClass;
		return $class::getPrimaryKeyField();
	}
	
	protected function quoteIdentifiers(array $data) {
		$quoted = array();
		foreach ($data as $field => $value)
			$quoted[$this->db->quoteIdentifier($field)] = $value;
		return $quoted;
	}
	
	public function setDB(DB $db) {
		$this->db = $db;
	}
	
	public function getDB() {
		return $this->db;
	}
	
	public function setEntityClass($class) {
		if (!is_subclass_of($class, 'typeoform\\EntityInterface'))
			throw new \InvalidArgumentException(sprintf('Entity class "%s" must implement "typeoform\\EntityInterface"', $class));
		
		$this->entityClass = $class;
	}
	
	public function getEntityClass() {
		return $this->entityClass;
	}
	
	public function setTableName($table) {
		$this->tableName = (string) $table;
	}
	
	public function getTableName() {
		return $this->tableName;
	}
}