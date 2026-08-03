# Local Wistia posters

These responsive WebP posters are local copies of each video's configured
Wistia thumbnail. Keeping them in the theme avoids loading the Wistia player,
thumbnail CDN, or tracking resources before a visitor starts playback.

The sync discovers IDs from theme PHP and from Wistia URLs stored in
WooCommerce product content or metadata. Refresh the posters after changing a
thumbnail or adding a product video:

```bash
wp eval-file wp-content/themes/amt-ntm/scripts/media/sync-wistia-thumbnails.php
```
