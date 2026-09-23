#!/usr/bin/env bash
#
# Set Yoast SEO and share metadata for the SSM coming-soon page (/ssm/).
# The page stays noindex until the approved SSM render and specs are live;
# delete the _yoast_wpseo_meta-robots-noindex meta to let search engines in.

set -euo pipefail

wp() {
  command wp --skip-themes --skip-plugins "$@"
}

page_slug='ssm'
page_id="$(wp post list --post_type=page --name="${page_slug}" --post_status=publish,private,draft,pending,future --posts_per_page=1 --field=ID)"

if [[ -z "${page_id}" ]]; then
  echo "ERROR: page /${page_slug}/ was not found" >&2
  exit 1
fi

image_path='2026/08/Covered-machine-tradeshow-image.png'
image_id="$(wp post list --post_type=attachment --meta_key=_wp_attached_file --meta_value="${image_path}" --posts_per_page=1 --field=ID)"

title='SSM Portable Siding Machine | Coming Soon | New Tech Machinery'
description='Be first to hear about the SSM Portable Siding Machine, a new portable rollformer from NTM built for wall panels. Sign up for details.'

wp post meta update "${page_id}" _yoast_wpseo_title "${title}" >/dev/null
wp post meta update "${page_id}" _yoast_wpseo_metadesc "${description}" >/dev/null
wp post meta update "${page_id}" _yoast_wpseo_opengraph-title "${title}" >/dev/null
wp post meta update "${page_id}" _yoast_wpseo_opengraph-description "${description}" >/dev/null
wp post meta update "${page_id}" _yoast_wpseo_meta-robots-noindex 1 >/dev/null

if [[ -n "${image_id}" ]]; then
  image_url="$(wp eval "echo wp_get_attachment_url(${image_id});")"
  wp post meta update "${page_id}" _thumbnail_id "${image_id}" >/dev/null
  wp post meta update "${page_id}" _yoast_wpseo_opengraph-image-id "${image_id}" >/dev/null
  wp post meta update "${page_id}" _yoast_wpseo_opengraph-image "${image_url}" >/dev/null
else
  echo "WARNING: share image ${image_path} not found; skipped OG image." >&2
fi

# Save once with plugins loaded so Yoast rebuilds its cached indexable from the meta above.
command wp --skip-themes post update "${page_id}" --post_excerpt="${description}" >/dev/null

echo "Updated SEO metadata on page ${page_id} (/${page_slug}/). Noindex is on."
