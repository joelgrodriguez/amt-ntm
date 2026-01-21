# Theme TODO

Tracking future enhancements and features.

---

## Mobile Filters

**Priority:** Medium
**Status:** Not started
**Files:** `single-profile.php`, future archive templates

### Problem
Filter sidebars are hidden on mobile (`hidden lg:block`). Users cannot access filters on smaller screens.

### Solution
Create a reusable slide-out drawer component:

1. **New files:**
   - `templates/parts/mobile-filter-drawer.php` - Drawer component
   - `resources/js/modules/MobileFilters.js` - Open/close behavior (similar to `MobileMenu.js`)

2. **Implementation:**
   - "Filter" button visible only on mobile (below header or as sticky bar)
   - Drawer slides in from left or bottom
   - Reuses same filter content from sidebar
   - Close button and overlay backdrop

3. **Templates to update:**
   - [ ] `single-profile.php` - Profile type and machine filters
   - [ ] `single-manual.php` - Manual type and machine filters
   - [ ] Archive templates (when created)
   - [ ] Product listing pages (when needed)

---

## Profile Template - WooCommerce Integration

**Priority:** Medium
**Status:** Not started
**File:** `single-profile.php:137`

### Current state
Machine tags display as placeholder cards with settings icon. Links go to tag archive pages.

### Tasks
- [ ] Connect machine tags to WooCommerce products by matching tag name to product title/SKU
- [ ] Display actual product images in "Compatible NTM Machines" cards
- [ ] Link cards to product pages instead of tag archives
- [ ] Handle cases where no matching product is found (fallback to current behavior)

### Code location
```php
// @todo: Connect tag to WooCommerce product
// Find product by matching tag name to product title/SKU
// $product = wc_get_products(['name' => $machine_tag->name, 'limit' => 1]);
```

---

## Manual Template - WooCommerce Integration

**Priority:** Medium
**Status:** Not started
**File:** `single-manual.php:140`

### Current state
Machine tags display as placeholder cards with settings icon. Links go to tag archive pages.

### Tasks
- [ ] Connect machine tags to WooCommerce products by matching tag name to product title/SKU
- [ ] Display actual product images in "Related NTM Machines" cards
- [ ] Link cards to product pages instead of tag archives
- [ ] Handle cases where no matching product is found (fallback to current behavior)

### Notes
- Manuals categorized by: Seamless Gutter Machines, Roof and Wall Panel Machines, Accessories
- Some manuals are in Spanish (may need language indicator in future)
- Each manual tagged with specific machine(s) it applies to

---

## Future Considerations

- [ ] Profile archive page with filters
- [ ] Manual archive page with filters
- [ ] Machine/product single page showing all compatible profiles and manuals
- [ ] Search integration for profiles and manuals
- [ ] Language indicator for Spanish manuals
