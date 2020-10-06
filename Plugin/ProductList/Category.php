<?php

declare(strict_types=1);

namespace Hunters\Bazaarvoice\Plugin\ProductList;

use Hunters\Bazaarvoice\Model\Source\ProductList;

/**
 * Class Category
 *
 * @package Hunters\Bazaarvoice\Plugin\ProductList
 */
class Category extends Item
{
    /**
     * @var string
     */
    protected $type = ProductList::CATEGORY;
}
