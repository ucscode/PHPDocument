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

You can also create an eleemnt and set their attributes like so:

```php
$span = new ElementNode('span', [
  'id' => 'short-cut',
  'class' => 'to set',
  'data-what' => 'attributes'
]);
```

You can use almost any existing node methods

```php
$element->appendChild($span);

$element->getNextSibling();

$element->getChild(0)->setAttribute('data-name', 'Ucscode');
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
$rootElement = $htmlLoader->getNodeList()->get(0);

// Set inner HTML of the root element
$rootElement->setInnerHtml('<h1 class="heading">New Heading</h1>');

// Query the first paragraph within the container
$paragraph = $rootElement->querySelector('p'); // returns null
$paragraph = $rootElement->querySelector('h1.heading'); // returns H1 ElementNode

// Accessing the number of direct child nodes
echo $rootElement->getChildNodes()->count(); // 1 (the container div)
```

### Render HTML

You can get or render the `ElementNode` as HTML using the `render()` method.

```php
echo $rootElement->render();
```

#### Result

```html
<div class="container"><h1 class="heading">New Heading</h1></div>
```

If you want to indent the rendered output, pass an unsigned integer (initially zero) to the `render()` method

```php
echo $rootElement->render(0);
```

#### Result

```html
<div class="container">
    <h1 class="heading">
        New Heading
    </h1>
</div>
```

### Element Render Visibility

If you want an element not to be removed from the DOM tree but not part of the rendered output, set the element visibility to false

```php
$rootElement->querySelector('.heading')->setVisible(false);

$rootElement->render(); // <div class="container"></div>

$rootElement->getChildren()->count(); // 1
```

### Setting Void Item

Some HTML elements do not have closing tags, examples include `br`, `img` etc.

You can use the `setVoid()` method to indentify an element as void.\
This allows the rendering machine to discard closing tag for that element
This is useful when defining custom object

```php
$element = new Element('x-widget', [
  ':vue-binder' => 'project'
]);

$element->render(); // <x-widget :vue-binder="project"></x-widget>

$element->setVoid(true);

$element->render(); // <x-widget :vue-binder="project"/>
```
---

### Wiki Documentation Coming Soon

---

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