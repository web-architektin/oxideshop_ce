<?php
/**
 * This file is part of OXID eShop Community Edition.
 *
 * OXID eShop Community Edition is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OXID eShop Community Edition is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2014
 * @version   OXID eShop CE
 */

/**
 * @covers oxServerNodeProcessor
 */
class Unit_Core_oxServerProcessorTest extends OxidTestCase
{
    public function testConstructorCreatesDefaultObjectServerNodesManager()
    {
        $oServerNodeProcessor = new oxServerNodeProcessor();
        $this->assertInstanceOf('oxServerNodesManager', $oServerNodeProcessor->UNITgetServerNodesManager());
    }

    public function testConstructorCreatesDefaultObjectServerNodeChecker()
    {
        $oServerNodeProcessor = new oxServerNodeProcessor();
        $this->assertInstanceOf('oxServerNodeChecker', $oServerNodeProcessor->UNITgetServerNodeChecker());
    }

    public function testConstructorCreatesDefaultObjectUtilsServer()
    {
        $oServerNodeProcessor = new oxServerNodeProcessor();
        $this->assertInstanceOf('oxUtilsServer', $oServerNodeProcessor->UNITgetUtilsServer());
    }

    public function testConstructorCreatesDefaultObjectUtilsDate()
    {
        $oServerNodeProcessor = new oxServerNodeProcessor();
        $this->assertInstanceOf('oxUtilsDate', $oServerNodeProcessor->UNITgetUtilsDate());
    }

    public function testNodeInformationNotUpdatedIfNotNeed0()
    {
        $oNode = $this->getMock('oxServerNode');

        $oServerNodesManager = $this->getMock('oxServerNodesManager');
        $oServerNodesManager->expects($this->any())->method('getNode')->will($this->returnValue($oNode));

        $oServerNodeChecker = $this->getMock('oxServerNodeChecker');
        // Test that check is called with object got from server node manager.
        $oServerNodeChecker->expects($this->any())->method('check')->with($oNode)->will($this->returnValue(false));

        $oUtilsServer = $this->getMock('oxUtilsServer');
        $oUtilsDate = $this->getMock('oxUtilsDate');

        $oServerNodesProcessor = new oxServerNodeProcessor($oServerNodesManager, $oServerNodeChecker, $oUtilsServer, $oUtilsDate);
        $oServerNodesProcessor->process();
    }

    public function testNodeInformationNotUpdatedIfNotNeed()
    {
        $oNode = $this->getMock('oxServerNode');

        $oServerNodesManager = $this->getMock('oxServerNodesManager');
        // Test that processor do not update node information if not needed.
        $oServerNodesManager->expects($this->never())->method('saveNode');
        $oServerNodesManager->expects($this->any())->method('getNode')->will($this->returnValue($oNode));

        $oServerNodeChecker = $this->getMock('oxServerNodeChecker');
        // Test that check is called with object got from server node manager.
        $oServerNodeChecker->expects($this->any())->method('check')->will($this->returnValue(true));

        $oUtilsServer = $this->getMock('oxUtilsServer');
        $oUtilsDate = $this->getMock('oxUtilsDate');

        $oServerNodesProcessor = new oxServerNodeProcessor($oServerNodesManager, $oServerNodeChecker, $oUtilsServer, $oUtilsDate);
        $oServerNodesProcessor->process();
    }

    public function providerNodeInformationUpdatedWhenNeed()
    {
        $sCurrentTime = '14000000000000';
        $sIP = '192.168.1.7';
        $sServerId = 'a45sdas5d4as564d56asd4';

        $oNode = new oxServerNode();
        $oNode->setId($sServerId);
        $oNode->setIp($sIP);
        $oNode->setTimestamp($sCurrentTime);

        $oNodeFrontend = clone($oNode);
        $oNodeFrontend->setLastFrontendUsage($sCurrentTime);

        $oNodeAdmin = clone($oNode);
        $oNodeAdmin->setLastAdminUsage($sCurrentTime);

        return array(
            array(false, $oNodeFrontend, $sServerId, $sCurrentTime, $sIP),
            array(true, $oNodeAdmin, $sServerId, $sCurrentTime, $sIP),
        );
    }

    /**
     * @param bool $blAdmin
     * @param oxServerNode $aNode
     * @param string $sCurrentTime
     * @param string $sIP
     *
     * @dataProvider providerNodeInformationUpdatedWhenNeed
     */
    public function testNodeInformationUpdatedWhenNeed($blAdmin, $oExpectedNode, $sServerId, $sCurrentTime, $sIP)
    {
        $this->setAdminMode($blAdmin);

        $oNode = new oxServerNode();
        $oServerNodesManager = $this->getMock('oxServerNodesManager');
        // Test that node manager was called with correct values.
        $oServerNodesManager->expects($this->atLeastOnce())->method('saveNode')->with($this->equalTo($oExpectedNode));
        $oServerNodesManager->expects($this->any())->method('getNode')->will($this->returnValue($oNode));

        $oServerNodeChecker = $this->getMock('oxServerNodeChecker');
        $oServerNodeChecker->expects($this->any())->method('check')->will($this->returnValue(false));

        $oUtilsServer = $this->getMock('oxUtilsServer');
        $oUtilsServer->expects($this->any())->method('getServerIp')->will($this->returnValue($sIP));
        $oUtilsServer->expects($this->any())->method('getServerNodeId')->will($this->returnValue($sServerId));

        $oUtilsDate = $this->getMock('oxUtilsDate');
        $oUtilsDate->expects($this->any())->method('getTime')->will($this->returnValue($sCurrentTime));

        $oServerNodesProcessor = new oxServerNodeProcessor($oServerNodesManager, $oServerNodeChecker, $oUtilsServer, $oUtilsDate);
        $oServerNodesProcessor->process();
    }
}