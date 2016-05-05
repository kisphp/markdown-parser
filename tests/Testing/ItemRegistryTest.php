<?php

namespace Kisphp\Testing;

use Kisphp\Blocks\Lists\Tree\Builder;
use Kisphp\Blocks\Lists\Tree\ItemsRegistry;
use Kisphp\Testing\Dummy\DummyItem;

class ItemRegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testItems()
    {
        $item = new DummyItem(new Builder());
        $registy = new ItemsRegistry();
        $registy->addItem($item);

        $this->assertNull($registy->getItemById(10));
    }
}
