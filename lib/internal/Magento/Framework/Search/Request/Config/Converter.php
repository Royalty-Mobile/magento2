<?php
/**
 * Search Request xml converter
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
namespace Magento\Framework\Search\Request\Config;

class Converter implements \Magento\Framework\Config\ConverterInterface
{
    /**
     * Convert config
     *
     * @param \DOMDocument $source
     * @return array
     */
    public function convert($source)
    {
        /** @var \DOMNodeList $requestNodes */
        $requestNodes = $source->getElementsByTagName('request');
        $requests = [];
        foreach ($requestNodes as $requestNode) {
            $simpleXmlNode = simplexml_import_dom($requestNode);
            /** @var \DOMElement $requestNode */
            $name = $requestNode->getAttribute('query');
            $request = $this->mergeAttributes((array)$simpleXmlNode);
            $request['queries'] = $this->convertNodes($simpleXmlNode->queries, 'name');
            $request['filters'] = $this->convertNodes($simpleXmlNode->filters, 'name');
            //$request['aggregation'] = $this->convertNodes($simpleXmlNode->aggregation, 'name');
            $requests[$name] = $request;
        }
        return $requests;
    }

    /**
     * Merge attributes in node data
     *
     * @param array $data
     * @return array
     */
    protected function mergeAttributes($data)
    {
        if (isset($data['@attributes'])) {
            $data = array_merge($data, $data['@attributes']);
            unset($data['@attributes']);
        }
        return $data;
    }

    /**
     * Deep converting simlexml element to array
     *
     * @param \SimpleXMLElement $node
     * @return array
     */
    protected function convertToArray(\SimpleXMLElement $node)
    {
        return $this->mergeAttributes(json_decode(json_encode($node), true));
    }

    /**
     * Convert nodes to array
     *
     * @param \SimpleXMLElement $nodes
     * @param string $name
     * @return array
     */
    protected function convertNodes(\SimpleXMLElement $nodes, $name)
    {
        $list = [];
        /** @var \SimpleXMLElement $node */
        foreach ($nodes->children() as $node) {
            $element = $this->convertToArray($node->attributes());
            if (count($node->children()) > 0) {
                foreach ($node->children() as $child) {
                    $element[$child->getName()][] = $this->convertToArray($child);
                }
            }
            $type = (string)$node->attributes('xsi', true)['type'];
            if (!empty($type)) {
                $element['type'] = $type;
            }

            $list[$element[$name]] = $element;
        }
        return $list;
    }
}
