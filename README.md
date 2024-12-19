# PHP Document 

A simple, lightweight, standalone PHP library for programmatically creating and manipulating HTML elements. It simplifies the process of working with HTML structures and DOM elements, offering functionality similar to [DOMDocument](https://www.php.net/manual/en/class.domdocument.php) but with reduced boilerplate and enhanced ease of use.

With PHPDocument, you can effortlessly create DOM nodes, set attributes, set innerHtml, use querySelector, modify element classlist etc, and generate (or render) HTML strings with ease. 

### Why PHP Document?

PHPDocument is designed to simplify and streamline the process of working with HTML elements in PHP. If (like me), you've ever been frustrated by the complexity of PHP's `DOMDocument` or found yourself writing repetitive, cumbersome code just to manipulate HTML structures, PHPDocument is the solution you’ve been waiting for.

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

### Prerequisite

- PHP >= 8.2

### Installation (Composer)

You can include PHPDocument library in your project using Composer:

```bash
composer require ucscode/uss-element
```

### Getting Started:

- Instantiate the PHPDocument class with the desired HTML element type (e.g., `NodeNameEnum::NODE_DIV`).
- Use PHPDocument methods to set attributes and content.
- Generate HTML strings with `NodeInterface::render()` for seamless integration into your web pages.

### Creating Elements

You can create elements by instantiating the type of node

```php
use Ucscode\PHPDocument\Node\ElementNode;

$element = new ElementNode('div');
```

If you prefer, you can use the `NodeNameEnum` enum

```php
use Ucscode\PHPDocument\Node\ElementNode;
use Ucscode\PHPDocument\Enums\NodeNameEnum;

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
  ->setAttribute('title', 'PHP Document')
;
```

### Traversing Elements

Use the `querySelector()` or `querySelectorAll()` method to select elements based on CSS selectors:

```php
$element->querySelector('.to.set[data-what=attributes]'); // Returns the <span> element
```

You can also retrieve an element by other methods such as `getElementById`, `getElementsByClassName`, `getElementsByTagName`:

```php
$element->getElementById('short-cut'); // Returns the <span> element
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
use Ucscode\PHPDocument\Parser\Translator\HtmlLoader;

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
use Ucscode\PHPDocument\Parser\Translator\HtmlLoader;

$html = <<< 'HERE'
  <h1>Hi there</h1>
  <p>Please enter your detail</p>
  <form name="my-form>
    <input name="username"/>
  </form>
HERE>>>

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
$divElement->setInnerHtml('<br/><h1 class="heading">New Heading</h1>');

// Query the first paragraph within the container
$paragraph = $divElement->querySelector('p'); // returns null
$heading = $divElement->querySelector('h1.heading'); // returns H1 ElementNode

// Accessing the number of direct child nodes
echo $divElement->getChildNodes()->count(); // 2
```

### Render HTML

You can get or render the `ElementNode` as HTML using the `render()` method.

```php
echo $divElement->render();
```

### Output

```html
<div class="container"><h1 class="heading">New Heading</h1></div>
```

If you want to indent the rendered output, pass an unsigned integer (initially zero) to the `render()` method

```php
echo $divElement->render(0);
```

### Output

```html
<div class="container">
    <h1 class="heading">
        New Heading
    </h1>
</div>
```

### Element Render Visibility

To keep an element in the DOM tree but exclude it from the rendered output, set its visibility to `false`.

```php
$divElement->querySelector('.heading')->setVisible(false);
```

```php
$divElement->render(); // <div class="container"></div>
```

```php
$divElement->getChildren()->first(); // H1 ElementNode
```

### Setting Void Item

Some HTML elements, like `<br>` and `<img>`, do not have closing tags.  

The `setVoid()` method marks an element as void, ensuring the renderer omits its closing tag. This is especially helpful when defining custom elements.

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
      <td>string</td>
    </tr>
    <tr>
      <td><code>getNodeType</code></td>
      <td>Return the node identifier</td>
      <td>integer</td>
    </tr>
    <tr>
      <td><code>setVisible</code></td>
      <td>Set the visibility status of a node</td>
      <td>static</td>
    </tr>
    <tr>
      <td><code>isVisible</code></td>
      <td>Verify the node visibility status</td>
      <td>boolean</td>
    </tr>
    <tr>
      <td><code>render</code></td>
      <td>Convert the node to string (OuterHtml)</td>
      <td>string</td>
    </tr>
    <tr>
      <td><code>getParentElement</code></td>
      <td>Returns an Element that is the parent of this node</td>
      <td>ElementInterface|null</td>
    </tr>
    <tr>
      <td><code>getParentNode</code></td>
      <td>Returns a Node that is the parent of this node</td>
      <td>NodeInterface|null</td>
    </tr>
    <tr>
      <td><code>getChildNodes</code></td>
      <td>Returns a NodeList containing all the children of this node</td>
      <td>NodeList</td>
    </tr>
    <tr>
      <td><code>appendChild</code></td>
      <td>Adds the specified childNode argument as the last child to the current node</td>
      <td>static</td>
    </tr>
    <tr>
      <td><code>prependChild</code></td>
      <td>Adds the specified childNode argument as the first child to the current node</td>
      <td>static</td>
    </tr>
    <tr>
      <td><code>getFirstChild</code></td>
      <td>Returns a Node representing the first direct child node of the node</td>
      <td>NodeInterface|null</td>
    </tr>
    <tr>
      <td><code>isFirstChild</code></td>
      <td>Verify that the specified node is first in the list</td>
      <td>boolean</td>
    </tr>
    <tr>
      <td><code>getLastChild</code></td>
      <td>Returns a Node representing the last direct child node of the node</td>
      <td>NodeInterface|null</td>
    </tr>
    <tr>
      <td><code>isLastChild</code></td>
      <td>Verify that the specified node is last in the list</td>
      <td>boolean</td>
    </tr>
    <tr>
      <td><code>getNextSibling</code></td>
      <td>Returns a Node representing the next node in the tree</td>
      <td>NodeInterface|null</td>
    </tr>
    <tr>
      <td><code>getPreviousSibling</code></td>
      <td>Returns a Node representing the previous node in the tree</td>
      <td>NodeInterface|null</td>
    </tr>
    <tr>
      <td><code>insertBefore</code></td>
      <td>Inserts a Node before the reference node as a child of a specified parent node</td>
      <td>static</td>
    </tr>
    <tr>
      <td><code>insertAfter</code></td>
      <td>Inserts a Node after the reference node as a child of a specified parent node</td>
      <td>static</td>
    </tr>
    <tr>
      <td><code>insertAdjacentNode</code></td>
      <td>Inserts a Node at a specific position relative to other child nodes</td>
      <td>static</td>
    </tr>
    <tr>
      <td><code>hasChild</code></td>
      <td>Verify that a node has the provided child node</td>
      <td>boolean</td>
    </tr>
    <tr>
      <td><code>getChild</code></td>
      <td>Get a child node from the Nodelist</td>
      <td>NodeInterface|null</td>
    </tr>
    <tr>
      <td><code>removeChild</code></td>
      <td>Removes a child node from the current element</td>
      <td>static</td>
    </tr>
    <tr>
      <td><code>replaceChild</code></td>
      <td>Replaces one child Node of the current one with the second one given in parameter</td>
      <td>static</td>
    </tr>
    <tr>
      <td><code>cloneNode</code></td>
      <td>Clone a Node, and optionally, all of its contents</td>
      <td>NodeInterface</td>
    </tr>
    <tr>
      <td><code>sortChildNodes</code></td>
      <td>Reorder the child nodes of a specified parent node</td>
      <td>static</td>
    </tr>
    <tr>
      <td><code>moveBefore</code></td>
      <td>Move the current node before a sibling node within the same parent node</td>
      <td>static</td>
    </tr>
    <tr>
      <td><code>moveAfter</code></td>
      <td>Move the current node after a sibling node within the same parent node</td>
      <td>static</td>
    </tr>
    <tr>
      <td><code>moveToFirst</code></td>
      <td>Move the current node to the first position of its relative sibling nodes</td>
      <td>static</td>
    </tr>
    <tr>
      <td><code>moveToLast</code></td>
      <td>Move the current node to the last position of its relative sibling nodes</td>
      <td>static</td>
    </tr>
    <tr>
      <td><code>moveToIndex</code></td>
      <td>Move the current node to a specific position within its sibling nodes</td>
      <td>static</td>
    </tr>
  </tbody>
</table>

## ElementInterface methods

All [NodeInterface methods](#nodeinterface-methods) and:

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
      <td>string</td>
    </tr>
    <tr>
      <td><code>setInnerHtml</code></td>
      <td>Set the inner HTML of the element</td>
      <td>static</td>
    </tr>
    <tr>
      <td><code>getInnerHtml</code></td>
      <td>Get the inner HTML of the element</td>
      <td>string</td>
    </tr>
    <tr>
      <td><code>setVoid</code></td>
      <td>Set whether the element is void (no closing tag)</td>
      <td>static</td>
    </tr>
    <tr>
      <td><code>isVoid</code></td>
      <td>Verify if the element is void, meaning it has no closing tag</td>
      <td>boolean</td>
    </tr>
    <tr>
      <td><code>getOpenTag</code></td>
      <td>Get the opening tag of the element</td>
      <td>string</td>
    </tr>
    <tr>
      <td><code>getCloseTag</code></td>
      <td>Get the closing tag of the element (if any)</td>
      <td>?string</td>
    </tr>
    <tr>
      <td><code>getChildren</code></td>
      <td>Get a collection of the element's children</td>
      <td>HtmlCollection</td>
    </tr>
    <tr>
      <td><code>getAttribute</code></td>
      <td>Get the value of a specific attribute by name</td>
      <td>?string</td>
    </tr>
    <tr>
      <td><code>getAttributes</code></td>
      <td>Get a collection of all attributes of the element</td>
      <td>Attributes</td>
    </tr>
    <tr>
      <td><code>getAttributeNames</code></td>
      <td>Get a list of all attribute names of the element</td>
      <td>array</td>
    </tr>
    <tr>
      <td><code>hasAttribute</code></td>
      <td>Check if the element has a specific attribute</td>
      <td>boolean</td>
    </tr>
    <tr>
      <td><code>hasAttributes</code></td>
      <td>Check if the element has any attributes</td>
      <td>boolean</td>
    </tr>
    <tr>
      <td><code>setAttribute</code></td>
      <td>Set the value of a specific attribute</td>
      <td>static</td>
    </tr>
    <tr>
      <td><code>removeAttribute</code></td>
      <td>Remove a specific attribute from the element</td>
      <td>static</td>
    </tr>
    <tr>
      <td><code>querySelector</code></td>
      <td>Find and return the first matching element by the given CSS selector</td>
      <td>?ElementInterface</td>
    </tr>
    <tr>
      <td><code>querySelectorAll</code></td>
      <td>Find and return all matching elements by the given CSS selector</td>
      <td>HtmlCollection</td>
    </tr>
    <tr>
      <td><code>getClassList</code></td>
      <td>Get the list of classes of the element</td>
      <td>ClassList</td>
    </tr>
    <tr>
      <td><code>matches</code></td>
      <td>Check if the element matches the given CSS selector</td>
      <td>boolean</td>
    </tr>
    <tr>
      <td><code>getElementsByClassName</code></td>
      <td>Get all elements with the specified class name</td>
      <td>HtmlCollection</td>
    </tr>
    <tr>
      <td><code>getElementsByTagName</code></td>
      <td>Get all elements with the specified tag name</td>
      <td>HtmlCollection</td>
    </tr>
  </tbody>
</table>

## TextNode methods

All [NodeInterface methods](#nodeinterface-methods) and:

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
      <td><code>isContentWhiteSpace</code></td>
      <td>Check if the content of the text node is empty or contains only whitespace</td>
      <td>boolean</td>
    </tr>
    <tr>
      <td><code>length</code></td>
      <td>The length of the text data</td>
      <td>integer</td>
    </tr>
  </tbody>
</table>

## Collection Objects  

- **Attributes**: Manages attributes of an element.  
- **ClassList**: Handles class names for an element.  
- **HtmlCollection**: A collection of `ElementNode` types.  
- **NodeList**: A collection of any node types.  

## Node Objects  

- **CommentNode**: Represents HTML comments.  
- **DocumentTypeNode**: Represents the `<!DOCTYPE>` declaration.  
- **ElementNode**: Represents HTML elements.  
- **TextNode**: Represents textual content.  

### Parser Objects  

- **HtmlLoader**: Parses an HTML string into nodes.  
- **Matcher**: Matches nodes against CSS selectors.  
- **Tokenizer**: Breaks down CSS selectors into tokens.  
- **Transformer**: Encodes and decodes CSS selectors.  
- **NodeSelector**: Finds descendants matching CSS selectors.  

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