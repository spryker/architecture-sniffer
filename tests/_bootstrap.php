<?php
/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

require_once '_data/Common/Plugin/NewPluginExtensionModuleTest/testRuleDoesNotApplyWhenPluginImplementsExtensionModuleInterface.php';
require_once '_data/Common/Plugin/NewPluginExtensionModuleTest/testRuleAppliesWhenPluginImplementsNotExtensionModuleInterface.php';
require_once '_data/Common/Bridge/BridgeMethodsTest/testRuleAppliesWhenBridgeMethodsAreNotCorrect.php';
require_once '_data/Common/Bridge/BridgeMethodsTest/testRuleDoesNotApplyWhenBridgeMethodsAreCorrect.php';
require_once '_data/Common/Bridge/BridgeMethodsTest/testRuleAppliesWhenBridgeMethodsParamsShouldHaveTypeHint.php';
require_once '_data/Common/Bridge/BridgeMethodsInterfaceTest/testRuleAppliesWhenReturnTypeIsAbsent.php';
require_once '_data/Common/Bridge/BridgeMethodsInterfaceTest/testRuleDoesNotApplyWhenReturnTypeIsNotSupportedInPhp7.php';
require_once '_data/Common/Bridge/BridgeMethodsInterfaceTest/testRuleAppliesWhenBridgeInterfaceMethodsAreNotCorrect.php';
require_once '_data/Common/Bridge/BridgeMethodsInterfaceTest/testRuleDoesNotApplyWhenBridgeInterfaceMethodsAreCorrect.php';
require_once '_data/Common/Bridge/BridgeFacadeMethodsTest/testRuleAppliesWhenFacadeBridgeMethodsAreCorrect.php';
require_once '_data/Common/Bridge/BridgeFacadeMethodsTest/testRuleDoesNotApplyWhenFacadeBridgeMethodsReturnTypeAreNotCorrect.php';
require_once '_data/Common/Bridge/BridgeFacadeMethodsTest/testRuleDoesNotApplyWhenBridgeFacadeMethodsParamsAreNotCorrect.php';
require_once '_data/Common/Bridge/BridgeFacadeMethodsTest/testRuleDoesNotApplyWhenBridgeFacadeMethodNamesAreNotCorrect.php';
