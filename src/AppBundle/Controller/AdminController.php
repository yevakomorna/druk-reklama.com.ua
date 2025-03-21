<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation\Inject;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\BlockImageEntity;
use AppBundle\Entity\BlockEntity;

use AppBundle\Form\Type\BlockType;

use AppBundle\Form\Type\SectionDescType;
use AppBundle\Form\Type\SectionType;
use AppBundle\Form\Type\SectionMoveLevel;
use AppBundle\Form\Type\SectionMoveInParent;
use AppBundle\Entity\SectionEntity;

use AppBundle\Form\Type\AdditionalServiceType;
use AppBundle\Form\Type\AdditionalServiceMoveLevel;
use AppBundle\Form\Type\AdditionalServiceMoveInParent;
use AppBundle\Entity\AdditionalServiceEntity;

/**
 * @Security("has_role('ROLE_ADMIN')")
 */
class AdminController extends Controller {

    /**
     * @Inject
     */
    public $translator;

    /**
     * @Inject("kernel")
     */
    public $kernel;

    /**
     * @Inject("liip_imagine.cache.manager")
     */
    public $thumbnailsCacheManager;

    /**
     * @Inject("BlockDAO")
     */
    public $BlockDAO;

    /**
     * @Inject("SectionDAO")
     */
    public $SectionDAO;

    /**
     * @Inject("AdditionalServiceDAO")
     */
    public $AdditionalServiceDAO;

    /**
     * @Template("default/admin/admin.html")
     * @Route("/admin", name="admin")
     */
    public function adminAction(Request $request) {

        return [];
    }

    /**
     * @Template("default/admin/tree.html")
     * @Route("/admin/tree", name="admin_tree")
     */
    public function treeAction(Request $request) {
        $sections = $this->SectionDAO->findBy([], ["lft" => "ASC"]);
        $form_name = $form_name_2 = null;
        $locale = $request->getLocale();
        if ($sectionId = $request->query->get("section_id")) {
            $mode = $request->query->get("mode");
            $section = $this->SectionDAO->findOneBy(["id" => $sectionId]);
            $sectionParentId = $section->getParent()->getID();
            $additionalServices = $this->AdditionalServiceDAO->findBy(["lvl"=>1], ["lft" => "ASC"]);
            if ($mode == "dellete") {
                $this->SectionDAO->delete($section);

                return $this->redirectToRoute('admin_tree');
            } elseif ($mode == "edit") {
                $form_name = "Редагування розділу";
                $form = $this->createForm(SectionType::class, $section, ['additionalServices'=> $additionalServices, 'locale'=> $locale ]);
            } elseif ($mode == "add") {
                $form_name = "Додача розділу";
                if ($parrent_name = $section->getTitleI18n($locale)) {
                    $form_name .= ' в розділ "' . $parrent_name . '"';
                }
                $sectionNew = new SectionEntity();
                $sectionNew->setParent($section);
                $form = $this->createForm(SectionType::class, $sectionNew, ['additionalServices'=> $additionalServices, 'locale'=> $locale ]);
            } elseif ($mode == "move") {
                $form_name = 'Переміщення розділу "' . $section->getTitleI18n($locale) . '" (з усіма підрозділами) до іншого батьківського розділу';
                $form = $this->createForm(SectionMoveLevel::class, $section, ['sections' => $sections, 'locale' => $locale, 'sectionParentId' => $sectionParentId]);

                $form_name_2 = 'Переміщення розділу "' . $section->getTitleI18n($locale) . '" в межах батьківського розділу';

                $sections_sibling = $this->SectionDAO->children($section->getParent(), true, "lft", "ASC");
                $form_2 = $this->createForm(SectionMoveInParent::class, null, ['sections' => $sections_sibling, 'locale' => $locale, 'sectionId' => $sectionId]);
            }

            if (isset($form)) {
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {

                    $section = $form->getData();
                    $this->SectionDAO->save($section);

                    return $this->redirectToRoute('admin_tree');
                }
            }

            if (isset($form_2)) {
                $form_2->handleRequest($request);

                if ($form_2->isSubmitted() && $form_2->isValid()) {

                    $sibling = $form_2->getData();
                    $method = $sibling['direction'];

                    if ($method == 'Prev') {
                        $this->SectionDAO->setPrevTo($section, $sibling['sibling']);
                    } elseif ($method == 'Next') {
                        $this->SectionDAO->setNextTo($section, $sibling['sibling']);
                    }

                    return $this->redirectToRoute('admin_tree');
                }
            }

        }

        return ["sections" => $sections, "sectionId" => $sectionId, "form" => isset($form) ? $form->createView() : null, 'form_name' => $form_name, "form_2" => isset($form_2) ? $form_2->createView() : null, 'form_name_2' => $form_name_2];
    }

