<?php

namespace ArchitectureSniffer\Zed\Business\Facade;

use ArchitectureSniffer\AbstractArchitectureSnifferTestCase;

/**
 * @group Zed
 * @group Business
 * @group Facade
 * @group NoPropertiesFacadeRule
 */
class NoPropertiesFacadeRuleTest extends AbstractArchitectureSnifferTestCase
{

    /**
     * @return void
     */
    public function testValid()
    {
        $this->expectValid($this);
    }

    /**
     * @return void
     */
    public function testInValid()
    {
        $this->expectInValid($this);
    }

    /**
     * @return string
     */
    protected function getRuleSetPath()
    {
        $ruleSet = $this->getSourceDirectory() . 'Zed/ruleset.xml';

        return $ruleSet;
    }

}
