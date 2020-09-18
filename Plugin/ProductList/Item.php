<?php
/**
 * Copyright © Bazaarvoice, Inc. All rights reserved.
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Hunters\Bazaarvoice\Plugin\ProductList;

/**
 * Class Item
 *
 * @package Hunters\Bazaarvoice\Plugin\ProductList
 */
class Item
{
    /* @var \Magento\Catalog\Api\Data\ProductInterface|\Magento\Catalog\Model\Product */
    protected $product;
    /**
     * @var string
     */
    protected $type;


    public function __construct(
        \Magento\Framework\Registry $registry,
        \Hunters\Bazaarvoice\Model\PostFactory $postFactory
    ) {
        $this->_postFactory = $postFactory;
        $this->_registry = $registry;
    }

    /**
     * @param $subject
     * @param $product
     */
    public function beforeGetProductPrice(
        /** @noinspection PhpUnusedParameterInspection */
        // @codingStandardsIgnoreLine Squiz.Functions.MultiLineFunctionDeclaration.FirstParamSpacing
        $subject,
        $product
    ) {
            $this->product = $product;
    }

    /**
     * @param $subject
     * @param $result
     *
     * @return string
     */
    public function afterGetProductPrice(
        /** @noinspection PhpUnusedParameterInspection */
        // @codingStandardsIgnoreLine Squiz.Functions.MultiLineFunctionDeclaration.FirstParamSpacing
        $subject,
        $result
    ) {
            $sku = $this->product->getSku();

            $sku_array = array(
                'FIBERS-CA' => 'FIBERS',
                'BBF-ca' => 'BBF',
                '20504231' => 'FHS4B',
                '20080136' => 'TTS',
                '20080131' => 'TSA4C',
                '20080130' => 'OPTE4',
                '20080164' => 'PKIT20010',
                'STR-ca' => 'STR'
            );

            $sku = isset($sku_array[$sku]) ? $sku_array[$sku] : $sku;
            $productUrl = $this->product->getProductUrl();
            $post = $this->_postFactory->create();
            $collection = $post->getCollection();
            $rating = $collection->addFieldToFilter('sku', $sku)->addFieldToFilter('type_text', 'product')->getData();
            if (!empty($rating[0]['statistic'])) {
                $statistic = json_decode($rating[0]['statistic'], true);
            }
            if (!empty($statistic['AverageOverallRating'])){
              $rat =  round($statistic['AverageOverallRating'], 1) * 20;
            }
            else {
              $rat = 0;
            }
            $result = '<div data-bv-show="rating_summary" data-bv-ready="true">
                <div class="bv-cleanslate bv-cv2-cleanslate bv_main_container">
                  <div class="bv-core-container-103">
                    <span class="bv-rating-stars-container">
                      <span class="bv-rating-stars bv-rating-stars-off" aria-hidden="true"> ★★★★★ </span>
                      <span class="bv-rating-stars bv-rating-stars-on bv-width-from-rating-stats-' . $rat . '" aria-hidden="true"> ★★★★★ </span>
                    </span>
                  </div>
                </div>
              </div>' . $result;
        return $result;
    }
}
