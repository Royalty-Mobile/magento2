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

namespace Magento\Customer\Test\Repository;

use Mtf\Repository\AbstractRepository;

/**
 * Class AddressInjectable
 * Customer address repository
 */
class AddressInjectable extends AbstractRepository
{
    /**
     * @param array $defaultConfig [optional]
     * @param array $defaultData [optional]
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __construct(array $defaultConfig = [], array $defaultData = [])
    {
        $this->_data['US_address'] = [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'John.Doe%isolation%@example.com',
            'company' => 'Magento %isolation%',
            'street' => '6161 West Centinela Avenue',
            'city' => 'Culver City',
            'region_id' => 'California',
            'postcode' => '90230',
            'country_id' => 'United States',
            'telephone' => '555-55-555-55',
            'default_billing' => 'Yes',
            'default_shipping' => 'Yes'
        ];

        $this->_data['US_address_default_billing'] = [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'John.Doe%isolation%@example.com',
            'company' => 'Magento %isolation%',
            'street' => '6161 West Centinela Avenue',
            'city' => 'Culver City',
            'region_id' => 'California',
            'postcode' => '90230',
            'country_id' => 'United States',
            'telephone' => '555-55-555-55',
            'default_billing' => 'Yes',
            'default_shipping' => 'No'
        ];

        $this->_data['US_address_default_shipping'] = [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'John.Doe%isolation%@example.com',
            'company' => 'Magento %isolation%',
            'street' => '6161 West Centinela Avenue',
            'city' => 'Culver City',
            'region_id' => 'California',
            'postcode' => '90230',
            'country_id' => 'United States',
            'telephone' => '555-55-555-55',
            'default_billing' => 'Yes',
            'default_shipping' => 'No'
        ];

        $this->_data['default_US_address'] = [
            'company' => 'Magento %isolation%',
            'street' => '6161 West Centinela Avenue',
            'city' => 'Culver City',
            'region_id' => 'California',
            'postcode' => '90230',
            'country_id' => 'United States',
            'telephone' => '555-55-555-55',
            'default_billing' => 'Yes',
            'default_shipping' => 'Yes',
        ];

        $this->_data['US_address_without_email'] = [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'company' => 'Magento %isolation%',
            'street' => '6161 West Centinela Avenue',
            'city' => 'Culver City',
            'region_id' => 'California',
            'postcode' => '90230',
            'country_id' => 'United States',
            'telephone' => '555-55-555-55',
        ];
    }
}
