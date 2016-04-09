<?php


class BlockFactoryTest extends PHPUnit_Framework_TestCase
{
    public function _test_create()
    {
        $bf = new \Kisphp\BlockFactory();

        $this->assertInstanceOf(
            \Kisphp\Blocks\BlockCode::class,
            $bf->create(\Kisphp\Markdown::TYPE_CODE)
        );
    }

    /**
     * @expectedException \Kisphp\Exceptions\BlockNotFoundException
     */
    public function test_create_no_existing_block()
    {
        $bf = new \Kisphp\BlockFactory();
        $bf->create('Alfa');
    }

    public function test_BlockParagraph()
    {
        $this->assertInstanceOf(
            \Kisphp\Blocks\Paragraph\BlockParagraph::class,
            \Kisphp\BlockFactory::create(\Kisphp\BlockTypes::BLOCK_PARAGRAPH)
        );
    }
}
