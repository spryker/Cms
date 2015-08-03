<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cms\Communication\Controller;

use Generated\Shared\Transfer\PageKeyMappingTransfer;
use Generated\Shared\Transfer\PageTransfer;
use Pyz\Zed\Cms\CmsDependencyProvider;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Cms\Business\CmsFacade;
use SprykerFeature\Zed\Cms\Communication\Form\CmsGlossaryForm;
use SprykerFeature\Zed\Cms\Communication\Form\CmsPageForm;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CmsDependencyContainer getDependencyContainer()
 * @method CmsFacade getFacade()
 */
class GlossaryController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idPage = $request->get('id_page');

        $localeTransfer = $this->getLocaleFacade()->getCurrentLocale();

        $table = $this->getDependencyContainer()
            ->createCmsGlossaryTable($idPage, $localeTransfer->getIdLocale())
        ;

        return [
            'keyMaps' => $table->render(),
            'idPage' => $idPage,
        ];
    }

    /**
     * @return JsonResponse
     */
    public function tableAction(Request $request)
    {
        $idPage = $request->get('id_page');

        $localeTransfer = $this->getLocaleFacade()->getCurrentLocale();

        $table = $this->getDependencyContainer()
            ->createCmsGlossaryTable($idPage, $localeTransfer->getIdLocale())
        ;

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @return array
     */
    public function addAction(Request $request)
    {

        $idPage = $request->get('id_page');

        $form = $this->getDependencyContainer()
            ->createCmsGlossaryForm('add', $idPage)
        ;

        $form->handleRequest();

        if ($form->isValid()) {
            $data = $form->getData();

            $pageKeyMappingTransfer = (new PageKeyMappingTransfer())->fromArray($data, true);
            $spyGlossaryKey = $this->getQueryContainer()
                ->queryKey($data[CmsGlossaryForm::GLOSSARY_KEY])
                ->findOne()
            ;
            $pageKeyMappingTransfer->setFkGlossaryKey($spyGlossaryKey->getIdGlossaryKey());

            $this->getFacade()
                ->savePageKeyMapping($pageKeyMappingTransfer)
            ;

            $this->touchActivePage($idPage);

            return $this->redirectResponse('/cms/glossary/?id_page=' . $idPage);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'idPage' => $idPage
        ]);
    }

    public function editAction(Request $request)
    {
        $idMapping = $request->get('id_mapping');
        $idPage = $request->get('id_page');

        $form = $this->getDependencyContainer()
            ->createCmsGlossaryForm('update', $idPage, $idMapping)
        ;

        $form->handleRequest();

        if ($form->isValid()) {
            $data = $form->getData();

            $pageKeyMappingTransfer = (new PageKeyMappingTransfer())->fromArray($data, true);
            $spyGlossaryKey = $this->getQueryContainer()
                ->queryKey($data[CmsGlossaryForm::GLOSSARY_KEY])
                ->findOne()
            ;
            $pageKeyMappingTransfer->setFkGlossaryKey($spyGlossaryKey->getIdGlossaryKey());

            $this->getFacade()
                ->savePageKeyMapping($pageKeyMappingTransfer)
            ;

            $this->touchActivePage($idPage);

            return $this->redirectResponse('/cms/glossary/?id_page=' . $idPage);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'idPage' => $idPage
        ]);
    }

    public function deleteAction(Request $request)
    {
        $idMapping = $request->get('id_mapping');
    }

    /**
     * @return LocaleFacade
     */
    private function getLocaleFacade()
    {
        return $this
            ->getDependencyContainer()
            ->getProvidedDependency(CmsDependencyProvider::LOCALE_BUNDLE)
            ;
    }

    private function touchActivePage($idPage){
        $spyPage = $this->getQueryContainer()->queryPageById($idPage)->findOne();
        $pageTransfer = (new PageTransfer())->fromArray($spyPage->toArray());

        return $this->getFacade()
            ->touchPageActive($pageTransfer)
        ;
    }
}
