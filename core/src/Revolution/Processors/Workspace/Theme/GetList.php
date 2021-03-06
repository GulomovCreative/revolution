<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Workspace\Theme;

use DirectoryIterator;
use MODX\Revolution\modObjectProcessor;

/**
 * Grabs a list of manager themes
 * @package MODX\Revolution\Processors\Workspace\Theme
 */
class GetList extends modObjectProcessor
{
    public $permission = 'settings';

    /**
     * @return mixed|string
     */
    public function process()
    {
        $themePath = $this->modx->config['manager_path'] . 'templates/';
        $themes = array();

        $dir = new DirectoryIterator($themePath);
        foreach ($dir as $fileInfo) {
            if ($fileInfo->isDir() && !$fileInfo->isDot()) {
                $themes[] = array('theme' => $fileInfo->getFilename());
            }
        }

        return $this->outputArray($themes, count($themes));
    }
}
