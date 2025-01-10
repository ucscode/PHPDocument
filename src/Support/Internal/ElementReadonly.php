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
        return $this->getElementSibling(1, $node);
    }

    public function getPreviousElementSibling($node): ?ElementInterface
    {
        return $this->getElementSibling(-1, $node);
    }    
    
    /**
    * @param integer $index
    * @param NodeInterface $self
    * @return NodeInterface|null
    */
   protected function getElementSibling(int $index, NodeInterface $self): ?NodeInterface
   {
       if ($this->parentElement) {
           $parentElementList = $this->parentElement->children;

           if (false !== $key = $parentElementList->indexOf($self)) {
               return $parentElementList->get($key + $index);
           }
       }

       return null;
   }
}