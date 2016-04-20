<?php


class BlockFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Kisphp\Exceptions\BlockNotFoundException
     */
    public function test_create_no_existing_block()
    {
        $bf = new \Kisphp\MarkdownFactory();
        $bf->create('Alfa');
    }

    public function test_BlockParagraph()
    {
        $factory = new \Kisphp\MarkdownFactory();

        $this->assertInstanceOf(
            \Kisphp\Blocks\Paragraph\BlockParagraph::class,
            $factory->create(\Kisphp\BlockTypes::BLOCK_PARAGRAPH)
        );
    }
}
