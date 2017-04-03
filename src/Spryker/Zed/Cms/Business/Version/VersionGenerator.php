<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version;

use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class VersionGenerator implements VersionGeneratorInterface
{

    const DEFAULT_VERSION_NUMBER = 1;

    /**
     * @var CmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param CmsQueryContainerInterface $queryContainer
     */
    public function __construct(CmsQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param int $idCmsPage
     *
     * @return int
     */
    public function generateNewCmsVersion($idCmsPage)
    {
        $cmsVersionEntity = $this->queryContainer->queryCmsVersionByIdPage($idCmsPage)->findOne();

        if ($cmsVersionEntity === null) {
            return self::DEFAULT_VERSION_NUMBER;
        }

        return $cmsVersionEntity->getVersion() + 1;
    }

    /**
     * @param int $versionNumber
     *
     * @return string
     */
    public function generateNewCmsVersionName($versionNumber)
    {
        return sprintf('v. %d', $versionNumber);
    }

    /**
     * @param int $idCmsVersionReference
     *
     * @return string
     */
    public function generateReferenceCmsVersionName($idCmsVersionReference)
    {
        $cmsVersionEntity = $this->queryContainer->queryCmsVersionById($idCmsVersionReference)->findOne();

        return sprintf('Copied from v. %d', $cmsVersionEntity->getVersion());
    }
}
