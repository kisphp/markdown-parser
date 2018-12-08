<?php

use Kisphp\BlockTypes;
use Kisphp\MarkdownFactory;
use PHPUnit\Framework\TestCase;

class BlockFactoryTest extends TestCase
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

    /**
     * @group Dummy
     */
    public function test_AddedCustomBlock()
    {
        $md = \Kisphp\Testing\Dummy\DummyFactory::createMarkdown();

        $this->assertSame('<span>custom block</span>', $md->parse('^ custom block'));
    }

    /**
     * @expectedException Kisphp\Exceptions\ParameterNotAllowedException
     */
    public function test_WrongPlugin()
    {
        $md = \Kisphp\Testing\Dummy\DummyFactory::createMarkdown();

        $factory = $md->getFactory();

        $factory->addBlockPlugin('^', new stdClass());
    }

    public function test_AddSameBlockType()
    {
        $md = \Kisphp\Testing\Dummy\DummyFactory::createMarkdown();

        $factory = $md->getFactory();
        $factory->addBlockPlugin('^', 'BlockDummy');
        $factory->addBlockPlugins('^', ['BlockDummy', 'BlockParagraph']);

        $this->assertEquals(2, count($factory->getBlockPlugins()['^']));
    }

    public function test_AddPluginsFromMarkdown()
    {
        $md = \Kisphp\Testing\Dummy\DummyFactory::createMarkdown();
        $md->addRules('^', ['BlockDummy', 'BlockParagraph']);

        $this->assertEquals(2, count($md->getFactory()->getBlockPlugins()['^']));
    }
}
