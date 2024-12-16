<?php

namespace Ucscode\PHPDocument\Enums;

enum NodeTypeEnum: int
{
    case ELEMENT_NODE = 1;
    case ATTRIBUTE_NODE = 2;
    case TEXT_NODE = 3;
    case CDATA_SECTION_NODE = 4;
    case PROCESSING_INSTRUCTION_NODE = 7;
    case COMMENT_NODE = 8;
    case DOCUMENT_NODE = 9;
    case DOCUMENT_TYPE_NODE = 10;
    case DOCUMENT_FRAGMENT_NODE = 11;
}
