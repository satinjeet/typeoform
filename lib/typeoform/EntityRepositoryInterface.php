<?php
namespace typeoform;

interface EntityRepositoryInterface {
	public function save(EntityInterface $entity);
	public function delete(EntityInterface $entity);
}