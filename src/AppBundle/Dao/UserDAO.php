<?php
namespace AppBundle\Dao;

use AppBundle\Dao\BaseDAO;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * @Service("userDAO")
 */
class UserDAO extends BaseDAO {
	
	private static $MODEL_CLASS_NAME = "AppBundle\Entity\UserEntity";
	
	/**
	 * @InjectParams({"em" = @Inject("doctrine.orm.entity_manager")})
	 */
	public function __construct($em) {
		parent::__construct($em, self::$MODEL_CLASS_NAME);
	}
}
?>