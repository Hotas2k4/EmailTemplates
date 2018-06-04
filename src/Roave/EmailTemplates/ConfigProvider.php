<?php
/**
 * @author    Jonas Eriksson <jonas.eriksson@interactivesolutions.se>
 *
 * @copyright Interactive Solutions
 */
declare(strict_types=1);

namespace Roave\EmailTemplates;


final class ConfigProvider
{
    public function __invoke(): array
    {
        $config = [];
        $config = array_merge_recursive($config, require __DIR__ . '/../../../config/doctrine.config.php');
        $config = array_merge_recursive($config, require __DIR__ . '/../../../config/module.config.php');

        return $config;
    }
}