# Behavior spec: machines

<!-- shogun:auto — appended on land, newest first. Read top-down for current behavior. -->

## Remove the "which machine" quiz CTA from the **gutter** landing page only. Adam: "Remove pick your machine quiz on the gutter landing pages." Leave it on the other two pages that include it. — #32
*Landed 2026-06-23 · type: chore*

- `app/page-seamless-gutter-machines.php:39` — drop the `which-machine` `get_template_part` include.
- `app/page-machines.php` and `app/page-roof-wall-panel-machines.php` keep their includes (scope = gutter only).
- `npm run build` passes.
