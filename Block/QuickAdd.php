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

namespace Mageprince\QuickAdd\Block;

use Magento\Framework\View\Element\Template;

class QuickAdd extends \Magento\Framework\View\Element\Template
{

    const GETALL_SKU_URL = 'quickadd/index/getallsku';

    const ADD_TO_CART_URL = 'quickadd/index/addtocart';


    protected $imageHelper;

    protected $_productCollectionFactory;

    protected $helper;

    public function __construct(
        Template\Context $context,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Mageprince\QuickAdd\Helper\Data $helper,
        array $data = [])
    {
        $this->imageHelper = $imageHelper;
        $this->storeManager = $storeManager;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

    /**
     * Get contoller url
     *
     * @return string
     */
    public function getAllSkuUrl()
    {
        return $this->getUrl(self::GETALL_SKU_URL);
    }

    /**
     * Get contoller url
     *
     * @return string
     */
    public function getAddToCartUrl()
    {
        return $this->getUrl(self::ADD_TO_CART_URL);
    }

    public function getProductImage($product)
    {
        $imageSize = 50;
        $productImage = $this->imageHelper->init($product, 'product_thumbnail_image', ['height' => $imageSize , 'width'=> $imageSize])->getUrl();
        if (!$productImage) {
            return false;
        }
        return $productImage;
    }

    public function getProductCollection($query)
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('name');
        $collection->addAttributeToFilter('sku', ['like' => '%'.$query.'%']);
        $collection->addAttributeToSelect('thumbnail');
        $collection->setPageSize(20);
        return $collection;
    }

    public function getOptions($collection)
    {
        $html = '';
        foreach ($collection as $key => $product) {
            if($product->isSaleable()) {
                $productImage = $this->getProductImage($product);
                $productImage = "<img src='$productImage' />";

                $response['items'][] = array(
                    'id' => $product->getSku(),
                    'name' => $product->getName(),
                    'image' => $productImage
                );
            }
        }

        return $response;
    }
}