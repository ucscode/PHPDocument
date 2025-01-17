# Uss Element 

A simple, lightweight, standalone PHP library for programmatically creating and manipulating HTML elements. It simplifies the process of working with HTML structures and DOM elements, offering functionality similar to [DOMDocument](https://www.php.net/manual/en/class.domdocument.php) but with reduced boilerplate and enhanced ease of use.

With UssElement, you can effortlessly create DOM nodes, set attributes, set innerHtml, use querySelector, modify element classlist etc, and generate (or render) HTML strings with ease. 

### Why Uss Element?

UssElement is designed to simplify and streamline the process of working with HTML elements in PHP. If (like me) you've ever been frustrated by the complexity of PHP's `DOMDocument` or found yourself writing repetitive, cumbersome code just to manipulate HTML structures, UssElement is the solution you’ve been waiting for.

This standalone library takes care of the heavy lifting, reducing boilerplate code and eliminates the need for complex XPath queries, offering a simple, intuitive API for tasks like creating elements, setting inner HTML, and selecting elements using CSS selectors.

The library is lightweight, fast, and easy to integrate into any project, making it perfect for both small and large-scale applications.

### Key Features:

- Create HTML elements using PHP code.
- More inspired by [Javascript DOM](https://developer.mozilla.org/en-US/docs/Web/API/Document_Object_Model) than PHP DOMDocument
- Set element attributes, such as class names and IDs.
- Define inner HTML content for elements.
- Generate or render HTML strings with the `NodeInterface::render()` method.
- Boost your productivity by simplifying HTML generation in PHP.
- Reducing Complexity
- Providing an Intuitive API
- Encouraging Dependency-Free Development
- Efficiency in Common DOM Tasks
- Flexibility and Extensibility
- Improving Developer Experience
- Encoding element to Json format
- Decoding element from json format

### Prerequisite

- PHP >= 8.2

### Installation (Composer)

You can include UssElement library in your project using Composer:

```bash
composer require ucscode/uss-element
```

### Getting Started:

- Instantiate the UssElement class with the desired HTML element type (e.g., `NodeNameEnum::NODE_DIV`).
- Use UssElement methods to set attributes and content.
- Generate HTML strings with `NodeInterface::render()` for seamless integration into your web pages.

### Creating Elements

You can create elements by instantiating the type of node

```php
use Ucscode\UssElement\Node\ElementNode;

$element = new ElementNode('div');
```

If you prefer, you can use the `NodeNameEnum` enum

```php
use Ucscode\UssElement\Node\ElementNode;
use Ucscode\UssElement\Enums\NodeNameEnum;

$element = new ElementNode(NodeNameEnum::NODE_DIV);
```

You can also create an element and set their attributes at the point of instantiation:

```php
$span = new ElementNode('span', [
  'id' => 'short-cut',
  'class' => 'to set',
  'data-what' => 'attributes'
]);
```

You can use many available methods to manipulate the DOM. 

A summary of these methods are provided in the following sections:

- [NodeInterface methods](#nodeinterface-methods)
- [ElementInterface methods](#elementinterface-methods)
- [TextNode methods](#textnode-methods)

```php
$element->appendChild($span);
```

```php
$element->getNextSibling();
```

```php
$element->getChild(0)
  ->setAttribute('data-name', 'Ucscode')
  ->setAttribute('title', 'Uss Element')
;
```

### Traversing Elements

Use the `querySelector()` or `querySelectorAll()` method to select elements based on CSS selectors:

```php
$element->querySelector('.to.set[data-what=attributes]'); // Returns the <span> element
```

You can also retrieve an element by other methods such as:

- `getElementsByClassName`
- `getElementsByTagName`

```php
$element->getElementsByClassName('.set'); // Returns the <span> element
```

### Inner HTML

- You can easily set the inner HTML content of an element using the `setInnerHtml()` method:
- You can also get inner HTML of an element using `getInnerHTML()` method:

```php
$element->setInnerHtml('<p>This is a paragraph inside a div.</p>');
```

### Loading HTML

You can convert an HTML string to `NodeList` containing all elements using the `HtmlLoader` class:

```php
use Ucscode\UssElement\Parser\Translator\HtmlLoader;

// An example HTML document:
$html = <<< 'HERE'
  <html>
    <head>
      <title>TEST</title>
    </head>
    <body id='foo'>
      <h1>Hello World</h1>
      <p>This is a test of the HTML5 parser.</p>
    </body>
  </html>
HERE;

$htmlLoader = new HtmlLoader($html);

$htmlLoader->getNodeList()->count(); // Returns the number of direct nodes (1 in this case)
$htmlLoader->getNodeList()->first; // HTML ElementNode
```

You can also load framents

```php
use Ucscode\UssElement\Parser\Translator\HtmlLoader;

$html = <<< 'HERE'
  <h1>Hi there</h1>
  <p>Please enter your detail</p>
  <form name="my-form>
    <input name="username"/>
  </form>
HERE;

$htmlLoader = new HtmlLoader($html);

$htmlLoader->getNodeList()->count(); // Returns the number of direct nodes (3 in this case)

$htmlLoader->getNodeList()->get(0); // H1 ElementNode
$htmlLoader->getNodeList()->get(1); // P ElementNode
$htmlLoader->getNodeList()->get(2); // FORM ElementNode
```
### Basic Example

```php
$html = '<div class="container"><p>Hello, world!</p></div>';
$htmlLoader = new HtmlLoader($html);

// Access the root div element
$divElement = $htmlLoader->getNodeList()->get(0);

// Set inner HTML of the root element
$divElement->setInnerHtml('<i class="fa-icon"></i><h1 class="heading">New Heading</h1><br/>');

// Query the first paragraph within the container
$paragraph = $divElement->querySelector('p'); // null
$heading = $divElement->querySelector('h1.heading'); // H1 ElementNode

// Accessing the number of direct child nodes
echo $divElement->getChildNodes()->count(); // 3
```

### Render HTML

You can get or render the `ElementNode` as HTML using the `render()` method.

```php
echo $divElement->render();
```

### Output

```html
<div class="container"><i class="fa-icon"></i><h1 class="heading">New Heading</h1><br/></div>
```

If you want to indent the rendered output, pass an unsigned integer (initially zero) to the `render()` method

```php
echo $divElement->render(0);
```

### Output

```html
<div class="container">
    <i class="fa-icon"></i>
    <h1 class="heading">
        New Heading
    </h1>
    <br/>
</div>
```

### Element Render Visibility

To keep an element in the DOM tree but exclude it from the rendered output, set its visibility to `false`.

```php
$divElement->querySelector('.heading')->setVisible(false);
```

```php
$divElement->render(0);
```

```html
<div class="container">
    <i class="fa-icon"></i>
    <br/>
</div>
```

```php
$divElement->getChildren()->count(); // 3
```

### Setting Void Item

Some HTML elements, like `<br>` and `<img>`, do not have closing tags.  

The `setVoid()` method marks an element as void, ensuring that it is rendered without a closing tag. This is especially helpful when defining custom elements.

```php
$element = new Element('x-widget', [
  ':vue-binder' => 'project'
]);
```

```php
$element->render(); // <x-widget :vue-binder="project"></x-widget>
```

```php
$element->setVoid(true);
```

```php
$element->render(); // <x-widget :vue-binder="project"/>
```
---

## Encoding and Decoding Nodes

This library provides methods for encoding a node into JSON format and decoding it back to its original structure.\
This is useful for transferring nodes between systems or storing them in a format that can be easily reconstructed.

### Encoding a Node

To encode a node into JSON format, the `toJson()` method is used.

```php
$node->toJson(); // Node to JSON Serialization
```

The `toJson()` method internally uses an instance of the `NodeJsonEncoder`, which is the actual encoder responsible for serializing the node.

```php
(new NodeJsonEncoder($node))->encode();
```

### Decoding a Node

To decode a JSON string back into a node, use the `NodeJsonDecoder` class.

```php
(new NodeJsonDecoder($json))->decode(); // JSON to Node Deserilization
```

The decoding process restores the full structure of the original node, including its attributes, child nodes, and content.

### Normalization

Both the `NodeJsonEncoder` and `NodeJsonDecoder` provide a `normalize` method to convert the input into an array.

```php
(new NodeJsonEncoder($node))->normalize(); // to array
(new NodeJsonDecoder($json))->normalize(); // to array
```

## NodeInterface methods

<table>
  <thead>
    <tr>
      <th>Method</th>
      <th>Description</th>
      <th>Returns</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><code>getNodeName</code></td>
      <td>Return the name of the current node</td>
      <td><code>string</code></td>
    </tr>
    <tr>
      <td><code>getNodeType</code></td>
      <td>Return the node identifier</td>
      <td><code>integer</code></td>
    </tr>
    <tr>
      <td><code>setVisible</code></td>
      <td>Set the visibility state of a node when rendered</td>
      <td><code>static</code></td>
    </tr>
    <tr>
      <td><code>isVisible</code></td>
      <td>Verify the visibility state of a node when rendered</td>
      <td><code>boolean</code></td>
    </tr>
    <tr>
      <td><code>render</code></td>
      <td>Convert the node to string (OuterHTML)</td>
      <td><code>string</code></td>
    </tr>
    <tr>
      <td><code>getParentElement</code></td>
      <td>Returns an Element that is the parent of the current node</td>
      <td><code>ElementInterface|null</code></td>
    </tr>
    <tr>
      <td><code>getParentNode</code></td>
      <td>Returns a Node that is the parent of the current node</td>
      <td><code>NodeInterface|null</code></td>
    </tr>
    <tr>
      <td><code>getChildNodes</code></td>
      <td>Returns a NodeList containing all the children of the current node</td>
      <td><code>NodeList</code></td>
    </tr>
    <tr>
      <td><code>clearChildNodes</code></td>
      <td>Remove all the child Nodes from the current element</td>
      <td><code>static</code></td>
    </tr>
    <tr>
      <td><code>appendChild</code></td>
      <td>Adds the specified Node argument as the last child to the current node</td>
      <td><code>static</code></td>
    </tr>
    <tr>
      <td><code>prependChild</code></td>
      <td>Adds the specified Node argument as the first child to the current node</td>
      <td><code>static</code></td>
    </tr>
    <tr>
      <td><code>getFirstChild</code></td>
      <td>Returns a Node representing the last direct child node of the current node</td>
      <td><code>NodeInterface|null</code></td>
    </tr>
    <tr>
      <td><code>getLastChild</code></td>
      <td>Returns a Node representing the last direct child node of the node</td>
      <td><code>NodeInterface|null</code></td>
    </tr>
    <tr>
      <td><code>getNextSibling</code></td>
      <td>Returns a Node representing the next node in the tree</td>
      <td><code>NodeInterface|null</code></td>
    </tr>
    <tr>
      <td><code>getPreviousSibling</code></td>
      <td>Returns a Node representing the previous node in the tree</td>
      <td><code>NodeInterface|null</code></td>
    </tr>
    <tr>
      <td><code>insertBefore</code></td>
      <td>Inserts a Node before the reference node as a child of a specified parent node</td>
      <td><code>static</code></td>
    </tr>
    <tr>
      <td><code>insertAfter</code></td>
      <td>Inserts a Node after the reference node as a child of a specified parent node</td>
      <td><code>static</code></td>
    </tr>
    <tr>
      <td><code>insertAdjacentNode</code></td>
      <td>Inserts a Node at a specific position relative to other child nodes</td>
      <td><code>static</code></td>
    </tr>
    <tr>
      <td><code>hasChild</code></td>
      <td>Verify that a node has the provided child node</td>
      <td><code>boolean</code></td>
    </tr>
    <tr>
      <td><code>getChild</code></td>
      <td>Get a child node from the Nodelist</td>
      <td><code>NodeInterface|null</code></td>
    </tr>
    <tr>
      <td><code>removeChild</code></td>
      <td>Removes a child node from the current element</td>
      <td><code>static</code></td>
    </tr>
    <tr>
      <td><code>replaceChild</code></td>
      <td>Replaces one child Node of the current one with the second one given in parameter</td>
      <td><code>static</code></td>
    </tr>
    <tr>
      <td><code>cloneNode</code></td>
      <td>Clone a Node, and optionally, all of its contents</td>
      <td><code>NodeInterface</code></td>
    </tr>
    <tr>
      <td><code>sortChildNodes</code></td>
      <td>Reorder the child nodes of a specified parent node</td>
      <td><code>static</code></td>
    </tr>
    <tr>
      <td><code>moveBefore</code></td>
      <td>Move the current node before a sibling node within the same parent node</td>
      <td><code>static</code></td>
    </tr>
    <tr>
      <td><code>moveAfter</code></td>
      <td>Move the current node after a sibling node within the same parent node</td>
      <td><code>static</code></td>
    </tr>
    <tr>
      <td><code>moveToFirst</code></td>
      <td>Move the current node to the first position of its relative sibling nodes</td>
      <td><code>static</code></td>
    </tr>
    <tr>
      <td><code>moveToLast</code></td>
      <td>Move the current node to the last position of its relative sibling nodes</td>
      <td><code>static</code></td>
    </tr>
    <tr>
      <td><code>moveToIndex</code></td>
      <td>Move the current node to a specific position within its sibling nodes</td>
      <td><code>static</code></td>
    </tr>
    <tr>
      <td><code>toJson</code></td>
      <td> Converts the node and its descendants into a JSON-encoded string</td>
      <td><code>string</code></td>
    </tr>
  </tbody>
</table>

## ElementInterface methods

Includes: 

- [NodeInterface methods](#nodeinterface-methods)

<table>
  <thead>
    <tr>
      <th>Method</th>
      <th>Description</th>
      <th>Returns</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><code>getTagName</code></td>
      <td>Return the tag name of the current element</td>
      <td><code>string</code></td>
    </tr>
    <tr>
      <td><code>setInnerHtml</code></td>
      <td>Set the inner HTML of the element</td>
      <td><code>static</code></td>
    </tr>
    <tr>
      <td><code>getInnerHtml</code></td>
      <td>Get the inner HTML of the element</td>
      <td><code>string</code></td>
    </tr>
    <tr>
      <td><code>setVoid</code></td>
      <td>Set whether the element is void (no closing tag)</td>
      <td><code>static</code></td>
    </tr>
    <tr>
      <td><code>isVoid</code></td>
      <td>Verify if the element is void, meaning it has no closing tag</td>
      <td><code>boolean</code></td>
    </tr>
    <tr>
      <td><code>getOpenTag</code></td>
      <td>Get the opening tag of the element</td>
      <td><code>string</code></td>
    </tr>
    <tr>
      <td><code>getCloseTag</code></td>
      <td>Get the closing tag of the element (if any)</td>
      <td><code>string|null</code></td>
    </tr>
    <tr>
      <td><code>getChildren</code></td>
      <td>Get a collection of the element's children</td>
      <td><code>ElementList</code></td>
    </tr>
    <tr>
      <td><code>getAttribute</code></td>
      <td>Get the value of a specific attribute by name</td>
      <td><code>string|null</code></td>
    </tr>
    <tr>
      <td><code>getAttributes</code></td>
      <td>Get a collection of all attributes of the element</td>
      <td><code>Attributes</code></td>
    </tr>
    <tr>
      <td><code>getAttributeNames</code></td>
      <td>Get a list of all attribute names of the element</td>
      <td><code>array</code></td>
    </tr>
    <tr>
      <td><code>hasAttribute</code></td>
      <td>Check if the element has a specific attribute</td>
      <td><code>boolean</code></td>
    </tr>
    <tr>
      <td><code>hasAttributes</code></td>
      <td>Check if the element has any attributes</td>
      <td><code>boolean</code></td>
    </tr>
    <tr>
      <td><code>setAttribute</code></td>
      <td>Set the value of a specific attribute</td>
      <td><code>static</code></td>
    </tr>
    <tr>
      <td><code>removeAttribute</code></td>
      <td>Remove a specific attribute from the element</td>
      <td><code>static</code></td>
    </tr>
    <tr>
      <td><code>querySelector</code></td>
      <td>Find and return the first matching element by the given CSS selector</td>
      <td><code>ElementInterface|null</code></td>
    </tr>
    <tr>
      <td><code>querySelectorAll</code></td>
      <td>Find and return all matching elements by the given CSS selector</td>
      <td><code>ElementList</code></td>
    </tr>
    <tr>
      <td><code>getClassList</code></td>
      <td>Get a collection of classes of the element</td>
      <td><code>ClassList</code></td>
    </tr>
    <tr>
      <td><code>matches</code></td>
      <td>Check if the current element matches the given CSS selector</td>
      <td><code>boolean</code></td>
    </tr>
    <tr>
      <td><code>getElementsByClassName</code></td>
      <td>Get all elements with the specified class name</td>
      <td><code>ElementList</code></td>
    </tr>
    <tr>
      <td><code>getElementsByTagName</code></td>
      <td>Get all elements with the specified tag name</td>
      <td><code>ElementList</code></td>
    </tr>
  </tbody>
</table>

## TextNode methods

Includes: 

- [NodeInterface methods](#nodeinterface-methods)

<table>
  <thead>
    <tr>
      <th>Method</th>
      <th>Description</th>
      <th>Returns</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><code>getData</code></td>
      <td>Get the text data</td>
      <td><code>string</code></td>
    </tr>
    <tr>
      <td><code>length</code></td>
      <td>The length of the text data</td>
      <td><code>integer</code></td>
    </tr>
    <tr>
      <td><code>isContentWhiteSpace</code></td>
      <td>Check if the content of the text node is empty or contains only whitespace</td>
      <td><code>boolean</code></td>
    </tr>
  </tbody>
</table>

## Collection Objects

<table>
  <thead>
    <tr>
      <th>PHP Class</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><code>Attributes</code></td>
      <td>Manages attributes of an element.</td>
    </tr>
    <tr>
      <td><code>ClassList</code></td>
      <td>Handles class names for an element.</td>
    </tr>
    <tr>
      <td><code>ElementList</code></td>
      <td>A collection of <code>ElementInterface</code> types.</td>
    </tr>
    <tr>
      <td><code>NodeList</code></td>
      <td>A collection of any node types.</td>
    </tr>
  </tbody>
</table>

## Node Objects

<table>
  <thead>
    <tr>
      <th>PHP Class</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><code>CommentNode</code></td>
      <td>Represents HTML comments.</td>
    </tr>
    <tr>
      <td><code>DocumentTypeNode</code></td>
      <td>Represents the <code>&lt;!DOCTYPE&gt;</code> declaration.</td>
    </tr>
    <tr>
      <td><code>ElementNode</code></td>
      <td>Represents HTML elements.</td>
    </tr>
    <tr>
      <td><code>TextNode</code></td>
      <td>Represents textual content.</td>
    </tr>
  </tbody>
</table>

## Parser Objects

<table>
  <thead>
    <tr>
      <th>PHP Class</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><code>HtmlLoader</code></td>
      <td>Parses an HTML string into nodes.</td>
    </tr>
    <tr>
      <td><code>Matcher</code></td>
      <td>Matches nodes against CSS selectors.</td>
    </tr>
    <tr>
      <td><code>Tokenizer</code></td>
      <td>Breaks down CSS selectors into tokens.</td>
    </tr>
    <tr>
      <td><code>Transformer</code></td>
      <td>Encodes and decodes CSS selectors.</td>
    </tr>
    <tr>
      <td><code>NodeSelector</code></td>
      <td>Finds descendants matching CSS selectors.</td>
    </tr>
    <tr>
      <td><code>NodeJsonEncoder</code></td>
      <td>Encodes a node and its descendants into JSON format</td>
    </tr>
    <tr>
      <td><code>NodeJsonDecoder</code></td>
      <td>Decodes a node JSON back into a node instance.</td>
    </tr>
  </tbody>
</table>


## Providing Support For:

- **Combinators**: Use of combinator such as `>`, `+`, `~`, `$` are captured but not yet supported

## Contributing

Feel free to open issues or submit pull requests. We welcome contributions to improve the library.

### How to Contribute

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-xyz`).
3. Commit your changes (`git commit -am 'Add feature xyz'`).
4. Push to the branch (`git push origin feature-xyz`).
5. Create a new pull request.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.