<?php

namespace Ucscode\UssElement\Contracts;

use Ucscode\UssElement\Collection\ElementList;
use Ucscode\UssElement\Collection\NodeList;
use Ucscode\UssElement\Enums\NodeTypeEnum;

/**
 * The base interface for all nodes
 *
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
interface NodeInterface
{
    /**
     * Return the unique id of the target node
     *
     * @return integer
     */
    public function getNodeId(): int;

    /**
     * Return the name of the target node
     *
     * @return string
     */
    public function getNodeName(): string;

    /**
     * Return an enumuration instance representing the node type
     *
     * @return NodeTypeEnum
     */
    public function getNodeTypeEnum(): NodeTypeEnum;

    /**
     * Return the node type (identifier) of the target node
     *
     * @return integer
     */
    public function getNodeType(): int;

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
     * Returns an element that is the parent of the target node.
     *
     * If the node has no parent, or if that parent is not an Element, this method returns null.
     *
     * @return ElementInterface|null
     */
    public function getParentElement(): ?ElementInterface;

    /**
     * Returns a node that is the parent of the target node.
     *
     * If there is no such node, like if this node is the top of the tree or if doesn't participate in a tree, this method returns null.
     *
     * @return NodeInterface|null
     */
    public function getParentNode(): ?NodeInterface;

    /**
     * Returns a NodeList containing all the children of this node (including elements, text and comments).
     *
     * @return NodeList<int, ElementInterface|NodeInterface>
     */
    public function getChildNodes(): NodeList;

    /**
     * Remove all the child Nodes from the target element
     *
     * @return void
     */
    public function clearChildNodes(): void;

    /**
     * Adds the specified Node argument as the last child to the current node.
     *
     * @param NodeInterface $node
     * @return null|NodeInterface The appended child or null on failure
     */
    public function appendChild(NodeInterface $node): ?NodeInterface;

    /**
     * Adds the specified Node argument as the first child to the current node.
     *
     * @param NodeInterface $node
     * @return null|NodeInterface
     */
    public function prependChild(NodeInterface $node): ?NodeInterface;

    /**
     * Returns a Node representing the first direct child node of the current node, or null if the node has no child.
     *
     * @return NodeInterface|null
     */
    public function getFirstChild(): ?NodeInterface;

    /**
     * Returns a Node representing the last direct child node of the current node, or null if the node has no child.
     *
     * @return NodeInterface|null
     */
    public function getLastChild(): ?NodeInterface;
    
    /**
     * Returns a Node representing the next node in the tree, or null if there isn't such node.
     *
     * @return NodeInterface|null
     */
    public function getNextSibling(): ?NodeInterface;

    /**
     * Returns a Node representing the previous node in the tree, or null if there isn't such node.
     *
     * @return NodeInterface|null
     */
    public function getPreviousSibling(): ?NodeInterface;

    /**
     * Inserts a Node before the reference node as a child of a specified parent node.
     *
     * @param NodeInterface $newNode The node to be inserted
     * @param NodeInterface $childNode The node before which newNode is inserted. If this is null, then newNode will not be inserted
     * @return null|NodeInterface The new node that was inserted
     */
    public function insertBefore(NodeInterface $newNode, NodeInterface $childNode): ?NodeInterface;

    /**
     * Inserts a Node after the reference node as a child of a specified parent node.
     *
     * @param NodeInterface $newNode The node to be inserted
     * @param NodeInterface $childNode The node after which newNode is inserted. If this is null, then newNode will not be inserted
     * @return null|NodeInterface The new node that was inserted
     */
    public function insertAfter(NodeInterface $newNode, NodeInterface $childNode): ?NodeInterface;

    /**
     * Inserts a Node at a specific position relative to other child nodes of a specified parent node.
     *
     * @param integer $offset
     * @param NodeInterface $node
     * @return null|NodeInterface The inserted node or null on failure
     */
    public function insertChildAtPosition(int $offset, NodeInterface $node): ?NodeInterface;

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
     * @return ?NodeInterface the removed node or null if unsuccessful
     */
    public function removeChild(NodeInterface $node): ?NodeInterface;

    /**
     * Replaces one child Node of the current one with the second one given in parameter.
     *
     * @param NodeInterface $newNode The new node to replace oldChild.
     * @param NodeInterface $oldNode The child to be replaced.
     * @return ?NodeInterface The replaced node (old)
     */
    public function replaceChild(NodeInterface $newNode, NodeInterface $oldNode): ?NodeInterface;

    /**
     * Clone a Node, and optionally, all of its contents.
     *
     * By default, it does not clone the content of the node.
     *
     * @param boolean $deep
     * @return static
     */
    public function cloneNode(bool $deep): static;

    /**
     * Reorder the child nodes of a specified parent node
     *
     * @param callable $func
     * @return static
     */
    public function sortChildNodes(callable $func): static;

    /**
     * Check if the target node has a sibling node
     *
     * @param NodeInterface $siblingNode
     * @return boolean
     */
    public function hasSibling(NodeInterface $siblingNode): bool;

    /**
     * Move the target node before a sibling node within the same parent node.
     *
     * Movement will not occur if the specified sibling node does not share the same parent with the current node
     *
     * @param NodeInterface $siblingNode The reference node before which the target node is inserted
     * @return ?NodeInterface The sibling node or null if failed
     */
    public function moveBeforeSibling(NodeInterface $siblingNode): ?NodeInterface;

    /**
     * Move the target node before a sibling node within the same parent node.
     *
     * Movement will not occur if the specified sibling node does not share the same parent with the current node
     *
     * @param NodeInterface $siblingNode The reference node after which the target node is inserted
     * @return ?NodeInterface The sibling node or null if failed
     */
    public function moveAfterSibling(NodeInterface $siblingNode): ?NodeInterface;

    /**
     * Move the target node to the first position of the its relative sibling nodes
     *
     * @return ?NodeInterface The previous first sibling or null if movement failed
     */
    public function moveToFirstSibling(): ?NodeInterface;

    /**
     * Move the target node to the last position of its relative sibling nodes
     *
     * @return ?NodeInterface The previous last sibling or null if movement failed
     */
    public function moveToLastSibling(): ?NodeInterface;

    /**
     * Move the target node to a specific position within its sibling nodes
     *
     * @return ?NodeInterface The previous sibling in that position or null if failed
     */
    public function moveToSiblingPosition(int $index): ?NodeInterface;

    /**
     * Convert node to json enabling seemless data transfer
     *
     * @return string
     */
    public function toJson(): string;
    
    /**
     * Get a collection of all parent nodes of the target element
     *
     * @return NodeList<int, NodeInterface>
     */
    public function getParentNodes(): NodeList;
    
    /**
     * Get a collection of all parent elements of the target element
     *
     * @return ElementList<int, ElementInterface>
     */
   public function getParentElements(): ElementList;   
}
