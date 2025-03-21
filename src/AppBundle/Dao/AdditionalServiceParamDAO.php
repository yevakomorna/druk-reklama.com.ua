<?php
namespace AppBundle\Dao;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * @Service("AdditionalServiceParamDAO", public=true)
 */
class AdditionalServiceParamDAO extends BaseTreeDAO {
    private static $MODEL_CLASS_NAME = "AppBundle\Entity\AdditionalServiceParamEntity";

    /**
     * @InjectParams({"em" = @Inject("doctrine.orm.entity_manager")})
     */
    public function __construct($em) {
        parent::__construct($em, self::$MODEL_CLASS_NAME);
    }
}
?>