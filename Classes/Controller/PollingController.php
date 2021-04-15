<?php
declare(strict_types=1);
namespace Wwwision\AssetRealtimeTest\Controller;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Media\Domain\Repository\AssetRepository;
use Wwwision\AssetRealtimeTest\AssetChangeLog;

final class PollingController extends ActionController
{

    /**
     * @Flow\Inject
     * @var AssetRepository
     */
    protected $assetRepository;

    /**
     * @Flow\Inject
     * @var AssetChangeLog
     */
    protected $assetChangeLog;

    /**
     * @param string|null $assetId
     */
    public function indexAction(string $assetId = null): void
    {
        $this->view->assign('etag', $this->assetChangeLog->getHash());
        $this->view->assign('assetId', $assetId);
        if ($assetId !== null) {
            $this->view->assign('asset', $this->assetRepository->findByIdentifier($assetId));
        }
    }
}
