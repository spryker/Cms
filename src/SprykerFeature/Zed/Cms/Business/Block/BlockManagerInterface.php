<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Cms\Business\Block;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsBlock;

interface BlockManagerInterface
{

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return CmsBlockTransfer
     */
    public function saveBlock(CmsBlockTransfer $cmsBlockTransfer);

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return CmsBlockTransfer
     */
    public function saveBlockAndTouch(CmsBlockTransfer $cmsBlockTransfer);

    /**
     * @param int $idCategoryNode
     */
    public function updateBlocksAssignedToDeletedCategoryNode($idCategoryNode);

    /**
     * @param SpyCmsBlock $blockEntity
     *
     * @return CmsBlockTransfer
     */
    public function convertBlockEntityToTransfer(SpyCmsBlock $blockEntity);

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     */
    public function touchBlockActive(CmsBlockTransfer $cmsBlockTransfer);

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     */
    public function touchBlockActiveWithKeyChange(CmsBlockTransfer $cmsBlockTransfer);

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     */
    public function touchBlockDelete(CmsBlockTransfer $cmsBlockTransfer);

    /**
     * @param int $idCategoryNode
     *
     * @return bool
     */
    public function hasBlockCategoryNodeMapping($idCategoryNode);

    /**
     * @param int $idCategoryNode
     *
     * @return CmsBlockTransfer[]
     */
    public function getCmsBlocksByIdCategoryNode($idCategoryNode);

}
