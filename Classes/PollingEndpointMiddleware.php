<?php
declare(strict_types=1);
namespace Wwwision\AssetRealtimeTest;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class PollingEndpointMiddleware implements MiddlewareInterface
{

    private AssetChangeLog $assetChangeLog;

    public function __construct(AssetChangeLog $assetChangeLog)
    {
        $this->assetChangeLog = $assetChangeLog;
    }


    public function process(ServerRequestInterface $request, RequestHandlerInterface $next): ResponseInterface
    {
        if ($request->getUri()->getPath() !== '/__recent-asset-changes') {
            return $next->handle($request);
        }
        $headers = ['Content-Type' => 'application/json'];
        $etag = $this->assetChangeLog->getHash();
        if ($etag !== '') {
            foreach ($request->getHeader('If-None-Match') as $match) {
                if ($match === $etag) {
                    return new Response(304);
                }
            }
            $headers['E-Tag'] = $etag;
        }
        return new Response(200, $headers, \json_encode($this->assetChangeLog->getAssetIds(), JSON_THROW_ON_ERROR));
    }
}
