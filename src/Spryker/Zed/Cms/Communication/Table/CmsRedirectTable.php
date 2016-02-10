<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Cms\Communication\Table;

use Orm\Zed\Url\Persistence\SpyUrl;
use Spryker\Zed\Cms\Communication\Controller\RedirectController;
use Spryker\Zed\Cms\Persistence\CmsQueryContainer;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Orm\Zed\Url\Persistence\Map\SpyUrlRedirectTableMap;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;

class CmsRedirectTable extends AbstractTable
{

    const ACTIONS = 'Actions';
    const REQUEST_ID_URL = 'id-url';

    /**
     * @var \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    protected $urlQuery;

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrlQuery $urlQuery
     */
    public function __construct($urlQuery)
    {
        $this->urlQuery = $urlQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyUrlTableMap::COL_ID_URL => 'ID',
            SpyUrlTableMap::COL_URL => 'From Url',
            CmsQueryContainer::TO_URL => 'To Url',
            SpyUrlRedirectTableMap::COL_STATUS => 'Status',
            self::ACTIONS => self::ACTIONS,
        ]);
        $config->setSortable([
            SpyUrlTableMap::COL_ID_URL,
            SpyUrlTableMap::COL_URL,
        ]);

        $config->setSearchable([
            SpyUrlTableMap::COL_ID_URL,
            SpyUrlTableMap::COL_URL,
            CmsQueryContainer::TO_URL => 'to_url',
            SpyUrlRedirectTableMap::COL_STATUS,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $urlCollection = $this->getUrlCollection($config);
        $results = [];

        foreach ($urlCollection as $urlEntity) {
            $results[] = [
                SpyUrlTableMap::COL_ID_URL => $urlEntity->getIdUrl(),
                SpyUrlTableMap::COL_URL => $urlEntity->getUrl(),
                CmsQueryContainer::TO_URL => $urlEntity->getSpyUrlRedirect()->getToUrl(),
                SpyUrlRedirectTableMap::COL_STATUS => $urlEntity->getSpyUrlRedirect()->getStatus(),
                self::ACTIONS => $this->buildLinks($urlEntity),
            ];
        }

        return $results;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrl[]
     */
    private function getUrlCollection(TableConfiguration $config)
    {
        return $this->runQuery($this->urlQuery, $config, true);
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     *
     * @return string
     */
    private function buildLinks(SpyUrl $urlEntity)
    {
        $buttons[] = $this->generateEditButton(sprintf('/cms/redirect/edit?%s=%s', RedirectController::REQUEST_ID_URL, $urlEntity->getIdUrl()), 'Edit');
        $buttons[] = $this->generateRemoveButton(sprintf('/cms/redirect/delete?%s=%s', RedirectController::REQUEST_ID_URL_REDIRECT, $urlEntity->getSpyUrlRedirect()->getIdUrlRedirect()), 'Delete');

        return implode(' ', $buttons);
    }

}
