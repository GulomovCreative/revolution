<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 *
 * @package modx-test
*/
namespace MODX\Revolution\Tests\Model\Registry;


use MODX\Revolution\MODxTestCase;
use MODX\Revolution\MODxTestHarness;
use MODX\Revolution\Registry\modDbRegister;
use MODX\Revolution\Registry\modFileRegister;
use MODX\Revolution\Registry\modRegister;

/**
 * Tests related to the modRegistry class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Registry
 * @group modRegistry
 */
class modRegistryTest extends MODxTestCase {
    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();
        $modx =& MODxTestHarness::getFixture('modX', 'modx');
        $modx->getService('registry', 'registry.modRegistry');
    }

    /**
     * Test the modRegistry->getRegister() method.
     *
     * @dataProvider providerGetRegister
     * @param boolean $shouldPass Indicates if the test is expected to pass.
     * @param string $name The name or key of the register to get.
     * @param string $class The name of the modRegister class implementation.
     * @param array $options An array of options for the modRegister instance to use if not already created.
     */
    public function testGetRegister($shouldPass, $name, $class, $options) {
        $this->modx->registry->getRegister($name, $class, $options);
        $actualClass = $this->modx->loadClass($class);
        if ($actualClass === false) {
            $actualClass = modRegister::class;
        }
        if ($shouldPass) {
            $this->assertInstanceOf($actualClass, $this->modx->registry->$name, "Could not get a valid modRegister instance.");
        } else {
            $this->assertNotInstanceOf($actualClass, $this->modx->registry->$name, "Got an unexpected modRegister instance.");
        }
    }
    public function providerGetRegister() {
        return array(
            array(true, 'testFileRegister', modFileRegister::class, array('directory' => 'testFileRegister')),
            array(true, 'testFileRegister2', modFileRegister::class, array('directory' => 'testFileRegister2')),
            array(true, 'testDbRegister', modDbRegister::class, array('directory' => 'testDbRegister')),
            array(true, 'testDbRegister2', modDbRegister::class, array('directory' => 'testDbRegister2')),
            array(false, 'testDbRegister3', '\MODX\Revolution\Registry\modDbRegister3', array('directory' => 'testDbRegister3')),
            array(false, 'modx', modFileRegister::class, array('directory' => 'modx')),
        );
    }

    /**
     * Test the modRegistry->addRegister() method.
     *
     * @dataProvider providerAddRegister
     * @param boolean $shouldPass Indicates if the test is expected to pass.
     * @param string $name The name or key of the register to add.
     * @param string $class The name of the modRegister class implementation.
     * @param array $options An array of options for the modRegister instance to use if not already created.
     */
    public function testAddRegister($shouldPass, $name, $class, $options) {
        $this->modx->registry->addRegister($name, $class, $options);
        $actualClass = $this->modx->loadClass($class);
        if ($actualClass === false) {
            $actualClass = modRegister::class;
        }
        if ($shouldPass) {
            $this->assertInstanceOf($actualClass, $this->modx->registry->$name, "Could not get a valid modRegister instance.");
        } else {
            $this->assertNotInstanceOf($actualClass, $this->modx->registry->$name, "Got an unexpected modRegister instance.");
        }
    }
    public function providerAddRegister() {
        return array(
            array(true, 'testFileRegister', modFileRegister::class, array('directory' => 'testFileRegister')),
            array(true, 'testFileRegister2', modFileRegister::class, array('directory' => 'testFileRegister2')),
            array(true, 'testDbRegister', modDbRegister::class, array('directory' => 'testDbRegister')),
            array(true, 'testDbRegister2', modDbRegister::class, array('directory' => 'testDbRegister2')),
            array(false, 'testDbRegister3', '\MODX\Revolution\Registry\modDbRegister3', array('directory' => 'testDbRegister3')),
            array(false, 'modx', modFileRegister::class, array('directory' => 'modx')),
        );
    }

    /**
     * Test modRegistry->removeRegister() method.
     *
     * @dataProvider providerRemoveRegister
     * @param string $name The name or key of the register instance to remove.
     */
    public function testRemoveRegister($name) {
        $this->modx->registry->removeRegister($name);
        $this->assertNotInstanceOf(modRegister::class, $this->modx->registry->$name, "Could not remove register with key {$name}");
    }
    public function providerRemoveRegister() {
        return array(
            array('testFileRegister'),
            array('testFileRegister2'),
            array('testDbRegister'),
            array('testDbRegister2'),
            array('testDbRegister3'),
            array('modx'),
        );
    }

    public function testSetLogging() {
        $this->assertTrue(true);
    }

    public function testResetLogging() {
        $this->assertTrue(true);
    }

    public function testIsLogging() {
        $this->assertTrue(true);
    }
}
