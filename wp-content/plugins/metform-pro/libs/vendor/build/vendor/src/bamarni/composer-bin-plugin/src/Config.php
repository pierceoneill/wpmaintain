<?php

namespace MetFormProVendor\Bamarni\Composer\Bin;

use MetFormProVendor\Composer\Composer;
final class Config
{
    private $config;
    public function __construct(Composer $composer)
    {
        $extra = $composer->getPackage()->getExtra();
        $this->config = \array_merge(['bin-links' => \true, 'target-directory' => 'vendor-bin', 'forward-command' => \false], isset($extra['bamarni-bin']) ? $extra['bamarni-bin'] : []);
    }
    /**
     * @return bool
     */
    public function binLinksAreEnabled()
    {
        return \true === $this->config['bin-links'];
    }
    /**
     * @return string
     */
    public function getTargetDirectory()
    {
        return $this->config['target-directory'];
    }
    /**
     * @return bool
     */
    public function isCommandForwarded()
    {
        return $this->config['forward-command'];
    }
}
