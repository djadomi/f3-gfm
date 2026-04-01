# GFM - GitHub-Flavoured Markdown Plugin for Fat-Free Framework

A modern markdown plugin for F3 that supports GitHub-Flavoured Markdown features using the league/commonmark library.

## Features

- **Tables** - Full table support with alignment
- **Task Lists** - Interactive checkboxes
- **Autolinks** - Automatic URL linking
- **Strikethrough** - `~~text~~` syntax

## Installation

```bash
composer require djadomi/f3-gfm
```

## Usage

### Basic Usage

```php
$gfm = new \Djadomi\F3Gfm;
$html = $gfm->convert('# Hello World');
echo $html;
```

### In Templates

```php
$f3->set('content', $gfm->convert($markdown));
```

### Template Filter

```php
\Template::instance()->filter('gfm', function($text) {
	return \Djadomi\F3Gfm::instance()->convert($text);
});
```

Then in templates:
```html
{{ @markdown_text | gfm }}
```

## Configuration

Set these in your F3 hive before using:

```php
$f3->set('GFM_HTML_INPUT', 'escape');   // 'strip' or 'allow' (default: 'escape')
$f3->set('GFM_UNSAFE_LINKS', false);    // Allow javascript: links (default: false)
$f3->set('GFM_MAX_NESTING', 100);       // Max nesting level (default: 100)
```

## Examples

### Tables

```markdown
| Name  | Age | City    |
|-------|-----|---------|
| Alice | 30  | NYC     |
| Bob   | 25  | London  |
```

### Task Lists

```markdown
- [x] Complete task
- [ ] Pending task
```

### Strikethrough

```markdown
~~This is deleted~~
```

### Autolinks

```markdown
Visit https://example.com
```

## License

MIT
