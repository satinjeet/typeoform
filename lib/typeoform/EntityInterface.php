<?php
namespace typeoform;

interface EntityInterface {
	public static function getPrimaryKeyField();
	public function getPrimaryKey();
	public function setData(array $data);
	public function getData();
	public function setEntityRepository(EntityRepositoryInterface $repository);
}