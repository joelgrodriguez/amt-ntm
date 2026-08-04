# NTM Portable Gutter Machine ROI Calculator — Specification

## Overview
A self-contained, single-file HTML calculator designed to be embedded in a WordPress page. It helps prospective buyers of New Tech Machinery (NTM) portable gutter machines understand the return on investment by entering their own coil cost per pound, monthly production volume, pre-made gutter cost, and selling price.

---

## Machine Options

| Machine | Starting Price | Produces |
|---|---|---|
| 5" Machine | $9,800 | 5" K-style gutter |
| 6" Machine | $10,500 | 6" K-style gutter |
| 5"/6" Combo | $12,300 | Both 5" and 6" K-style gutter |

---

## Material / Coil Data

All lbs/ft values are sourced from the original NTM coil stock data. Cost per foot is **not** pre-calculated — it is derived at runtime from the user's entered $/lb × the material's lbs/ft.

| Material | Coil Width | Gutter Size | lbs/ft |
|---|---|---|---|
| Aluminum 032 | 11.75" | 5" | 0.446 |
| Aluminum 032 | 11.875" | 5" | 0.45 |
| Aluminum 027 | 11.75" | 5" | 0.377 |
| Aluminum 027 | 11.875" | 5" | 0.38 |
| Steel 26ga | 11.75" | 5" | 0.768 |
| Steel 26ga | 11.875" | 5" | 0.775 |
| Steel 24ga | 11.75" | 5" | 1.0 |
| Steel 24ga | 11.875" | 5" | 1.0 |
| Aluminum 032 | 15" | 6" | 0.571 |
| Aluminum 027 | 15" | 6" | 0.476 |
| Steel 26ga | 15" | 6" | 0.969 |
| Steel 24ga | 15" | 6" | 1.2 |
| Steel 24ga | 20" | Specialty | 1.67 |

### Material Dropdown Filtering
When a machine is selected, the material dropdown filters to only show compatible coil sizes:
- 5" Machine → 11.75" and 11.875" coil options only
- 6" Machine → 15" coil options only
- 5"/6" Combo → all options including specialty

---

## User Inputs

| Field | Type | Default | Required | Notes |
|---|---|---|---|---|
| Machine Selection | Card toggle | 5" Machine | Yes | 3 options |
| Material & Coil Width | Dropdown | First valid option | Yes | Filtered by machine |
| Monthly Production | Number (ft) | 9,600 | Yes | Based on 50 ft/min production speed |
| Your Coil Cost ($/lb) | Number | None | **Yes** | No results shown until entered |
| Pre-Made Gutter Cost ($/ft) | Number | Auto-fill from HD data | No | Used for savings & payback calc |
| Your Selling Price ($/ft) | Number | None | No | Used for monthly revenue calc |

### Pre-Made Cost Auto-Fill (Home Depot Reference)
| Gutter Size | Home Depot $/ft |
|---|---|
| 5" aluminum | $1.74 |
| 6" aluminum | $3.90 |

Auto-fills when a material is selected. User can override. Resets on material change unless manually edited.

---

## Outputs / Results

| Result Card | Formula | Requires |
|---|---|---|
| Derived Cost Per Foot | $/lb × lbs/ft | $/lb input |
| Cost Per Foot Comparison (bar chart) | Machine-made vs. pre-made | $/lb + pre-made cost |
| Monthly Production | User input (ft) | Monthly ft input |
| Monthly Material Cost | monthly ft × $/ft (machine-made) | $/lb input |
| Monthly Revenue Potential | monthly ft × selling price | $/lb + selling price |
| Monthly Savings vs. Pre-Made | monthly ft × (pre-made $/ft − machine $/ft) | $/lb + pre-made cost |
| Machine Payback Period | machine price ÷ monthly savings | All cost inputs |

---

## Production Speed Assumption
All time-based calculations use **50 ft/min**. The 35 ft/min figure from source data is not used.

---

## Constraints
- No backend, no database, no external dependencies
- Fully portable: logo embedded as base64, all CSS and JS inline
- Must render correctly when pasted into a WordPress Custom HTML block or iFrame
- Max width: 860px (auto-centers on wider pages)
- Responsive down to ~320px viewport width
