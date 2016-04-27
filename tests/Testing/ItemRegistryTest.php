<?php

namespace Kisphp\Testing;

use Kisphp\Blocks\Lists\Tree\ItemInterface;
use Kisphp\Blocks\Lists\Tree\ItemsRegistry;
use Kisphp\Testing\Dummy\DummyItem;

class ItemRegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testItems()
    {
        $item = new DummyItem();
        $registy = new ItemsRegistry();
        $registy->addItem($item);
    }
}