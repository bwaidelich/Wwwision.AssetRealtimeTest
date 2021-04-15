<?php
declare(strict_types=1);
namespace Wwwision\AssetRealtimeTest;

use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Package\Package as BasePackage;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Media\Domain\Model\AssetInterface;
use Neos\Media\Domain\Service\AssetService;

class Package extends BasePackage
{

    public function boot(Bootstrap $bootstrap): void
    {
        $dispatcher = $bootstrap->getSignalSlotDispatcher();

        $logAssetChangeClosure = static function(AssetInterface $asset) use ($bootstrap) {
            $assetId = $bootstrap->getObjectManager()->get(PersistenceManagerInterface::class)->getIdentifierByObject($asset);
            /** @var AssetChangeLog $assetChangeLog */
            $assetChangeLog = $bootstrap->getObjectManager()->get(AssetChangeLog::class);
            $assetChangeLog->add($assetId);
        };
        $dispatcher->connect(AssetService::class, 'assetCreated', $logAssetChangeClosure);
        $dispatcher->connect(AssetService::class, 'assetUpdated', $logAssetChangeClosure);
        $dispatcher->connect(AssetService::class, 'assetRemoved', $logAssetChangeClosure);
        $dispatcher->connect(AssetService::class, 'assetResourceReplaced', $logAssetChangeClosure);
    }
}
