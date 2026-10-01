# Spacing System Quick Reference

Use these standards for consistent spacing across all templates.

## Spacing Philosophy

**Gap-based** is the primary approach:
- Headings have NO margin - parent grid controls spacing via `gap-*`
- Use `grid gap-*` for discrete blocks (headers, cards, sections)
- Eliminates margin collapse issues
- Parent container controls separation between children

**Margin is valid** for:
- Inside prose/typography blocks (Tailwind Typography handles this)
- Internal card styling where grid doesn't work (e.g., overlapping elements)

## Container Class

The `.container` class already includes `mx-auto`. Never use `container mx-auto` - it's redundant.

```php
<!-- Correct -->
<div class="container">

<!-- Wrong - redundant mx-auto -->
<div class="container mx-auto">
```

## Section Classes

| Class | Padding | Usage |
|-------|---------|-------|
| `.section` | `py-16 md:py-20 lg:py-24` | Standard content sections |
| `.section-compact` | `py-12 md:py-16` | Utility/tool sections |

## Section Content Layout

| Class | Description |
|-------|-------------|
| `.section-content` | `grid gap-12 lg:gap-16` - Wraps header + content, gap handles separation |

## Header Containers (No margin - parent gap handles spacing)

| Class | Description |
|-------|-------------|
| `.section-header` | Centered: `text-center grid gap-4` |
| `.section-header-left` | Left-aligned: `grid gap-4` |

## Header Elements (No margin - parent gap handles spacing)

| Class | Usage |
|-------|-------|
| `.section-eyebrow` | Uppercase label above title |
| `.section-divider` | Left-aligned accent bar |
| `.section-divider-center` | Centered accent bar |
| `.section-title` | Main heading (responsive `3xl → 4xl → 5xl`) |
| `.section-subtitle` | Intro text (no margin) |
| `.section-subtitle-centered` | Centered intro with `max-w-2xl` |

## Standard Gap Values

| Context | Gap |
|---------|-----|
| Section content (header → body) | `gap-12 lg:gap-16` |
| Left-aligned column content | `gap-8 lg:gap-10` |
| Two-column layouts | `gap-12 lg:gap-16` |
| Card/feature grids | `gap-6 lg:gap-8` |
| Header elements (eyebrow → title → subtitle) | `gap-4` |
| Button groups | `gap-4` |
| List items | `gap-4` |
| Icon + text pairs | `gap-3` |
| Internal card content | `gap-3` |
| Nav dots/small elements | `gap-2` |

## Pattern: Centered Section

```php
<section class="section" aria-labelledby="section-title">
    <div class="container section-content">
        <div class="section-header">
            <p class="section-eyebrow"><?php echo esc_html($content['eyebrow']); ?></p>
            <div class="section-divider-center"></div>
            <h2 id="section-title" class="section-title"><?php echo esc_html($content['title']); ?></h2>
            <p class="section-subtitle-centered"><?php echo esc_html($content['text']); ?></p>
        </div>
        <!-- Content grid/cards here - gap-12 lg:gap-16 separates from header -->
        <div class="grid gap-6 lg:gap-8 md:grid-cols-3">...</div>
        <div class="text-center">
            <a href="#" class="btn btn-primary">CTA</a>
        </div>
    </div>
</section>
```

## Pattern: Two-column with Left-aligned Header

```php
<section class="section" aria-labelledby="section-title">
    <div class="container">
        <div class="grid gap-12 lg:grid-cols-2 lg:gap-16 lg:items-center">
            <!-- Content Column -->
            <div class="grid gap-8 lg:gap-10 content-start">
                <div class="section-header-left">
                    <p class="section-eyebrow"><?php echo esc_html($content['eyebrow']); ?></p>
                    <div class="section-divider"></div>
                    <h2 id="section-title" class="section-title"><?php echo esc_html($content['title']); ?></h2>
                    <p class="section-subtitle"><?php echo esc_html($content['text']); ?></p>
                </div>
                <!-- Content here - gap-8 lg:gap-10 separates from header -->
                <ul class="space-y-6">...</ul>
                <div class="flex flex-wrap gap-4">
                    <a href="#" class="btn btn-primary">CTA</a>
                </div>
            </div>
            <!-- Image Column -->
            <div>
                <img src="..." alt="..." class="w-full h-auto" loading="lazy">
            </div>
        </div>
    </div>
</section>
```

## Pattern: Article Layout (single.php style)

```php
<article class="grid gap-6 lg:gap-12">
    <header>
        <div class="container grid gap-6 lg:gap-12">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold">Title</h1>
            <!-- No margin on heading - gap handles spacing -->
        </div>
    </header>
    <div class="prose">Content</div>
</article>
```

## Pattern: Card with Internal Content

For cards where content needs to be centered and grid would stretch elements:

```php
<div class="grid gap-3 justify-items-center bg-white border border-slate-200 p-8">
    <span class="...">Badge/Icon</span>
    <h3 class="text-xl font-semibold text-slate-900">Title</h3>
    <p class="text-slate-600">Description</p>
</div>
```

## Pattern: Simple CTA Section

```php
<section class="section bg-slate-900" aria-labelledby="cta-title">
    <div class="container grid gap-8 lg:gap-10 text-center">
        <div class="grid gap-4">
            <h2 id="cta-title" class="text-3xl font-bold text-white md:text-4xl lg:text-5xl">
                Title
            </h2>
            <p class="text-lg text-slate-300 max-w-2xl mx-auto">
                Subtitle text
            </p>
        </div>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="#" class="btn btn-primary">Primary CTA</a>
            <a href="#" class="btn btn-outline-light">Secondary CTA</a>
        </div>
    </div>
</section>
```

## Exceptions

- **Hero slider**: No section padding (full-viewport)
- **Video embed (who-is-ntm)**: Custom layout with `py-3`, `py-6 lg:py-12`
- **JS-toggled panels**: When using `display: hidden/block` for visibility, use `visibility`/`opacity` with `position: absolute/relative` instead so grid gap works properly (see explore-machines.php)
