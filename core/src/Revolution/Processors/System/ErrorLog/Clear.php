<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\ErrorLog;

use MODX\Revolution\modCacheManager;
use MODX\Revolution\modProcessor;
use xPDO\Cache\xPDOCacheManager;

/**
 * Clear the error log
 * @package MODX\Revolution\Processors\System\ErrorLog
 */
class Clear extends modProcessor
{
    /**
     * @return mixed
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('error_log_erase');
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $logTarget = $this->modx->getLogTarget();
        if (!is_array($logTarget)) {
            $logTarget = ['options' => []];
        }
        $filename = $this->modx->getOption('filename', $logTarget['options'], 'error.log', true);
        $filepath = $this->modx->getOption('filepath', $logTarget['options'],
            $this->modx->getCachePath() . xPDOCacheManager::LOG_DIR, true);
        $file = rtrim($filepath, '/') . '/' . $filename;
        $content = '';
        $tooLarge = false;
        if (file_exists($file)) {
            /* @var modCacheManager $cacheManager */
            $cacheManager = $this->modx->getCacheManager();
            $cacheManager->writeFile($file, '');

            $size = round(@filesize($file) / 1000 / 1000, 2);
            if ($size > 1) {
                $tooLarge = true;
            } else {
                $content = @file_get_contents($file);
            }
        }

        $la = [
            'name' => $file,
            'log' => $content,
            'tooLarge' => $tooLarge,
        ];

        return $this->success('', $la);
    }
}
