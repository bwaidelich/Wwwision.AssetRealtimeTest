<?php
declare(strict_types=1);
namespace Wwwision\AssetRealtimeTest\Command;

use Neos\Flow\Cli\CommandController;
use Wwwision\AssetRealtimeTest\AssetChangeLog;

final class TestCommandController extends CommandController
{

    private AssetChangeLog $assetChangeLog;

    public function __construct(AssetChangeLog $assetChangeLog)
    {
        parent::__construct();
        $this->assetChangeLog = $assetChangeLog;
    }


    public function testCommand(): void
    {
        $this->assetChangeLog->add((string)mktime());
        $this->outputLine('YO');
    }
}
