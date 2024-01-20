<?php

namespace Ucscode\UssElement\Abstract;

abstract class AbstractUssElement extends AbstractUssElementChildCatalyst
{
    public function __debugInfo()
    {
        $debugInfo = [];
        $hiddenProperties = ['parentElement', 'voidTags'];
        foreach ($this as $property => $value) {
            if(!in_array($property, $hiddenProperties)) {
                $value = $property == 'children' ? count($value) : $value;
                $debugInfo[$property] = $value;
            }
        }
        $debugInfo['hasParent'] = !empty($this->parentElement);
        return $debugInfo;
    }

    /**
     * @ignore
     */
    protected function sanitizeAttributeContext(string $name): string
    {
        $name = str_replace(" ", '', $name);
        $this->attributes[$name] ??= [];
        return $name;
    }
}