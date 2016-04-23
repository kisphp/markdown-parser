<?php

use Kisphp\BlockTypes;
use Kisphp\MarkdownFactory;

class BlockFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Kisphp\Exceptions\BlockNotFoundException
     */
    public function test_create_no_existing_block()
    {
        $bf = new MarkdownFactory();
        $bf->create('Alfa');
    }

    public function test_BlockParagraph()
    {
        $factory = new MarkdownFactory();

        $this->assertInstanceOf(
            $factory->getClassNamespace(BlockTypes::BLOCK_PARAGRAPH),
            $factory->create(BlockTypes::BLOCK_PARAGRAPH)
        );
    }
}
