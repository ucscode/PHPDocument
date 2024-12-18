## NodeQuery($node, 'selector')

### Phase 1

- Quoted strings are encoded
- Attributes are encoded
- Selector groups are splited (by commad)
- Individual selector is splited (by space)

### Phase 2

- Each selector group contains an array of unit selector (fully encoded)
- Each unit selector represents a sibling or descendant
- The unit selectors are passed into the `Tokenizer` as is
