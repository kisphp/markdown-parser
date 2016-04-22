<?php


class MarkdownTest extends PHPUnit_Framework_TestCase
{
    const DIRECTORY = '/../data/';

    /**
     * @param string $filename
     *
     * @return string
     */
    protected function getFileContent($filename)
    {
        $filePath = __DIR__ . self::DIRECTORY . '/' . $filename;
        if (!is_file($filePath)) {
            return '';
        }

        return file_get_contents($filePath);
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

    /**
     * @dataProvider dataProvider
     */
    public function test_Markup($fileMd, $fileHtml)
    {
        $codeMd = $this->getFileContent($fileMd);
        $codeHtml = $this->getFileContent($fileHtml);

        $md = \Kisphp\MarkdownFactory::createMarkdown();

        $this->assertSame(
            trim($codeHtml),
            trim($md->parse($codeMd))
        );
    }

    public function dataProvider()
    {
        $data = [];

        $finder = new \Symfony\Component\Finder\Finder();
        $finder->files()
            ->name('*.md')
            ->in(__DIR__ . self::DIRECTORY)
        ;

        foreach ($finder as $file) {
            $data[] = [
                $file->getFilename(),
                str_replace('.md', '.html', $file->getFilename()),
            ];
        }

        return $data;
    }
}
