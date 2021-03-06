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
namespace Magento\Downloadable\Block\Adminhtml\Catalog\Product\Edit\Tab\Downloadable;

/**
 * Adminhtml catalog product downloadable items tab links section
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Links extends \Magento\Backend\Block\Template
{
    /**
     * Block config data
     *
     * @var \Magento\Framework\Object
     */
    protected $_config;

    /**
     * Purchased Separately Attribute cache
     *
     * @var \Magento\Catalog\Model\Resource\Eav\Attribute
     */
    protected $_purchasedSeparatelyAttribute = null;

    /**
     * @var string
     */
    protected $_template = 'product/edit/downloadable/links.phtml';

    /**
     * Downloadable file
     *
     * @var \Magento\Downloadable\Helper\File
     */
    protected $_downloadableFile = null;

    /**
     * Core file storage database
     *
     * @var \Magento\Core\Helper\File\Storage\Database
     */
    protected $_coreFileStorageDb = null;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Eav\Model\Entity\AttributeFactory
     */
    protected $_attributeFactory;

    /**
     * @var \Magento\Downloadable\Model\Link
     */
    protected $_link;

    /**
     * @var \Magento\Backend\Model\Config\Source\Yesno
     */
    protected $_sourceModel;

    /**
     * @var \Magento\Backend\Model\UrlFactory
     */
    protected $_urlFactory;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $_jsonEncoder;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Core\Helper\File\Storage\Database $coreFileStorageDatabase
     * @param \Magento\Downloadable\Helper\File $downloadableFile
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Model\Config\Source\Yesno $sourceModel
     * @param \Magento\Downloadable\Model\Link $link
     * @param \Magento\Eav\Model\Entity\AttributeFactory $attributeFactory
     * @param \Magento\Backend\Model\UrlFactory $urlFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Core\Helper\File\Storage\Database $coreFileStorageDatabase,
        \Magento\Downloadable\Helper\File $downloadableFile,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Model\Config\Source\Yesno $sourceModel,
        \Magento\Downloadable\Model\Link $link,
        \Magento\Eav\Model\Entity\AttributeFactory $attributeFactory,
        \Magento\Backend\Model\UrlFactory $urlFactory,
        array $data = array()
    ) {
        $this->_jsonEncoder = $jsonEncoder;
        $this->_coreRegistry = $coreRegistry;
        $this->_coreFileStorageDb = $coreFileStorageDatabase;
        $this->_downloadableFile = $downloadableFile;
        $this->_sourceModel = $sourceModel;
        $this->_link = $link;
        $this->_attributeFactory = $attributeFactory;
        $this->_urlFactory = $urlFactory;
        parent::__construct($context, $data);
    }

    /**
     * Class constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setCanEditPrice(true);
        $this->setCanReadPrice(true);
    }

    /**
     * Get product that is being edited
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        return $this->_coreRegistry->registry('product');
    }

    /**
     * Retrieve Purchased Separately Attribute object
     *
     * @return \Magento\Catalog\Model\Resource\Eav\Attribute
     */
    public function getPurchasedSeparatelyAttribute()
    {
        if (is_null($this->_purchasedSeparatelyAttribute)) {
            $_attributeCode = 'links_purchased_separately';

            $this->_purchasedSeparatelyAttribute = $this->_attributeFactory->create()->loadByCode(
                \Magento\Catalog\Model\Product::ENTITY,
                $_attributeCode
            );
        }

        return $this->_purchasedSeparatelyAttribute;
    }

    /**
     * Retrieve Purchased Separately HTML select
     *
     * @return string
     */
    public function getPurchasedSeparatelySelect()
    {
        $select = $this->getLayout()->createBlock(
            'Magento\Framework\View\Element\Html\Select'
        )->setName(
            'product[links_purchased_separately]'
        )->setId(
            'downloadable_link_purchase_type'
        )->setOptions(
            $this->_sourceModel->toOptionArray()
        )->setValue(
            $this->getProduct()->getLinksPurchasedSeparately()
        );

        return $select->getHtml();
    }

    /**
     * Retrieve Add button HTML
     *
     * @return string
     */
    public function getAddButtonHtml()
    {
        $addButton = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            array(
                'label' => __('Add New Row'),
                'id' => 'add_link_item',
                'class' => 'add',
                'data_attribute' => array('action' => 'add-link')
            )
        );
        return $addButton->toHtml();
    }

    /**
     * Retrieve default links title
     *
     * @return string
     */
    public function getLinksTitle()
    {
        return $this->getProduct()->getId() &&
            $this->getProduct()->getTypeId() ==
            'downloadable' ? $this->getProduct()->getLinksTitle() : $this->_scopeConfig->getValue(
                \Magento\Downloadable\Model\Link::XML_PATH_LINKS_TITLE,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
    }

    /**
     * Check exists defined links title
     *
     * @return bool
     */
    public function getUsedDefault()
    {
        return $this->getProduct()->getAttributeDefaultValue('links_title') === false;
    }

    /**
     * Return true if price in website scope
     *
     * @return bool
     */
    public function getIsPriceWebsiteScope()
    {
        $scope = (int)$this->_scopeConfig->getValue(
            \Magento\Store\Model\Store::XML_PATH_PRICE_SCOPE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if ($scope == \Magento\Store\Model\Store::PRICE_SCOPE_WEBSITE) {
            return true;
        }
        return false;
    }

    /**
     * Return array of links
     *
     * @return array
     */
    public function getLinkData()
    {
        $linkArr = array();
        if ($this->getProduct()->getTypeId() !== \Magento\Downloadable\Model\Product\Type::TYPE_DOWNLOADABLE) {
            return $linkArr;
        }
        $links = $this->getProduct()->getTypeInstance()->getLinks($this->getProduct());
        $priceWebsiteScope = $this->getIsPriceWebsiteScope();
        $fileHelper = $this->_downloadableFile;
        foreach ($links as $item) {
            $tmpLinkItem = array(
                'link_id' => $item->getId(),
                'title' => $this->escapeHtml($item->getTitle()),
                'price' => $this->getCanReadPrice() ? $this->getPriceValue($item->getPrice()) : '',
                'number_of_downloads' => $item->getNumberOfDownloads(),
                'is_shareable' => $item->getIsShareable(),
                'link_url' => $item->getLinkUrl(),
                'link_type' => $item->getLinkType(),
                'sample_file' => $item->getSampleFile(),
                'sample_url' => $item->getSampleUrl(),
                'sample_type' => $item->getSampleType(),
                'sort_order' => $item->getSortOrder()
            );

            $linkFile = $item->getLinkFile();
            if ($linkFile) {
                $file = $fileHelper->getFilePath($this->_link->getBasePath(), $linkFile);

                $fileExist = $fileHelper->ensureFileInFilesystem($file);

                if ($fileExist) {
                    $name = '<a href="' . $this->getUrl(
                        'adminhtml/downloadable_product_edit/link',
                        array('id' => $item->getId(), '_secure' => true)
                    ) . '">' . $fileHelper->getFileFromPathFile(
                        $linkFile
                    ) . '</a>';
                    $tmpLinkItem['file_save'] = array(
                        array(
                            'file' => $linkFile,
                            'name' => $name,
                            'size' => $fileHelper->getFileSize($file),
                            'status' => 'old'
                        )
                    );
                }
            }

            $sampleFile = $item->getSampleFile();
            if ($sampleFile) {
                $file = $fileHelper->getFilePath($this->_link->getBaseSamplePath(), $sampleFile);

                $fileExist = $fileHelper->ensureFileInFilesystem($file);

                if ($fileExist) {
                    $tmpLinkItem['sample_file_save'] = array(
                        array(
                            'file' => $item->getSampleFile(),
                            'name' => $fileHelper->getFileFromPathFile($sampleFile),
                            'size' => $fileHelper->getFileSize($file),
                            'status' => 'old'
                        )
                    );
                }
            }

            if ($item->getNumberOfDownloads() == '0') {
                $tmpLinkItem['is_unlimited'] = ' checked="checked"';
            }
            if ($this->getProduct()->getStoreId() && $item->getStoreTitle()) {
                $tmpLinkItem['store_title'] = $item->getStoreTitle();
            }
            if ($this->getProduct()->getStoreId() && $priceWebsiteScope) {
                $tmpLinkItem['website_price'] = $item->getWebsitePrice();
            }
            $linkArr[] = new \Magento\Framework\Object($tmpLinkItem);
        }
        return $linkArr;
    }

    /**
     * Return formatted price with two digits after decimal point
     *
     * @param float $value
     * @return string
     */
    public function getPriceValue($value)
    {
        return number_format($value, 2, null, '');
    }

    /**
     * Retrieve max downloads value from config
     *
     * @return int
     */
    public function getConfigMaxDownloads()
    {
        return $this->_scopeConfig->getValue(
            \Magento\Downloadable\Model\Link::XML_PATH_DEFAULT_DOWNLOADS_NUMBER,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Prepare block Layout
     *
     * @return void
     */
    protected function _prepareLayout()
    {
        $this->addChild(
            'upload_button',
            'Magento\Backend\Block\Widget\Button',
            array(
                'id' => '',
                'label' => __('Upload Files'),
                'type' => 'button',
                'onclick' => 'Downloadable.massUploadByType(\'links\');Downloadable.massUploadByType(\'linkssample\')'
            )
        );
    }

    /**
     * Retrieve Upload button HTML
     *
     * @return string
     */
    public function getUploadButtonHtml()
    {
        return $this->getChildBlock('upload_button')->toHtml();
    }

    /**
     * Retrieve File Field Name
     *
     * @param string $type
     * @return string
     */
    public function getFileFieldName($type)
    {
        return $type;
    }

    /**
     * Retrieve Upload URL
     *
     * @param string $type
     * @return string
     */
    public function getUploadUrl($type)
    {
        return $this->_urlFactory->create()->addSessionParam()->getUrl(
            'adminhtml/downloadable_file/upload',
            array('type' => $type, '_secure' => true)
        );
    }

    /**
     * Retrieve config json
     *
     * @param string $type
     * @return string
     */
    public function getConfigJson($type = 'links')
    {
        $this->getConfig()->setUrl($this->getUploadUrl($type));
        $this->getConfig()->setParams(array('form_key' => $this->getFormKey()));
        $this->getConfig()->setFileField($this->getFileFieldName($type));
        $this->getConfig()->setFilters(array('all' => array('label' => __('All Files'), 'files' => array('*.*'))));
        $this->getConfig()->setReplaceBrowseWithRemove(true);
        $this->getConfig()->setWidth('32');
        $this->getConfig()->setHideUploadButton(true);
        return $this->_jsonEncoder->encode($this->getConfig()->getData());
    }

    /**
     * Retrieve config object
     *
     * @return \Magento\Framework\Object
     */
    public function getConfig()
    {
        if (is_null($this->_config)) {
            $this->_config = new \Magento\Framework\Object();
        }

        return $this->_config;
    }

    /**
     * @return bool
     */
    public function isSingleStoreMode()
    {
        return $this->_storeManager->isSingleStoreMode();
    }

    /**
     * @param null|string|bool|int|\Magento\Store\Model\Store $storeId $storeId
     * @return string
     */
    public function getBaseCurrencyCode($storeId)
    {
        return $this->_storeManager->getStore($storeId)->getBaseCurrencyCode();
    }
}
