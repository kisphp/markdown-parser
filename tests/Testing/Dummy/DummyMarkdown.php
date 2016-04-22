<?php

namespace Kisphp\Testing\Dummy;

use Kisphp\Markdown;

class DummyMarkdown extends Markdown
{
    const BLOCK_DUMMY = 'BlockDummy';

    protected function createRules()
    {
        parent::createRules();

        $this->factory
            ->addBlockPlugin('^', self::BLOCK_DUMMY)
        ;
    }

    /**
     * @return \Kisphp\MarkdownFactoryInterface
     */
    public function getFactory()
    {
        return $this->factory;
    }
}
