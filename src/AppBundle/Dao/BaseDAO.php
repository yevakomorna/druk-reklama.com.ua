<?php
namespace AppBundle\Dao;

use Doctrine\ORM\EntityRepository;

class BaseDAO extends EntityRepository {
	
	private $em;

	public function __construct($em, $modelClassName) {
		$this->em = $em;
		parent::__construct($em, $em->getClassMetadata($modelClassName));
	}

	public function save($object) {
		$this->em->persist($object);
		$this->em->flush();
	}

	public function saveAll($objects) {
		foreach ($objects as $object){
			$this->save($object);
		}
	}

	public function delete($object) {
		$this->em->remove($object);
		$this->em->flush();				
	}

	public function deleteAll($objects) {
		foreach ($objects as $object){
			$this->delete($object);
		}
	}

	public function getAllMapped() {
		$result = [];
		foreach ($this->findAll() as $object) {
			$result[$object->getID()] = $object; 
		}

		return $result;
	}
}
?>