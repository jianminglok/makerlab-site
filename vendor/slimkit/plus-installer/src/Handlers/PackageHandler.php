<?php

namespace SlimKit\PlusInstaller\Handlers;

use Illuminate\Console\Command;
use Zhiyi\Plus\Support\PackageHandler as BasePackageHandler;

class PackageHandler extends BasePackageHandler
{
    /**
     * Publish public asstes source handle.
     *
     * @param \Illuminate\Console\Command $command
     * @return mixed
     */
    public function publishAssetsHandle(Command $command)
    {
        $force = $command->confirm('Overwrite any existing files');

        return $command->call('vendor:publish', [
            '--provider' => \SlimKit\PlusInstaller\Providers\AppServiceProvider::class,
            '--tag' => 'installer-public',
            '--force' => boolval($force),
        ]);
    }
}
