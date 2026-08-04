# NTM ROI Calculator — Handoff Document

## Deliverable
**File:** `ntm_roi_calculator.html`
A single, fully self-contained HTML file. No server, no build step, no external dependencies.

---

## How to Embed in WordPress

### Option A — Custom HTML Block (Recommended)
1. Open the WordPress page editor.
2. Add a **Custom HTML** block.
3. Paste the entire contents of `ntm_roi_calculator.html` directly into the block.
4. Publish or update the page.

> **Tip:** If your theme applies conflicting global styles (e.g. resets `box-sizing` or overrides `font-family`), the calculator's scoped CSS under `.ntm-calc` should contain most styles safely — but test in a staging environment first.

### Option B — iFrame Embed
1. Upload `ntm_roi_calculator.html` to your server (e.g. via FTP or the WordPress media library using a plugin that allows HTML uploads such as **WP File Manager**).
2. Note the public URL of the uploaded file.
3. Add a **Custom HTML** block to your page with:

```html
<iframe
  src="https://yourdomain.com/path/to/ntm_roi_calculator.html"
  width="100%"
  height="950"
  frameborder="0"
  scrolling="auto"
  style="border:none;">
</iframe>
```

4. Adjust `height` as needed — 950px covers the full calculator on desktop.

### Option C — WPCode Plugin
1. Install and activate the **WPCode** plugin (free).
2. Go to **Code Snippets → Add Snippet → HTML Snippet**.
3. Paste the full file contents.
4. Use the generated shortcode `[wpcode id="XX"]` on any page or post.

---

## Updating Machine Prices

Machine starting prices are defined in the JavaScript near the top of the `<script>` block:

```js
const MACHINES = {
  '5in':  { label:'5" Machine',   price: 9800,  sizes: [5] },
  '6in':  { label:'6" Machine',   price: 10500, sizes: [6] },
  'combo':{ label:'5"/6" Combo',  price: 12300, sizes: [5,6,null] }
};
```

Change the `price` values to reflect current pricing at any time.

---

## Updating Material / Coil Data

Material entries are in the `MATERIALS` array in the `<script>` block:

```js
{ id:1, label:'Aluminum 032 — 11.75" coil (5" gutter)', size:5, lbPerFt:0.446 },
```

- `label` — displayed in the dropdown
- `size` — `5`, `6`, or `null` (specialty); controls which machine selections show this option
- `lbPerFt` — pounds per linear foot; used to derive $/ft from the user's $/lb input

---

## Updating Home Depot Reference Prices

The auto-fill comparison prices are defined as:

```js
const HD = { 5: 1.74, 6: 3.90 };
```

Update these values to reflect current market pricing whenever needed.

---

## Updating the Logo

The NTM logo is embedded as a base64 data URI string assigned to `LOGO_DATA_URI` near the bottom of the `<script>` block. To replace it:

1. Convert your new logo image to base64:
   - Online tool: [base64.guru/converter/encode/image](https://base64.guru/converter/encode/image)
   - Command line: `base64 -w 0 your_logo.jpg`
2. Replace the value of `LOGO_DATA_URI` with: `'data:image/jpeg;base64,YOUR_BASE64_STRING_HERE'`
   (Use `image/png` if the new logo is a PNG.)

---

## Browser Compatibility
Tested and functional in:
- Chrome 100+
- Firefox 100+
- Safari 15+
- Edge 100+
- Mobile Safari / Chrome on iOS and Android

---

## Known Limitations
- No results are shown until the user enters a **$/lb coil cost** — this is by design to avoid pre-populating with potentially outdated data.
- The calculator does not account for labor, overhead, installation, or freight costs.
- Machine prices shown are **starting prices** — actual quotes may differ.
- The 50 ft/min production speed is a standard assumption; actual throughput varies by operator and setup.