    /**
     * @Template("default/admin/edit_category.html")
     * @Route("/edit_category/", defaults={"category_name" = "home"})
     * @Route("/edit_category/{category_name}", name="edit_category")
     */
    public function categoryPageEdit(Request $request, $category_name) {

        $category = $this->SectionDAO->findOneBy(['name' => $category_name]);

        $form = $this->createForm(SectionDescType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $category = $form->getData();
            $this->SectionDAO->save($category);

            return $this->redirectToRoute('edit_category', ['category_name' => $category_name]);
        }

        $data = ['category' => $category];

        return ["form" => $form->createView(), 'data' => $data];
    }

    /**
     * @Template("default/admin/list_category_block.html")
     * @Route("/list_category_block/", defaults={"category_name" = "home"})
     * @Route("/list_category_block/{category_name}", name="list_category_block")
     */
    public function categoryBlockList(Request $request, $category_name) {

        $category = $this->SectionDAO->findOneBy(['name' => $category_name]);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($this->BlockDAO->getQueryForPaginator(["section" => $category->getID()]), $request->query->get('page', 1), 20);

        return ["pagination" => $pagination, 'category' => $category];
    }

    /**
     * @Template("default/admin/edit_category_block.html")
     * @Route("/edit_category_block/{block_id}", name="edit_category_block")
     */
    public function categoryBlockEdit(Request $request, $block_id) {
        $rootPath = dirname($this->kernel->getRootDir());
        $block = $this->BlockDAO->find($block_id);
        $block->convertImagesNameToFileObject($rootPath);

        $form = $this->createForm(BlockType::class, $block);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $block = $form->getData();
            $blockImages = $block->getImages();

            foreach ($blockImages as $key => $image) {
                $uploadedImage = $image->getFileNameAssert();
                /**
                 * видаляєм порожні поля малюнків (заглушки в формі редагування)
                 */
                if (!$uploadedImage && !$image->getFileName()) {
                    $blockImages->remove($key);
                    continue;
                }
                if ($uploadedImage && $image->getFileName()) {
                    // видалення з диску існуючих малюнків
                    $image->remuveFromStorage($rootPath);
                    $this->thumbnailsCacheManager->remove($image->getFull());
                }

                if ($uploadedImage) {
                    $fileName = md5(uniqid()) . '.' . $uploadedImage->guessExtension();
                    $uploadedImage->move($rootPath . BlockImageEntity::$PHOTO_DIR, $fileName);

                    $image->setFileName($fileName);
                }

            }

            $this->BlockDAO->save($block);

            return $this->redirectToRoute('edit_category_block', ['block_id' => $block_id]);

        }

