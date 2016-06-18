<?php

namespace Kisphp;

abstract class BlockTypes
{
    const BLOCK_CONTINUE = 'BlockContinue';

    const BLOCK_EMPTY = 'BlockEmpty';
    const BLOCK_PARAGRAPH = 'BlockParagraph';
    const BLOCK_HEADER = 'BlockHeader';
    const BLOCK_HEADER_ONE = 'BlockHeaderOne';
    const BLOCK_HEADER_TWO = 'BlockHeaderTwo';
    const BLOCK_HORIZONTAL_RULE = 'BlockHorizontalRule';
    const BLOCK_QUOTE = 'BlockQuote';
    const BLOCK_CODE = 'BlockCode';
    const BLOCK_SPACES_CODE = 'BlockSpacesCode';
    const BLOCK_LIST = 'BlockList';
    const BLOCK_IMAGE = 'BlockImage';
    const BLOCK_URLS = 'BlockUrls';
    const BLOCK_TABLE = 'BlockTable';
    const BLOCK_CONTENT_BLOCKS = 'BlockContentTemplate';

    const BLOCK_STRONG_ITALIC = 'BlockStrongItalic';
    const BLOCK_STRIKETHROUGH = 'BlockStrikethrough';
    const BLOCK_STRONG = 'BlockStrong';
    const BLOCK_EMPHASIS = 'BlockEmphasis';
    const BLOCK_INLINE_CODE = 'BlockInlineCode';
    const BLOCK_CODE_INJECTOR = 'BlockCodeInjector';
    const BLOCK_NEW_LINES = 'BlockNewLines';

    const BLOCK_SKIP = 'BlockSkip';
    const BLOCK_UNCHANGE = 'BlockUnchange';
}
