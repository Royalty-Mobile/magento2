<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright   Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Magento\Framework\Stdlib\Cookie;

use Magento\TestFramework\Helper\ObjectManager;

/**
 * Test CookieScope
 *
 * @coversDefaultClass Magento\Framework\Stdlib\Cookie\CookieScope
 */
class CookieScopeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    private $defaultScopeParams;

    public function setUp()
    {
        $this->objectManager = new ObjectManager($this);
        $cookieMetadataFactory = $this
            ->getMockBuilder('Magento\Framework\Stdlib\Cookie\CookieMetadataFactory')
            ->disableOriginalConstructor()->getMock();
        $cookieMetadataFactory->expects($this->any())
            ->method('createSensitiveCookieMetadata')
            ->will($this->returnCallback([$this, 'createSensitiveMetadata']));
        $cookieMetadataFactory->expects($this->any())
            ->method('createPublicCookieMetadata')
            ->will($this->returnCallback([$this, 'createPublicMetadata']));
        $cookieMetadataFactory->expects($this->any())
            ->method('createCookieMetadata')
            ->will($this->returnCallback([$this, 'createCookieMetadata']));
        $this->defaultScopeParams = [
            'cookieMetadataFactory' => $cookieMetadataFactory
        ];
    }

    /**
     * @covers ::getSensitiveCookieMetadata
     */
    public function testGetSensitiveCookieMetadataEmpty()
    {
        $cookieScope = $this->createCookieScope();

        $this->assertEmpty($cookieScope->getSensitiveCookieMetadata()->__toArray());
    }

    /**
     * @covers ::getPublicCookieMetadata
     */
    public function testGetPublicCookieMetadataEmpty()
    {
        $cookieScope = $this->createCookieScope();

        $this->assertEmpty($cookieScope->getPublicCookieMetadata()->__toArray());
    }

    /**
     * @covers ::getCookieMetadata
     */
    public function testGetCookieMetadataEmpty()
    {
        $cookieScope = $this->createCookieScope();

        $this->assertEmpty($cookieScope->getPublicCookieMetadata()->__toArray());
    }

    /**
     * @covers ::createSensitiveMetadata ::getPublicCookieMetadata
     */
    public function testGetSensitiveCookieMetadataDefaults()
    {
        $defaultValues = [
            SensitiveCookieMetadata::KEY_PATH => 'default path',
            SensitiveCookieMetadata::KEY_DOMAIN => 'default domain',
        ];
        $sensitive = $this->createSensitiveMetadata($defaultValues);
        $cookieScope = $this->createCookieScope(
            [
                'sensitiveCookieMetadata' => $sensitive,
                'publicCookieMetadata' => null,
                'deleteCookieMetadata' => null
            ]
        );

        $this->assertEmpty($cookieScope->getPublicCookieMetadata()->__toArray());
        $this->assertEmpty($cookieScope->getCookieMetadata()->__toArray());
        $this->assertEquals($defaultValues, $cookieScope->getSensitiveCookieMetadata()->__toArray());
    }

    /**
     * @covers ::createSensitiveMetadata ::getPublicCookieMetadata ::getCookieMetadata
     */
    public function testGetPublicCookieMetadataDefaults()
    {
        $defaultValues = [
            PublicCookieMetadata::KEY_PATH => 'default path',
            PublicCookieMetadata::KEY_DOMAIN => 'default domain',
            PublicCookieMetadata::KEY_DURATION => 'default duration',
            PublicCookieMetadata::KEY_HTTP_ONLY => 'default http',
            PublicCookieMetadata::KEY_SECURE => 'default secure',
        ];
        $public = $this->createPublicMetadata($defaultValues);
        $cookieScope = $this->createCookieScope(
            [
                'sensitiveCookieMetadata' => null,
                'publicCookieMetadata' => $public,
                'deleteCookieMetadata' => null
            ]
        );

        $this->assertEmpty($cookieScope->getSensitiveCookieMetadata()->__toArray());
        $this->assertEmpty($cookieScope->getCookieMetadata()->__toArray());
        $this->assertEquals($defaultValues, $cookieScope->getPublicCookieMetadata()->__toArray());
    }

    /**
     * @covers ::createSensitiveMetadata ::getPublicCookieMetadata ::getCookieMetadata
     */
    public function testGetCookieMetadataDefaults()
    {
        $defaultValues = [
            CookieMetadata::KEY_PATH => 'default path',
            CookieMetadata::KEY_DOMAIN => 'default domain',
        ];
        $cookieMetadata = $this->createCookieMetadata($defaultValues);
        $cookieScope = $this->createCookieScope(
            [
                'sensitiveCookieMetadata' => null,
                'publicCookieMetadata' => null,
                'deleteCookieMetadata' => $cookieMetadata
            ]
        );

        $this->assertEquals($defaultValues, $cookieScope->getCookieMetadata()->__toArray());
    }

    /**
     * @covers ::createSensitiveMetadata ::getPublicCookieMetadata ::getCookieMetadata
     */
    public function testGetSensitiveCookieMetadataOverrides()
    {
        $defaultValues = [
            SensitiveCookieMetadata::KEY_PATH => 'default path',
            SensitiveCookieMetadata::KEY_DOMAIN => 'default domain',
        ];
        $overrideValues = [
            SensitiveCookieMetadata::KEY_PATH => 'override path',
            SensitiveCookieMetadata::KEY_DOMAIN => 'override domain',
        ];
        $sensitive = $this->createSensitiveMetadata($defaultValues);
        $cookieScope = $this->createCookieScope(
            [
                'sensitiveCookieMetadata' => $sensitive,
                'publicCookieMetadata' => null,
                'deleteCookieMetadata' => null
            ]
        );
        $override = $this->createSensitiveMetadata($overrideValues);

        $this->assertEmpty($cookieScope->getPublicCookieMetadata($this->createPublicMetadata())->__toArray());
        $this->assertEmpty($cookieScope->getCookieMetadata($this->createCookieMetadata())->__toArray());
        $this->assertEquals($overrideValues, $cookieScope->getSensitiveCookieMetadata($override)->__toArray());
    }

    /**
     * @covers ::createSensitiveMetadata ::getPublicCookieMetadata ::getCookieMetadata
     */
    public function testGetPublicCookieMetadataOverrides()
    {
        $defaultValues = [
            PublicCookieMetadata::KEY_PATH => 'default path',
            PublicCookieMetadata::KEY_DOMAIN => 'default domain',
            PublicCookieMetadata::KEY_DURATION => 'default duration',
            PublicCookieMetadata::KEY_HTTP_ONLY => 'default http',
            PublicCookieMetadata::KEY_SECURE => 'default secure',
        ];
        $overrideValues = [
            PublicCookieMetadata::KEY_PATH => 'override path',
            PublicCookieMetadata::KEY_DOMAIN => 'override domain',
            PublicCookieMetadata::KEY_DURATION => 'override duration',
            PublicCookieMetadata::KEY_HTTP_ONLY => 'override http',
            PublicCookieMetadata::KEY_SECURE => 'override secure',
        ];
        $public = $this->createPublicMetadata($defaultValues);
        $cookieScope = $this->createCookieScope(
            [
                'sensitiveCookieMetadata' => null,
                'publicCookieMetadata' => $public,
                'cookieMetadata' => null
            ]
        );
        $override = $this->createPublicMetadata($overrideValues);
        $this->assertEquals($overrideValues, $cookieScope->getPublicCookieMetadata($override)->__toArray());
    }

    /**
     * @covers ::createSensitiveMetadata ::getPublicCookieMetadata ::getCookieMetadata
     */
    public function testGetCookieMetadataOverrides()
    {
        $defaultValues = [
            CookieMetadata::KEY_PATH => 'default path',
            CookieMetadata::KEY_DOMAIN => 'default domain',
        ];
        $overrideValues = [
            CookieMetadata::KEY_PATH => 'override path',
            CookieMetadata::KEY_DOMAIN => 'override domain',
        ];
        $cookieMeta = $this->createCookieMetadata($defaultValues);
        $cookieScope = $this->createCookieScope(
            [
                'sensitiveCookieMetadata' => null,
                'publicCookieMetadata' => null,
                'deleteCookieMetadata' => $cookieMeta
            ]
        );
        $override = $this->createCookieMetadata($overrideValues);

        $this->assertEquals(
            [],
            $cookieScope->getSensitiveCookieMetadata($this->createSensitiveMetadata())->__toArray()
        );
        $this->assertEquals(
            [],
            $cookieScope->getPublicCookieMetadata($this->createPublicMetadata())->__toArray()
        );
        $this->assertEquals($overrideValues, $cookieScope->getCookieMetadata($override)->__toArray());
    }

    /**
     * Creates a CookieScope object with the given parameters.
     *
     * @param array $params
     * @return CookieScope
     */
    protected function createCookieScope($params = [])
    {
        $params = array_merge($this->defaultScopeParams, $params);
        return $this->objectManager->getObject('Magento\Framework\Stdlib\Cookie\CookieScope', $params);
    }

    /**
     * Creates a SensitiveCookieMetadata object with provided metadata values.
     *
     * @param array $metadata
     * @return SensitiveCookieMetadata
     */
    public function createSensitiveMetadata($metadata = [])
    {
        return $this->objectManager->getObject(
            'Magento\Framework\Stdlib\Cookie\SensitiveCookieMetadata',
            ['metadata' => $metadata]
        );
    }

    /**
     * Creates a PublicCookieMetadata object with provided metadata values.
     *
     * @param array $metadata
     * @return PublicCookieMetadata
     */
    public function createPublicMetadata($metadata = [])
    {
        return $this->objectManager->getObject(
            'Magento\Framework\Stdlib\Cookie\PublicCookieMetadata',
            ['metadata' => $metadata]
        );
    }

    /**
     * Creates a CookieMetadata object with provided metadata values.
     *
     * @param array $metadata
     * @return CookieMetadata
     */
    public function createCookieMetadata($metadata = [])
    {
        return $this->objectManager->getObject(
            'Magento\Framework\Stdlib\Cookie\CookieMetadata',
            ['metadata' => $metadata]
        );
    }
} 
