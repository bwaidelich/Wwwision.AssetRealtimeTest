<?php
declare(strict_types=1);
namespace Wwwision\AssetRealtimeTest;

use Neos\Cache\Frontend\StringFrontend;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Utility\Algorithms;

/**
 * @Flow\Scope("singleton")
 */
final class AssetChangeLog
{

    private StringFrontend $cache;
    private int $cacheLifetime;

    public function __construct(StringFrontend $cache, int $cacheLifetime)
    {
        $this->cache = $cache;
        $this->cacheLifetime = $cacheLifetime;
    }

    public function add(string $assetId): void
    {
        $this->cache->set(md5($assetId), $assetId, ['changedAssets'], $this->cacheLifetime);
        $this->cache->set('_etag_', Algorithms::generateRandomString(10));
    }

    public function getAssetIds(): array
    {
        return array_filter(array_values($this->cache->getByTag('changedAssets')));
    }

    public function getHash(): string
    {
        return (string)$this->cache->get('_etag_');
    }
}
