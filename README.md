# F3-GFM - GitHub-Flavoured Markdown Plugin for Fat-Free Framework

A modern markdown plugin for F3 that supports GitHub-Flavoured Markdown features using the league/commonmark library.

## Features

- **Tables** - Full table support with alignment
- **Task Lists** - Interactive checkboxes
- **Autolinks** - Automatic URL linking
- **Strikethrough** - `~~text~~` syntax
- **Alerts** - GitHub-style admonitions (`[!NOTE]`, `[!TIP]`, `[!IMPORTANT]`, `[!WARNING]`, `[!CAUTION]`)

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
$f3->set('GFM_HTML_INPUT', 'escape'); // 'strip' or 'allow' (default: 'escape')
$f3->set('GFM_UNSAFE_LINKS', false); // Allow javascript: links (default: false)
$f3->set('GFM_MAX_NESTING', 100); // Max nesting level (default: 100)
$f3->set('GFM_TABLE_WRAP', false); // Wrap tables in div.gfm-table (default: false)
```

## Styling

You may want to include something like https://github.com/sindresorhus/github-markdown-css to get GitHub-like styling for your rendered markdown. Adding the `markdown-body` class to your container to apply these might require you to undo some of this CSS if you want to keep your site's own styling, especially variables like `--bgColor-default` and `--fgColor-default`.

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

### Alerts

> [!WARNING]
> Work in progress

```markdown
> [!NOTE]
> This is a note.

> [!WARNING]
> This is a warning.
```

Output:
```html
<blockquote class="gfm-alert gfm-alert-note" data-alert-type="note">
<p>This is a note.</p>
</blockquote>
```

## License

MIT
