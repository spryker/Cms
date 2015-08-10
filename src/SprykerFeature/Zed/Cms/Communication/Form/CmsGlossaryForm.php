<?php

namespace SprykerFeature\Zed\Cms\Communication\Form;

use SprykerFeature\Zed\Cms\Business\CmsFacade;
use SprykerFeature\Zed\Cms\Communication\Form\Constraint\CmsConstraint;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsGlossaryKeyMappingQuery;
use SprykerFeature\Zed\Glossary\Business\GlossaryFacade;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContext;

class CmsGlossaryForm extends AbstractForm
{

    const FK_PAGE = 'fkPage';
    const PLACEHOLDER = 'placeholder';
    const GLOSSARY_KEY = 'glossary_key';
    const ID_KEY_MAPPING = 'idCmsGlossaryKeyMapping';

    /**
     * @var SpyCmsGlossaryKeyMappingQuery
     */
    protected $glossaryByIdQuery;

    /**
     * @var int
     */
    protected $idPage;

    /**
     * @var int
     */
    protected $idMapping;

    /**
     * @var array
     */
    protected $placeholder;

    /**
     * @var CmsFacade
     */
    protected $cmsFacade;

    /**
     * @param SpyCmsGlossaryKeyMappingQuery $glossaryByIdQuery
     * @param int $idPage
     * @param int $idMapping
     * @param array $placeholder
     * @param CmsFacade $cmsFacade
     * @param GlossaryFacade $glossaryFacade
     */

    public function __construct(SpyCmsGlossaryKeyMappingQuery $glossaryByIdQuery, $idPage, $idMapping, $placeholder, CmsFacade $cmsFacade)
    {
        $this->glossaryByIdQuery = $glossaryByIdQuery;
        $this->idPage = $idPage;
        $this->idMapping = $idMapping;
        $this->placeholder = $placeholder;
        $this->cmsFacade = $cmsFacade;
    }

    /**
     * @return CmsRedirectForm
     */
    protected function buildFormFields()
    {
        $placeholderConstraints = CmsConstraint::getMandatoryConstraints();

        if (!isset($this->idMapping)) {
            $placeholderConstraints[] = new Callback([
                'methods' => [
                    function ($placeholder, ExecutionContext $context) {
                        if ($this->cmsFacade->hasPagePlaceholderMapping($this->idPage, $placeholder)) {
                            $context->addViolation('Placeholder has already mapped');
                        }
                    },
                ],
            ]);
        }

        $keyConstraints[] = new Callback([
            'methods' => [
                function ($key, ExecutionContext $context) {
                    if (!$this->glossaryByIdQuery->useGlossaryKeyQuery()
                        ->findOneByKey($key)
                    ) {
                        $context->addViolation('Key does not exist.');
                    }
                },
            ],
        ]);

        $placeholderParams = [
            'label' => 'Placeholder',
            'constraints' => $placeholderConstraints,
        ];

        if (intval($this->idMapping) > 0) {
            $placeholderParams['disabled'] = 'disabled';
        }

        return $this->addHidden(self::FK_PAGE)
            ->addHidden(self::ID_KEY_MAPPING)
            ->addText(self::PLACEHOLDER, $placeholderParams)
            ->addAutosuggest(self::GLOSSARY_KEY, [
                'label' => 'Glossary Key',
                'url' => '/glossary/key/suggest',
                'constraints' => $keyConstraints,
            ])
            ;
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        $formItems = [
            self::FK_PAGE => $this->idPage,
            self::ID_KEY_MAPPING => $this->idMapping,
        ];

        if ($this->placeholder) {
            $formItems[self::PLACEHOLDER] = $this->placeholder;
        }

        if (null !== $this->idMapping) {
            $glossaryMapping = $this->glossaryByIdQuery->findOne();

            if ($glossaryMapping) {
                $formItems[self::PLACEHOLDER] = $glossaryMapping->getPlaceholder();
                $formItems[self::GLOSSARY_KEY] = $glossaryMapping->getKeyname();
            }
        }

        return $formItems;
    }

}
