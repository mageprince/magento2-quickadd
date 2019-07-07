<?php

/**
 * MagePrince
 * Copyright (C) 2018 Mageprince
 *
 * NOTICE OF LICENSE
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see http://opensource.org/licenses/gpl-3.0.html
 *
 * @category MagePrince
 * @package Prince_Productattach
 * @copyright Copyright (c) 2018 MagePrince
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author MagePrince
 */

namespace Mageprince\QuickAdd\Controller\Index;

use Magento\Framework\App\Action\Context;

class Getallsku extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_resultJson;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * Getallsku constructor.
     * @param Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJson
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJson,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
    ) {
        parent::__construct($context);
        $this->_resultJson = $resultJson;
        $this->_productCollectionFactory = $productCollectionFactory;
    }

    public function execute()
    {
        if ($this->getRequest()->isAjax()) {
            $query = $this->getRequest()->getParam('q', false);
            $response['items'] = [];
            $collection = $this->_productCollectionFactory->create();
            $collection->addAttributeToFilter('sku', ['like' => '%'.$query.'%']);
            $collection->setPageSize(20);
            foreach ($collection as $product) {
                if($product->isSaleable()) {
                    $response['items'][]['id'] = $product->getSku();
                }
            }
            $resultJson = $this->_resultJson->create();
            return $resultJson->setData($response);
        }
    }
}
