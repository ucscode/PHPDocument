## NodeQuery($node, 'selector')

### Phase 1

- Quoted strings are encoded
- Attributes are encoded
- Selector groups are splited (by commad)
- Individual selector is splited (by space)

### Phase 2

- start from the depth and find all matching node
- traverse upward until the node reaches the inputed node.

Example:

```css
.name div#id [data-value] span
```

1. Find all elements matching `span`
2. check if the `span` element has parent `[data-value]`
3. check if the `[data-value]` has parent `div#id`
4. check if the `div#id` has parent `.name`

If any fails, discard `span` element, otherwise, add it to the collection list