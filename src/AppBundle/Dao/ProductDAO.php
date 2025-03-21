<?php
namespace AppBundle\Dao;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * @Service("ProductDAO", public=true)
 */
class ProductDAO extends BaseDAO {
    private static $MODEL_CLASS_NAME = "AppBundle\Entity\ProductEntity";

    /**
     * @InjectParams({"em" = @Inject("doctrine.orm.entity_manager")})
     */
    public function __construct($em) {
        parent::__construct($em, self::$MODEL_CLASS_NAME);
    }
}
?>