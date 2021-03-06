<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Access\UserGroup\AccessNamespace;

use MODX\Revolution\modAccessNamespace;
use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\modNamespace;
use MODX\Revolution\modObjectUpdateProcessor;
use MODX\Revolution\modUserGroup;

/**
 * @package MODX\Revolution\Processors\Security\Access\UserGroup\AccessNamespace
 */
class Update extends modObjectUpdateProcessor
{
    public $classKey = modAccessNamespace::class;
    public $objectType = 'access_namespace';
    public $languageTopics = ['access', 'user', 'namespace'];
    public $permission = 'access_permissions';

    /**
     * @return bool
     */
    public function beforeSet()
    {
        $principal = $this->getProperty('principal');
        if ($principal === null) {
            $this->addFieldError('principal', $this->modx->lexicon('usergroup_err_ns'));
        }

        $policy = $this->getProperty('policy', '');
        if (empty($policy)) {
            $this->addFieldError('policy', $this->modx->lexicon('access_policy_err_ns'));
        }

        $authority = $this->getProperty('authority');
        if ($authority === null) {
            $this->addFieldError('authority', $this->modx->lexicon('authority_err_ns'));
        }

        $namespace = $this->getProperty('target');
        if (!$namespace) {
            $this->addFieldError('target', $this->modx->lexicon('namespace_err_ns'));
        }

        return parent::beforeSet();
    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $policy = $this->modx->getObject(modAccessPolicy::class, $this->getProperty('policy'));
        if (!$policy) {
            $this->addFieldError('policy', $this->modx->lexicon('access_policy_err_nf'));
        }

        /** @var modNamespace $namespace */
        $namespace = $this->modx->getObject(modNamespace::class, $this->getProperty('target'));
        if (!$namespace) {
            $this->addFieldError('target', $this->modx->lexicon('namespace_err_nf'));
        } else {
            if (!$namespace->checkPolicy('view')) {
                $this->addFieldError('target', $this->modx->lexicon('access_denied'));
            }
        }

        if ($this->doesAlreadyExist([
            'principal' => $this->object->get('principal'),
            'principal_class' => modUserGroup::class,
            'target' => $this->object->get('target'),
            'policy' => $this->object->get('policy'),
            'context_key' => $this->object->get('context_key'),
            'id:!=' => $this->object->get('id'),
        ])) {
            $this->addFieldError('target', $this->modx->lexicon($this->objectType . '_err_ae'));
        }
        $this->object->set('principal_class', modUserGroup::class);

        return parent::beforeSave();
    }
}
