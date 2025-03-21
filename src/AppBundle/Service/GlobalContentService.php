<?php
namespace AppBundle\Service;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\Service;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Service("GlobalContentService")
 */
class GlobalContentService {

    /**
     * @Inject("request_stack")
     */
    public $requestStack;

    /**
     * @Inject
     */
    public $router;

    /**
     * @Inject
     */
    public $translator;

    /**
     * @Inject("SectionDAO")
     */
    public $SectionDAO;

    /**
     * @Inject("AdditionalServiceDAO")
     */
    public $AdditionalServiceDAO;

    /**
     * @Inject("OrderDAO")
     */
    public $OrderDAO;
    
    public function getMainMenu() {
        $request = $this->requestStack->getCurrentRequest();
        $category = $this->SectionDAO->findOneBy(['name' => 'main_menu']);
        $router = $this->router;
        $translator = $this->translator;
        $htmlTree = $this->SectionDAO->childrenHierarchy($category, false, ['decorate' => true, 'representationField' => 'name', 'html' => true, 'rootOpen' => function ($children)use ($router, $translator) {
            if ($children[0]['lvl'] == 2) {
                return '<ul>
						<li class="butterfly"><a href="' . $router->generate("home") . '">
						  <div style="position:relative; width:313px"> <img class="image" src="/web/img/butterfly.png" >
						    <div class="butterfly_text">' . $translator->trans("common.sitename_1") . '<br>
						      ' . $translator->trans("common.sitename_2") . '</div>
						  </div>
						  </a> </li>'; }
        else {
            return '<ul>'; }

        }
        , 'rootClose' => function ($children) {
            return '</ul>'; }
        , 'childOpen' => function ($node) {
            if ($node['status'] == 1) {
                return '<li>'; }
        }
        , 'childClose' => function ($node) {
            if ($node['status'] == 1) {
                return '</li>'; }
        }
        , 'nodeDecorator' => function ($node)use (&$router, $request) {
            if ($node['status'] == 1) {
                $localle = ucfirst($request->getLocale()); return '<a href="' . $router->generate("category", array("category_name" => $node['name'])) . '" class="' . $node['cssClass'] . '" >' . $node['title' . $localle] . '</a>'; }
    
        }
    
        ], false);

        return $htmlTree;
    }

    public function getServises($parentId, $orderID = false) {
    
        $request = $this->requestStack->getCurrentRequest();
        $treeElements = $this->AdditionalServiceDAO->findOneBy(['id' => $parentId]);
        
        $selectedSectionInOrder = $orderID ? $this->OrderDAO->findOneById($orderID) : null;
        $selectedSectionInOrder = ($selectedSectionInOrder) ?  $selectedSectionInOrder->getOrderServicesID(true) : null;
        $selectedSectionInOrder = count($selectedSectionInOrder) ? $selectedSectionInOrder : null;
        
        if ($orderID && !$selectedSectionInOrder) {
            return '';
        }
        
        $translator = $this->translator;
        $localle = ucfirst($request->getLocale());
        $getChildren =  $this->AdditionalServiceDAO->getChildren($treeElements);
        
        $counter = 1;
        foreach ($getChildren as $ind => $getChild) {
            if ($selectedSectionInOrder) {
                if (!in_array($getChild->getID(), $selectedSectionInOrder)) {
                    unset($getChildren[$ind]);
                    continue;
                }
            }
            $getChild = $getChild->getArrayForTree($localle);
            if ($getChild['lvl'] == 2) {
                $getChild['index'] = $counter;
                $counter++; 
            }
            $getChildren[$ind] = $getChild;   
        }
        
        
        $htmlTree = $this->AdditionalServiceDAO->buildTree($getChildren,  [
                    'decorate' => true, 
                    'representationField' => 'name', 
                    'html' => true,
                    'rootOpen' => function ($children) {
                        if ($children[0]['lvl'] == 3) {
                            return '<div class="form_checkbox_level_1">'; 
                        }
                    }, 
                    'rootClose' => function ($children) {
                        if ($children[0]['lvl'] == 3) {
                            return '</div></div>';
                        }
                    }, 
                    'childOpen' => function ($node)  {
                        $return = '';
                        if ($node['lvl'] == 2) {
                            $return .= '<div class="form_checkbox_group ';
                            if ( $node['index']==1 ) {
                                $return .= ' first_element';
                            }
                            $return .= '">'; 
                        }
                        return $return;
                    },
                    'childClose' => function ($node) {
                        $return = '';
                        if ($node['lvl'] == 2) {
                            if (count($node['__children']) == 0) {
                                $return .= '</div>'; 
                            } 
                        }
                        return $return;
                    }, 
                    'nodeDecorator' => function ($node)  {
                        if ($node['status'] != 1) {
                            return ''; 
                        }
                        if ($node['lvl'] == 2) {
                            return '
                            <label class="main_checkbox_group">
                            <div class="checkbox_numeration">' .  $node['index'] . '. </div><div class="checkbox_lable_caption">' . $node['title' ] . '</div>
                            <input name="additional_service[' . $node['id'] . ']" type="checkbox"  class="checkbox_colored main_checkbox" />
                            </label>';
                        } else {
                            return '
                            <label class="checkbox_group_item"  > ' . $node['title' ] . '
                            <input name="additional_service_item[' . $node['id'] . ']" type="checkbox"  class="checkbox_colored child_checkbox" />
                            </label>'; 
                        }
                    }
            ], false);
    
        return $htmlTree;
        
    }

}
?>