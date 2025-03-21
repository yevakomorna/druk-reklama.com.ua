<?php
namespace AppBundle\Dao;

use AppBundle\Dao\BaseDAO;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

/**
 * @Service("BlockDAO", public=true)
 */
class BlockDAO extends BaseDAO {
    private static $MODEL_CLASS_NAME = "AppBundle\Entity\BlockEntity";
    private static $TABLE_ALIAS = 'block';
    /**
     * @InjectParams({"em" = @Inject("doctrine.orm.entity_manager")})
     */
    public function __construct($em) {
        parent::__construct($em, self::$MODEL_CLASS_NAME);
    }

    public function getQueryForPaginator($criteria = []) {

        $sqlWhere = " WHERE 1 = 1 ";
        $sqlParameters = [];
        if (count($criteria) > 0) {
            $counter = 0;
            foreach ($criteria as $fildName => $fildVal) {
                $sqlWhere .= " and " . self::$TABLE_ALIAS . '.' . $fildName . '= :' . $fildName . '_' . $counter;
                $sqlParameters[$fildName . '_' . $counter] = $fildVal;
                $counter++;
            }

        }
        
		$QueryStr = "SELECT " . self::$TABLE_ALIAS . " FROM " . self::$MODEL_CLASS_NAME . " AS " . self::$TABLE_ALIAS . " " . $sqlWhere . " ";
		
        $query = $this->getEntityManager()->createQuery($QueryStr);
        $query->setParameters($sqlParameters);

        return $query;

    }

}
?>