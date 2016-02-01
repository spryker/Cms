<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Cms\Business\Page;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Cms\Business\Exception\MissingPageException;
use Spryker\Zed\Cms\Business\Exception\MissingTemplateException;
use Spryker\Zed\Cms\Business\Exception\PageExistsException;
use Orm\Zed\Cms\Persistence\SpyCmsPage;

interface PageManagerInterface
{

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $page
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingTemplateException
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     * @throws \Spryker\Zed\Url\Business\Exception\MissingUrlException
     * @throws \Spryker\Zed\Cms\Business\Exception\PageExistsException
     *
     * @return \Generated\Shared\Transfer\PageTransfer
     */
    public function savePage(PageTransfer $page);

    /**
     * @param int $idPage
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPage
     */
    public function getPageById($idPage);

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $page
     *
     * @return \Generated\Shared\Transfer\PageTransfer
     */
    public function convertPageEntityToTransfer(SpyCmsPage $page);

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $page
     *
     * @return void
     */
    public function touchPageActive(PageTransfer $page);

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $page
     * @param string $url
     *
     * @throws \Spryker\Zed\Url\Business\Exception\UrlExistsException
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createPageUrl(PageTransfer $page, $url);

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $page
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @throws \Spryker\Zed\Url\Business\Exception\UrlExistsException
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createPageUrlWithLocale(PageTransfer $page, $url, LocaleTransfer $localeTransfer);

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $page
     * @param string $url
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function savePageUrlAndTouch(PageTransfer $page, $url);

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $page
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $blockTransfer
     *
     * @return \Generated\Shared\Transfer\PageTransfer
     */
    public function savePageBlockAndTouch(PageTransfer $page, CmsBlockTransfer $blockTransfer);

}
