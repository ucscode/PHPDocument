<?php

namespace Ucscode\UssElement\Contracts;

use Ucscode\UssElement\Collection\NodeList;

/**
 * The base interface for all nodes
 * 
 * @property NodeList $childNodes A NodeList collection containing all the child Nodes
 * @property ?NodeInterface $firstChild The first direct child node of the current node
 * @property ?NodeInterface $lastChild The last direct child node of the current node
 * @property ?NodeInterface $nextSibling The node after the current node
 * @property ?NodeInterface $previousSibling The node before the current node 
 * @property ?NodeInterface $parentNode The parent Node or null
 * @property ?ElementInterface $parentElement The parent Element or null.
 * @property string $nodeName The node name
 * @property int $nodeType An integer representing the node type
 * @property int $nodeId A unique id assigned to the node
 * 
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
interface NodeInterface
{
    /**
     * Set the visibility state of a node when rendered
     *
     * If the node visibility is set to `false`, it will not be attached as member of the node when converted to HTML
     *
     * @param boolean $visible
     * @return static
     */
    public function setVisible(bool $visible): static;

    /**
     * Verify the visibility state of a node when rendered
     *
     * @return boolean
     */
    public function isVisible(): bool;

    /**
     * Convert the node to string (OuterHtml)
     *
     * @return string
     */
    public function render(?int $indent = null): string;

    /**
     * Remove all the child Nodes from the current element
     *
     * @return static
     */
    public function clearChildNodes(): static;

    /**
     * Adds the specified Node argument as the last child to the current node.
     *
     * @param NodeInterface $node
     * @return static
     */
    public function appendChild(NodeInterface $node): static;

    /**
     * Adds the specified Node argument as the first child to the current node.
     *
     * @param NodeInterface $node
     * @return static
     */
    public function prependChild(NodeInterface $node): static;

    /**
     * Verify that the specified Node argument is first in the NodeList
     *
     * @param NodeInterface $node
     * @return boolean
     */
    public function isFirstChild(NodeInterface $node): bool;

    /**
     * Verify that the specified Node argument is last in the NodeList
     *
     * @param NodeInterface $node
     * @return boolean
     */
    public function isLastChild(NodeInterface $node): bool;

    /**
     * Inserts a Node before the reference node as a child of a specified parent node.
     *
     * @param NodeInterface $newNode The node to be inserted
     * @param NodeInterface $referenceNode The node before which newNode is inserted. If this is null, then newNode will not be inserted
     * @return static
     */
    public function insertBefore(NodeInterface $newNode, NodeInterface $referenceNode): static;

    /**
     * Inserts a Node after the reference node as a child of a specified parent node.
     *
     * @param NodeInterface $newNode The node to be inserted
     * @param NodeInterface $referenceNode The node after which newNode is inserted. If this is null, then newNode will not be inserted
     * @return static
     */
    public function insertAfter(NodeInterface $newNode, NodeInterface $referenceNode): static;

    /**
     * Inserts a Node at a specific position relative to other child nodes of a specified parent node.
     *
     * @param integer $offset
     * @param NodeInterface $node
     * @return static
     */
    public function insertAdjacentNode(int $offset, NodeInterface $node): static;

    /**
     * Verify that a node has the provided child node
     *
     * Similar to JavaScript's Node.contains()
     *
     * @param NodeInterface $node
     * @return boolean
     */
    public function hasChild(NodeInterface $node): bool;

    /**
     * Get a child node from the Nodelist
     *
     * @param integer $offset
     * @return NodeInterface|null
     */
    public function getChild(int $offset): ?NodeInterface;

    /**
     * Removes a child node from the current element, which must be a child of the current node.
     *
     * @param NodeInterface $node
     * @return static
     */
    public function removeChild(NodeInterface $node): static;

    /**
     * Replaces one child Node of the current one with the second one given in parameter.
     *
     * @param NodeInterface $newNode The new node to replace oldChild.
     * @param NodeInterface $oldNode The child to be replaced.
     * @return static
     */
    public function replaceChild(NodeInterface $newNode, NodeInterface $oldNode): static;

    /**
     * Clone a Node, and optionally, all of its contents.
     *
     * By default, it does not clone the content of the node.
     *
     * @param boolean $deep
     * @return NodeInterface
     */
    public function cloneNode(bool $deep): NodeInterface;

    /**
     * Reorder the child nodes of a specified parent node
     *
     * @param callable $func
     * @return static
     */
    public function sortChildNodes(callable $func): static;

    /**
     * Move the current node before a sibling node within the same parent node.
     *
     * Movement will not occur if the specified sibling node does not share the same parent with the current node
     *
     * @param NodeInterface $siblingNode The reference node before which the current node is inserted
     * @return static
     */
    public function moveBefore(NodeInterface $siblingNode): static;

    /**
     * Move the current node before a sibling node within the same parent node.
     *
     * Movement will not occur if the specified sibling node does not share the same parent with the current node
     *
     * @param NodeInterface $siblingNode The reference node after which the current node is inserted
     * @return static
     */
    public function moveAfter(NodeInterface $siblingNode): static;

    /**
     * Move the current node to the first position of the its relative sibling nodes
     *
     * @return static
     */
    public function moveToFirst(): static;

    /**
     * Move the current node to the last position of its relative sibling nodes
     *
     * @return static
     */
    public function moveToLast(): static;

    /**
     * Move the current node to a specific position within its sibling nodes
     *
     * @return static
     */
    public function moveToIndex(int $index): static;

    /**
     * Convert node to json enabling seemless data transfer
     *
     * @return string
     */
    public function toJson(): string;
}
