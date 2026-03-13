# Final CTA — Specialist-Forward Image Collage

## Problem

The right panel of the final CTA section needs a visually compelling image treatment that puts the specialist front-and-center while providing machine/jobsite context. Previous attempts with grid-based collages broke due to sizing conflicts and looked disconnected.

## Design

### Layout: Overlapping Absolute Stack

A single `relative` container with `aspect-[4/3]` and `max-w-md`. Three images layered via absolute positioning:

| Image | Size | Position | Z-index | Style |
|---|---|---|---|---|
| Machine (product PNG) | ~40% w | `top-0 right-0` | z-0 | `object-contain`, `bg-slate-800` pad |
| Action (jobsite photo) | ~50% w | `bottom-0 right-4` | z-10 | `object-cover`, `shadow-lg` |
| Specialist (portrait) | ~60% w | `bottom-0 left-0` | z-20 | `object-cover`, `shadow-xl` |

### Visual Hierarchy

- Specialist is largest and frontmost — clearly the hero
- Machine peeks out top-right behind the specialist
- Action peeks out bottom-right behind the specialist
- Increasing shadow depth (md -> lg -> xl) reinforces layering

### Name Card (below collage)

Left-aligned with orange `border-l-4` accent:
- **John Doe** (bold white)
- **Account Specialist** (orange)
- 12 Years - Southwest Region (slate gray)

## Files to Edit

- `app/templates/parts/final-cta.php` — replace specialist panel markup (lines 96-145)
- No changes to data wrappers — they already pass `image`, `image_machine`, `image_action`

## Verification

- Visual check on all three category pages
- `npm run build` passes
