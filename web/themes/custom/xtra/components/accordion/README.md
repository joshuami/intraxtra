# Accordion

The accordion component is a simple way to show and hide content. Build vertically collapsing accordions in combination with our Collapse JavaScript plugin.

## Bootstrap Documentation

https://getbootstrap.com/docs/5.3/components/accordion/

## Usage

```twig
  {% include 'radix:accordion' with {
    title: 'Yolo!',
    open_item_id: 1,
    title_tag: 'h2',
    flush: true,
    items: [
      {
        title: 'Item 1',
        title_tag: 'h3',
        content: 'Content 1',
      },
      {
        title: 'Item 2',
        title_tag: 'h3',
        content: 'Content 2',
      },
      {
        title: 'Item 3',
        title_tag: 'h3',
        content: 'Content 3',
      },
    ],
  } %}
```
