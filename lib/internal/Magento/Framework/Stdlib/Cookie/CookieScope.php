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

/**
 * CookieScope is used to store default scope metadata.
 */
class CookieScope
{
    /**
     * @var SensitiveCookieMetadata
     */
    private $sensitiveCookieMetadata;

    /**
     * @var PublicCookieMetadata
     */
    private $publicCookieMetadata;

    /**
     * @var CookieMetadata
     */
    private $cookieMetadata;

    /**
     * @var CookieMetadataFactory
     */
    private $cookieMetadataFactory;


    /**
     * @param CookieMetadataFactory $cookieMetadataFactory
     * @param SensitiveCookieMetadata $sensitiveCookieMetadata
     * @param PublicCookieMetadata $publicCookieMetadata
     * @param CookieMetadata $deleteCookieMetadata
     */
    public function __construct(
        CookieMetadataFactory $cookieMetadataFactory,
        SensitiveCookieMetadata $sensitiveCookieMetadata = null,
        PublicCookieMetadata $publicCookieMetadata = null,
        CookieMetadata $deleteCookieMetadata = null
    ) {
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->sensitiveCookieMetadata = $sensitiveCookieMetadata;
        $this->publicCookieMetadata = $publicCookieMetadata;
        $this->cookieMetadata = $deleteCookieMetadata;
    }

    /**
     * Merges the input override metadata with any defaults set on this Scope, and then returns a CookieMetadata
     * object representing the merged values.
     *
     * @param SensitiveCookieMetadata|null $override
     * @return SensitiveCookieMetadata
     */
    public function getSensitiveCookieMetadata(SensitiveCookieMetadata $override = null)
    {
        if (!is_null($this->sensitiveCookieMetadata)) {
            $merged = $this->sensitiveCookieMetadata->__toArray();
        } else {
            $merged = [];
        }
        if (!is_null($override)) {
            $merged = array_merge($merged, $override->__toArray());
        }

        return $this->cookieMetadataFactory->createSensitiveCookieMetadata($merged);
    }

    /**
     * Merges the input override metadata with any defaults set on this Scope, and then returns a CookieMetadata
     * object representing the merged values.
     *
     * @param PublicCookieMetadata|null $override
     * @return PublicCookieMetadata
     */
    public function getPublicCookieMetadata(PublicCookieMetadata $override = null)
    {
        if (!is_null($this->publicCookieMetadata)) {
            $merged = $this->publicCookieMetadata->__toArray();
        } else {
            $merged = [];
        }
        if (!is_null($override)) {
            $merged = array_merge($merged, $override->__toArray());
        }

        return $this->cookieMetadataFactory->createPublicCookieMetadata($merged);
    }

    /**
     * Merges the input override metadata with any defaults set on this Scope, and then returns a CookieMetadata
     * object representing the merged values.
     *
     * @param CookieMetadata|null $override
     * @return CookieMetadata
     */
    public function getCookieMetadata(CookieMetadata $override = null)
    {
        if (!is_null($this->cookieMetadata)) {
            $merged = $this->cookieMetadata->__toArray();
        } else {
            $merged = [];
        }
        if (!is_null($override)) {
            $merged = array_merge($merged, $override->__toArray());
        }

        return $this->cookieMetadataFactory->createCookieMetadata($merged);
    }
}