        return ["form" => $form->createView(), 'block' => $block];
    }

    /**
     * @Template("default/admin/kill_category_block.html")
     * @Route("/kill_category_block/{block_id}", name="kill_category_block")
     */
    public function categoryBlockKill(Request $request, $block_id) {
        if ($block = $this->BlockDAO->find($block_id)) {
            $rootPath = dirname($this->kernel->getRootDir());
            foreach ($block->getImages() as $key => $image) {
                if ($image->getFileName()) {
                    $image->remuveFromStorage($rootPath);
                    $this->thumbnailsCacheManager->remove($image->getFull());
                }
            }
            $category_name = $block->getSection()->getName();
            $this->BlockDAO->delete($block);

            return $this->redirectToRoute('list_category_block', ['category_name' => $category_name]);
        }

        return [];
    }

    /**
     * @Template("default/admin/edit_category_block.html")
     * @Route("/add_category_block/{category_name}", name="add_category_block")
     */
    public function categoryBlockAdd(Request $request, $category_name) {
        $rootPath = dirname($this->kernel->getRootDir());

        $category = $this->SectionDAO->findOneBy(['name' => $category_name]);
        $block = new BlockEntity();
        $block->setSection($category);
        $block->convertImagesNameToFileObject($rootPath);

        $form = $this->createForm(BlockType::class, $block);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $block = $form->getData();
            if (!$block->getPriority()) {
                $block->setPriority(1000);
            }

            $blockImages = $block->getImages();

            foreach ($blockImages as $key => $image) {
                $uploadedImage = $image->getFileNameAssert();
                /**
                 * видаляєм порожні поля малюнків (заглушки в формі редагування)
                 */
                if (!$uploadedImage && !$image->getFileName()) {
                    $blockImages->remove($key);
                    continue;
                }
                if ($uploadedImage && $image->getFileName()) {
                    // видалення з диску існуючих малюнків
                    $image->remuveFromStorage($rootPath);
                    $this->thumbnailsCacheManager->remove($image->getFull());
                }

                if ($uploadedImage) {
                    $fileName = md5(uniqid()) . '.' . $uploadedImage->guessExtension();
                    $uploadedImage->move($rootPath . BlockImageEntity::$PHOTO_DIR, $fileName);

                    $image->setFileName($fileName);
                }

            }

            $this->BlockDAO->save($block);

            return $this->redirectToRoute('add_category_block', ['category_name' => $category_name]);

        }

        return ["form" => $form->createView(), 'block' => $block];
    }

    /**
     * @Template("default/admin/additional_service.html")
     * @Route("/admin/additional_service", name="admin_additional_service")
     */
    function additionalServiceAction(Request $request) {
        $redirectRoute = 'admin_additional_service';
        $editAddClass = AdditionalServiceType::class;
        $moveLevelClass = AdditionalServiceMoveLevel::class;
        $moveInParentClass = AdditionalServiceMoveInParent::class;
        $entityTypeClass = AdditionalServiceEntity::class;
        $elementNew = new $entityTypeClass();
        $DAO = $this->AdditionalServiceDAO;

        $treeElements = $DAO->findBy([], ["lft" => "ASC"]);
        $form_name = $form_name_2 = null;
        $locale = $request->getLocale();

        if ($elementID = $request->query->get("element_id")) {
            $mode = $request->query->get("mode");
            $element = $DAO->findOneBy(["id" => $elementID]);
            $parentId = $element->getParent() ? $element->getParent()->getID() : 0;
            if ($mode == "dellete") {
                $DAO->delete($element);

                return $this->redirectToRoute($redirectRoute);
            } elseif ($mode == "edit") {
                $form_name = "Редагування розділу";
                $form = $this->createForm($editAddClass, $element, ['data_class' => $entityTypeClass]);
            } elseif ($mode == "add") {
                $form_name = "Додача розділу";
                if ($parrent_name = $element->getTitleI18n($locale)) {
                    $form_name .= ' в розділ "' . $parrent_name . '"';
                }

                $elementNew->setParent($element);
                $form = $this->createForm($editAddClass, $elementNew, ['data_class' => $entityTypeClass]);
            } elseif ($mode == "move") {
                $form_name = 'Переміщення розділу "' . $element->getTitleI18n($locale) . '" (з усіма підрозділами) до іншого батьківського розділу';
                $form_move = $this->createForm($moveLevelClass, $element, ['data_class' => $entityTypeClass, 'choices' => $treeElements, 'locale' => $locale, 'parentId' => $parentId]);

                $form_name_2 = 'Переміщення розділу "' . $element->getTitleI18n($locale) . '" в межах батьківського розділу';

                $sibling = $DAO->children($element->getParent(), true, "lft", "ASC");
                $form_2 = $this->createForm($moveInParentClass, null, ['choices' => $sibling, 'locale' => $locale, 'currentId' => $elementID, 'entityTypeClass' => $entityTypeClass]);

            }


            if (isset($form_move)) {
                $form_move->handleRequest($request);

                if ($form_move->isSubmitted() && $form_move->isValid()) {

                    $element = $form_move->getData();
                    $DAO->save($element);

                    return $this->redirectToRoute($redirectRoute);
                }
            }

            if (isset($form)) {
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {

                    $element = $form->getData();
                    $DAO->save($element);

                    return $this->redirectToRoute($redirectRoute);
                }
            }

            if (isset($form_2)) {
                $form_2->handleRequest($request);

                if ($form_2->isSubmitted() && $form_2->isValid()) {

                    $sibling = $form_2->getData();
                    $method = $sibling['direction'];

                    if ($method == 'Prev') {
                        $DAO->setPrevTo($element, $sibling['sibling']);
                    } elseif ($method == 'Next') {
                        $DAO->setNextTo($element, $sibling['sibling']);
                    }

                    return $this->redirectToRoute($redirectRoute);
                }
            }

        }
        

        return ["redirectRoute"=>$redirectRoute, "treeElements" => $treeElements, "elementID" => $elementID, "form" => isset($form) ? $form->createView() : null, 'form_name' => $form_name, "form_2" => isset($form_2) ? $form_2->createView() : null, "form_move" => isset($form_move) ? $form_move->createView() : null, 'form_name_2' => $form_name_2];
    }

}
