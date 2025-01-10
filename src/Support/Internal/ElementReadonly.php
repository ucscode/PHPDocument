<?php

namespace Ucscode\UssElement\Support\Internal;

use Ucscode\UssElement\Collection\ElementList;
use Ucscode\UssElement\Contracts\ElementInterface;
use Ucscode\UssElement\Contracts\NodeInterface;
use Ucscode\UssElement\Enums\NodeTypeEnum;

class ElementReadonly extends NodeReadonly
{
    public function getChildren(): ElementList
    {
        $filter = array_filter(
            $this->nodeList->toArray(),
            fn (NodeInterface $node) => $node->nodeType === NodeTypeEnum::NODE_ELEMENT->value
        );

        return new ElementList($filter);
    }

    public function getFirstElementChild(): ?ElementInterface
    {
        return $this->getChildren()->first();
    }

    public function getLastElementChild(): ?ElementInterface
    {
        return $this->getChildren()->last();
    }

    public function getNextElementSibling($node): ?ElementInterface
    {
        return $this->getElementSibling($node, 1);
    }

    public function getPreviousElementSibling($node): ?ElementInterface
    {
        return $this->getElementSibling($node, -1);
    }    
    
    /**
    * @param NodeInterface $node    The node whose sibling should be gotten
    * @param int $index  The index of sibling (next = 1, previous = -1)
    * @return NodeInterface|null
    */
   protected function getElementSibling(NodeInterface $node, int $index): ?ElementInterface
   {
       if ($this->parentElement) {
           $siblings = $this->parentElement->children;

           if (false !== $key = $siblings->indexOf($node)) {
               return $siblings->get($key + $index);
           }
       }

       return null;
   }
}