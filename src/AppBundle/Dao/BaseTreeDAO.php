<?php
namespace AppBundle\Dao;


use Gedmo\Tree\Entity\Repository\NestedTreeRepository; 

class BaseTreeDAO extends NestedTreeRepository {
	
	private $em;

	public function __construct($em, $modelClassName) {
		$this->em = $em;
		parent::__construct($em, $em->getClassMetadata($modelClassName));
	}

	public function save($object) {
		$this->em->persist($object);
		$this->em->flush();
	}

	public function saveAll(array $objects) {
		foreach ($objects as $object){
			$this->save($object);
		}
	}

	public function delete($object) {
		$this->em->remove($object);
		$this->em->flush();				
	}

	public function deleteAll(array $objects) {
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
        
    public function setNextTo($node, $sibling) {
        $this->persistAsNextSiblingOf($node, $sibling);
        $this->em->flush();
    }
    
    public function setPrevTo($node, $sibling) {
        $this->persistAsPrevSiblingOf($node, $sibling);
        $this->em->flush();
    }
    
}
?>