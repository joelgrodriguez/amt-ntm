<?php
/**
 * Knowledgebase article fixtures.
 *
 * One-shot migration source for the service-hub Troubleshooting content,
 * captured from the live support portal (support.newtechmachinery.com) on
 * 2026-06-16. The importer (scripts/db/024-seed-knowledgebase.sh) reads this
 * file and upserts a `knowledgebase` post per record, so the import is offline,
 * deterministic, and replayable without the live site being up.
 *
 * Each record:
 *   source_url    string  Canonical URL on the support portal. Upsert key
 *                         (stored as `_kb_source_url` post meta) — re-running
 *                         the importer updates in place, never duplicates.
 *   title         string  Article title (kept verbatim, marks included).
 *   machine_slugs string[] Theme machine slugs (app/data/machines/<slug>.php)
 *                         this article applies to. Each becomes a post_tag, so
 *                         a shared article surfaces under every machine it fits.
 *   excerpt       string  One-line summary for the card.
 *   published     string  Source publish date (Y-m-d).
 *   body          string  Clean HTML (Gutenberg-compatible) for post_content.
 *   image_slug    string  Attachment slug (post_name) of the curated featured
 *                         image, picked from the existing media library by topic.
 *                         Resolved to an ID at seed time so it survives a fresh
 *                         prod pull (IDs differ; slugs are stable). If the slug
 *                         can't be resolved, the importer falls back to the
 *                         machine's product photo.
 *
 * Portal-slug -> theme-slug mapping is applied here, at capture time, so the
 * importer stays dumb. Mapping reference:
 *   ssqii  -> ssq-ii-multipro
 *   ssh    -> ssh-multipro
 *   ssr    -> ssr-multipro-jr
 *   machii -> mach-ii-5-gutter, mach-ii-6-gutter, mach-ii-combo-gutter
 *   bg7    -> bg7-box-gutter
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$roof_all  = ['ssq-ii-multipro', 'ssh-multipro', 'ssr-multipro-jr'];
$ssqii     = ['ssq-ii-multipro'];
$machii    = ['mach-ii-5-gutter', 'mach-ii-6-gutter', 'mach-ii-combo-gutter'];
$gutter_all = ['mach-ii-5-gutter', 'mach-ii-6-gutter', 'mach-ii-combo-gutter', 'bg7-box-gutter'];
$bg7       = ['bg7-box-gutter'];

return [
    // --- Roof & Wall Panel machines -------------------------------------
    [
        'source_url'    => 'https://support.newtechmachinery.com/article/update-uniq-automatic-control-system-software/',
        'title'         => 'How Do I Update My UNIQ® Automatic Control System Software?',
        'machine_slugs' => $ssqii,
        'excerpt'       => 'Step-by-step instructions to update the UNIQ PLC and touchscreen using the provided SD card and USB drive.',
        'published'     => '2022-11-07',
        'image_slug'    => 'uniq-software-update-download-featured-image',
        'body'          => <<<'HTML'
<!-- wp:paragraph --><p>The NTM UNIQ® Automatic Control System software has undergone a complete re-design and update based on your feedback. The latest software update will ensure user-friendliness, reliability, and safety remain top of mind when using your UNIQ controller.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>This latest release can be downloaded and easily updated on your machine controller via the SD card and USB thumb drive provided. All machines are currently being shipped with the SD card and thumb drives. Check your UNIQ Control System for these items before contacting the Service Department.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p><a href="https://newtechmachinery.com/machines/uniq-control-system-update/">Download the latest UNIQ® software update.</a></p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Before updating your UNIQ Control System</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>When you download the software update from the zip file, extract the contents inside the folders labeled "Files for SD Card" and "Files for USB Drive". Download the zip file, save it in your downloads folder, right-click and select "Extract all", then copy the contents to the SD card and thumb drive you will need to complete the update.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Part 1 — Update the PLC</h2><!-- /wp:heading -->
<!-- wp:list {"ordered":true} --><ol><li>Turn off the machine by rotating the power disconnect on the right side of the control panel.</li><li>Loosen the two screws at the bottom corners of the control panel and open the door.</li><li>Insert the SD card provided into the card slot of the PLC (programmable logic controller).</li><li>Turn the machine on by rotating the power disconnect.</li><li>After about 30 seconds, the RUN / STOP light should be steady yellow and the MAINT light should be flashing.</li><li>Turn the machine off by rotating the power disconnect.</li><li>Remove the SD card.</li><li>Turn the machine back on by rotating the power disconnect.</li><li>The lights will flash again. When the RUN / STOP light is steady green the programming is complete.</li><li>Close the control panel door and replace the screws.</li></ol><!-- /wp:list -->
<!-- wp:heading --><h2>Part 2 — Update the Touchscreen</h2><!-- /wp:heading -->
<!-- wp:list {"ordered":true} --><ol><li>Insert the USB drive into the USB port on the front of the control panel.</li><li>Move the AUTO/MAN switch to AUTO if it is not already in that position.</li><li>Press the Home button, then Settings, then Exit HMI.</li><li>Press Settings.</li><li>Double-tap Service & Commissioning.</li><li>Press the Load Project tab.</li><li>Press the Next button.</li><li>Press Storage Card USB to highlight it, then press Next.</li><li>Press on the file listed to highlight it, then press Next.</li><li>Check the two boxes shown and press Load. (A pencil eraser works well for pressing the small checkboxes. It will still be possible to go back to Version 1 even after the upgrade.)</li><li>Press Yes on the next window.</li><li>Press the Load button.</li><li>Once it has finished loading, the touchscreen will restart. Verify the version numbers at the bottom of the screen are correct.</li><li>If the USB drive and SD card were from New Tech Machinery, mail them back in the stamped, addressed envelope provided. If you used the ones inside the control panel, put them back for later use.</li></ol><!-- /wp:list -->
HTML,
    ],
    [
        'source_url'    => 'https://support.newtechmachinery.com/article/panel-is-not-forming-properly/',
        'title'         => 'The Panel Isn\'t Forming Properly or Having Shape Issues',
        'machine_slugs' => $roof_all,
        'excerpt'       => 'Tooling alignment, entry guide, arbor position, and die alignment checks when a panel comes out crooked or mis-shaped.',
        'published'     => '2022-05-31',
        'image_slug'    => 'cutting-coil-from-inside-rollforming-machine',
        'body'          => <<<'HTML'
<!-- wp:paragraph --><p>If the finished panel doesn't look straight, has shape issues, or the legs are the wrong size, this could mean a few things. Work through the checks below:</p><!-- /wp:paragraph -->
<!-- wp:list --><ul><li>Check that your profile tooling is set up properly and in alignment.</li><li>Ensure your entry guide is set up correctly for the coil width you're using.</li><li>Ensure the material position on the expandable arbor is correct for the tooling profile in the machine. The arbor adjuster must also be on the correct side of the machine.</li><li>Check that entry rollers and shear/profile dies are correctly aligned to the material.</li></ul><!-- /wp:list -->
<!-- wp:paragraph --><p>A video demonstration of these procedures is available on the source article. Although it demonstrates the steps on an SSQ II machine, the process is similar for the SSH and SSR machines — refer to your specific machine manual for detailed instructions.</p><!-- /wp:paragraph -->
HTML,
    ],
    [
        'source_url'    => 'https://support.newtechmachinery.com/article/why-is-the-shear-not-cutting-properly/',
        'title'         => 'Why Is the Shear Not Cutting Properly?',
        'machine_slugs' => $roof_all,
        'excerpt'       => 'Diagnose shear misalignment, then adjust entry/exit dies and blades and maintain the shear for clean cuts.',
        'published'     => '2022-05-31',
        'image_slug'    => 'shear-adjustment-mach-ll-gutter-machine-video-2',
        'body'          => <<<'HTML'
<!-- wp:paragraph --><p>If your shear is not cutting cleanly or straight, the issue likely stems from misalignment or inadequate lubrication.</p><!-- /wp:paragraph -->
<!-- wp:quote --><blockquote class="wp-block-quote"><p>The shear is extremely dangerous and can cause serious bodily injury or death. Always keep the guard in place during operation. Remove it only for maintenance when the machine is unplugged or locked out / tagged out.</p></blockquote><!-- /wp:quote -->
<!-- wp:heading --><h2>Signs of shear misalignment</h2><!-- /wp:heading -->
<!-- wp:list --><ul><li>The shear cuts metal unevenly or jaggedly.</li><li>Cut panels catch on or hit the exit shear die.</li><li>A worn-out center mandrel.</li><li>A broken shear die.</li></ul><!-- /wp:list -->
<!-- wp:heading --><h2>How to adjust the entry and exit shear dies</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Entry and exit shear dies are profile-specific and require adjustment when changing profiles. The outside vertical leg of the male and female entry dies should be approximately 1/32" away from the outside of the vertical legs of the panel.</p><!-- /wp:paragraph -->
<!-- wp:list {"ordered":true} --><ol><li>Run material through until approximately 6 inches from the shear; shut off and disconnect power.</li><li>Sight down the panel legs to locate the entry shear dies; hold with "C" bolts (don't tighten yet).</li><li>Start the machine and carefully jog material forward while viewing through the dies; adjust if needed.</li><li>Continue jogging until material is 1–2 inches past the shear dies; power down.</li><li>Adjust entry shear dies to 1/32" from the panel's outside vertical leg; tighten "C" bolts.</li><li>If the entry shear die has a mandrel, position it per your manual; loosen "E" bolts if needed and adjust.</li><li>Install exit shear dies positioned about 1/32" outboard of the entry shear dies; tighten "C" bolts.</li><li>Adjust the exit shear die mandrel as needed.</li></ol><!-- /wp:list -->
<!-- wp:heading {"level":3} --><h3>Blade adjustments</h3><!-- /wp:heading -->
<!-- wp:list {"ordered":true} --><ol><li>Position top blades so the #2 blade tip is inside the male leg and the #1 blade tip is inside the female leg, creating a scissor action. Loosen the seven "D" bolts to reposition if necessary, then retighten.</li><li>Start the machine and shear a 12-inch panel; jog forward a few inches. Inspect for scraping and cut quality; make corrections as needed.</li></ol><!-- /wp:list -->
<!-- wp:heading --><h2>Maintaining the shear</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Clean and lubricate the top blades, bottom dies, and male/female dies approximately every 30 cuts during regular use, or when cutting surfaces appear dry. Proper lubrication is essential to clean cuts, rust prevention, and longevity of the shear. Super Lube® Multi-Purpose Synthetic Lubricant is recommended. Do not use WD-40.</p><!-- /wp:paragraph -->
HTML,
    ],
    [
        'source_url'    => 'https://support.newtechmachinery.com/article/panel-length-programming-encoder-alignment/',
        'title'         => 'Why Is the Finished Panel Not the Length I Programmed Into My Controller?',
        'machine_slugs' => $roof_all,
        'excerpt'       => 'Align and tension the encoder so programmed panel lengths match the finished panel.',
        'published'     => '2022-05-31',
        'image_slug'    => '20230614_ntm_adjusting-notching-tab-length-thumbnail-2',
        'body'          => <<<'HTML'
<!-- wp:paragraph --><p>If the panel is a different length than you programmed into your PLC or EZ-Counter controllers, the most common cause is that the encoder may need adjustment. Make sure your encoder is making contact with the material inside the machine and that the tension is set properly.</p><!-- /wp:paragraph -->
<!-- wp:list {"ordered":true} --><ol><li>Consult your machine manual for instructions: <a href="https://newtechmachinery.com/learning-center/resource/manuals/">newtechmachinery.com/learning-center/resource/manuals/</a></li><li>Make sure all covers and guards are removed from the machine. <em>Don't operate your machine without covers and guards in place!</em></li><li>Locate your encoder. Check your machine manual for help on where to find the encoder inside your machine.</li><li>Required tools: 5/32" hex wrench, 3/32" hex wrench, 7/16" boxed-end wrench.</li><li>Using the 5/32" hex wrench and 7/16" boxed-end wrench, ensure the through-bolt is tight but do not over-tighten.</li><li>Remove the rubber cap on top of the encoder.</li><li>Using the 3/32" hex wrench, apply pressure to the encoder and tighten the internal spring. This allows the encoder to make contact with the panel.</li><li>Reinstall the rubber cap on top of the encoder and replace all covers and guards.</li></ol><!-- /wp:list -->
<!-- wp:paragraph --><p><strong>Note:</strong> SSQ II MultiPro machines use a self-tensioning encoder, so this process does not apply. If you encounter any issues or if you are using the UNIQ Control System, please contact the Service department.</p><!-- /wp:paragraph -->
HTML,
    ],
    [
        'source_url'    => 'https://support.newtechmachinery.com/article/ssq-ii-machine-stuck-in-maintenance-mode/',
        'title'         => 'What Do I Do if My Machine Is Stuck in "Maintenance Mode" When Using the UNIQ® Control System?',
        'machine_slugs' => $ssqii,
        'excerpt'       => 'Maintenance mode is a normal UNIQ safety state when covers and guards are off — here is how to exit it.',
        'published'     => '2022-05-31',
        'image_slug'    => '20221110_ntm_how-to-use-the-uniq-push-button-control-panel-update_featured-image',
        'body'          => <<<'HTML'
<!-- wp:paragraph --><p>When the covers and guards are off of your machine, the machine will operate in "maintenance mode." This is a normal safety function of the UNIQ® Automatic Control System and will not allow you to use any of the automatic functions (automatic run mode, automatic shear, programming recipes, etc.). When maintenance mode is activated, only the push buttons on the controller panel can be used.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>To exit maintenance mode, first make sure your machine is set up correctly with all outside covers and shear guards in place. Make sure the shear magnets / sensors are aligned properly. Once all covers, guards, and sensors are in place, you can access automatic operation with the UNIQ Control System.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>If you haven't yet, update your UNIQ Automatic Control System to the latest software.</p><!-- /wp:paragraph -->
HTML,
    ],

    // --- Seamless Gutter machines ---------------------------------------
    [
        'source_url'    => 'https://support.newtechmachinery.com/article/how-to-do-a-changeover-for-a-5-6-combo-gutter-machine/',
        'title'         => 'How to Do a Changeover on the MACH II 5″/6″ Combo Gutter Machine',
        'machine_slugs' => $machii,
        'excerpt'       => 'Walkthrough of the 5″↔6″ changeover on the MACH II Combo Gutter Machine.',
        'published'     => '2025-01-28',
        'image_slug'    => '6-inch-to-5-inch-changeover-mach-ll-gutter-machine-video-2',
        'body'          => <<<'HTML'
<!-- wp:paragraph --><p>This guide covers the 5″-to-6″ and 6″-to-5″ changeover on the MACH II Combo Gutter Machine. The full step-by-step procedure is demonstrated in the video walkthrough on the source article.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p><a href="https://support.newtechmachinery.com/article/how-to-do-a-changeover-for-a-5-6-combo-gutter-machine/">View the full changeover walkthrough →</a></p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Have a different problem? Contact the NTM Service Department by phone or email for troubleshooting assistance.</p><!-- /wp:paragraph -->
HTML,
    ],
    [
        'source_url'    => 'https://support.newtechmachinery.com/article/gutter-lip-is-too-small/',
        'title'         => 'The Gutter\'s Hem / Lip Is Too Small',
        'machine_slugs' => $machii,
        'excerpt'       => 'Move the entry guides to the right to increase a hem that is coming out under the 1/4″ minimum.',
        'published'     => '2022-06-06',
        'image_slug'    => 'what-is-the-clip-relief-why-its-important-and-how-to-adjust-it',
        'body'          => <<<'HTML'
<!-- wp:paragraph --><p>If the hem or lip on your finished gutter is coming out too small — less than the recommended 1/4″ minimum — it could hinder the way the gutter functions or cause it to be improperly installed. Too small a lip can also cause the gutter to run uphill and into the building.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>On a MACH II™ Gutter Machine, the easiest fix is to move the Face/Right Entry, Back/Left Entry, and Post Entry Guides to the right. ("Left" and "right" refer to facing the entry end of the machine.) Reference the Entry Guide Assembly section of your manual for instructions based on your machine's sizing and configuration.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Steps to fix the issue</h2><!-- /wp:heading -->
<!-- wp:list {"ordered":true} --><ol><li>Move the Face/Right, Back/Left Entry Guide, and Post Guide to the right.</li><li>The Face/Right Entry Guide controls how much material is fed into the face roller and box assembly. Move it only to increase or decrease the amount of lip turned under.</li><li>If you adjust the Face/Right Entry Guide, you must also adjust the Post Guide on the first skate assembly and the Back/Left Entry Guide, using the coil as a guide. There should be no visible space between the entry guides and the coil — but not so tight that the material binds in the entry guide assembly.</li></ol><!-- /wp:list -->
<!-- wp:heading --><h2>Manufacturing-date considerations</h2><!-- /wp:heading -->
<!-- wp:list --><ul><li><strong>December 2014 or earlier:</strong> The last 4 digits of your serial number give the month and year of production. If they are 1214 or earlier, you will not have Post Entry Guides to move.</li><li><strong>January 2015 or later:</strong> If the last 4 digits are 0115 or later, you have a Post Guide Assembly.</li></ul><!-- /wp:list -->
HTML,
    ],
    [
        'source_url'    => 'https://support.newtechmachinery.com/article/gutter-lip-is-too-big/',
        'title'         => 'The Gutter\'s Hem / Lip Is Too Big',
        'machine_slugs' => $machii,
        'excerpt'       => 'Move the entry guides to the left to reduce a rippled or oversized hem above the 5/16″ maximum.',
        'published'     => '2022-06-06',
        'image_slug'    => 'what-is-the-clip-relief-why-its-important-and-how-to-adjust-it',
        'body'          => <<<'HTML'
<!-- wp:paragraph --><p>If the lip or hem of the gutter is coming out rippled or distorted, it may be too big. The recommended lip on a gutter is approximately 1/4″ to 5/16″ maximum, turned under.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>On a MACH II™ Gutter Machine, the easiest fix is to move the Face/Right Entry, Back/Left Entry, and Post Entry Guides to the left. ("Left" and "right" refer to facing the entry end of the machine.) Reference the Entry Guide Assembly section of your manual for instructions based on your machine's sizing and configuration.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>If you adjust the Face/Right Entry Guide, you must also adjust the Post Guide on the first skate assembly and the Back/Left Entry Guide, using the coil as a guide. There should be no visible space between the entry guides and the coil — but not so tight that the material binds in the entry guide assembly.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Manufacturing-date considerations</h2><!-- /wp:heading -->
<!-- wp:list --><ul><li><strong>December 2014 or earlier:</strong> If the last 4 digits of your serial number are 1214 or earlier, you will not have Post Entry Guides to move.</li><li><strong>January 2015 or later:</strong> If the last 4 digits are 0115 or later, you have a Post Guide Assembly.</li></ul><!-- /wp:list -->
HTML,
    ],
    [
        'source_url'    => 'https://support.newtechmachinery.com/article/gutter-running-away-from-the-house/',
        'title'         => 'Gutter Running "Away From the House"',
        'machine_slugs' => ['mach-ii-5-gutter', 'mach-ii-6-gutter', 'mach-ii-combo-gutter', 'bg7-box-gutter'],
        'excerpt'       => 'Adjust the green station swing shaft assembly (bolt "K" clockwise) to straighten a gutter bowing away from the fascia.',
        'published'     => '2022-06-06',
        'image_slug'    => 'blue-station-adjustment-mach-ll-gutter-machine-video-2',
        'body'          => <<<'HTML'
<!-- wp:paragraph --><p>Holding the gutter in your hands and looking straight down toward the ground, if the center of the gutter touches the fascia but the ends do not, the gutter is "running away from the house."</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Refer to your gutter machine manual before making any adjustments.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p><strong>If your machine has multiple issues simultaneously — like "downhill" and "into/away from the house" — adjust only one thing at a time. If you're unsure how to adjust your gutter machine, contact the Service department first.</strong></p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Adjust the green station</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>To fix gutters going away from the house, adjust the swing shaft assembly (color-coded green). This assembly applies pressure to the head of the gutter and is the most common adjustment used to straighten the gutter.</p><!-- /wp:paragraph -->
<!-- wp:list {"ordered":true} --><ol><li>Loosen the two "J" bolts on top of the gutter box assembly enough that the swing shaft assembly can slide.</li><li>Turn the adjustment bolt "K" to move the swing shaft assembly.</li><li>To fix gutters going away from the house, turn bolt "K" <strong>clockwise</strong> a quarter turn at a time.</li><li>After each adjustment, run an 8′ piece of gutter to check straightness.</li><li>If needed, repeat — and check the gutter lip before completing any adjustments.</li><li>While holding the swing shaft adjustment assembly so bolt "K" is against the box assembly, retighten bolts "J."</li></ol><!-- /wp:list -->
HTML,
    ],
    [
        'source_url'    => 'https://support.newtechmachinery.com/article/gutter-running-into-the-house/',
        'title'         => 'Gutter Is Running "Into the House"',
        'machine_slugs' => ['mach-ii-5-gutter', 'mach-ii-6-gutter', 'mach-ii-combo-gutter', 'bg7-box-gutter'],
        'excerpt'       => 'Adjust the green station swing shaft assembly (bolt "K" counterclockwise) to straighten a gutter bowing into the fascia.',
        'published'     => '2022-06-06',
        'image_slug'    => 'blue-station-adjustment-mach-ll-gutter-machine-video-2',
        'body'          => <<<'HTML'
<!-- wp:paragraph --><p>If the ends of a finished piece of gutter bow toward the house and the center pulls away from the fascia, the gutter is "running into the house."</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Refer to your gutter machine manual before making any adjustments.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p><strong>If your machine has multiple issues simultaneously — like "downhill" and "into/away from the house" — adjust only one thing at a time. If you're unsure how to adjust your gutter machine, contact the Service department first.</strong></p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Adjust the green station</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>To fix gutters going into the house, adjust the swing shaft assembly (color-coded green). This assembly applies pressure to the head of the gutter and is the most common adjustment used to straighten the gutter.</p><!-- /wp:paragraph -->
<!-- wp:list {"ordered":true} --><ol><li>Loosen the two "J" bolts on top of the gutter box assembly enough that the swing shaft assembly can slide.</li><li>Turn the adjustment bolt "K" to move the swing shaft assembly.</li><li>To fix gutters going into the house, turn bolt "K" <strong>counterclockwise</strong> a quarter turn at a time.</li><li>After each adjustment, run an 8′ piece of gutter to check straightness.</li><li>If needed, repeat — and check the gutter lip before completing any adjustments.</li><li>While holding the swing shaft adjustment assembly so bolt "K" is against the box assembly, retighten bolts "J."</li></ol><!-- /wp:list -->
HTML,
    ],
    [
        'source_url'    => 'https://support.newtechmachinery.com/article/gutter-is-running-downhill/',
        'title'         => 'The Gutter Is Running "Downhill"',
        'machine_slugs' => ['mach-ii-5-gutter', 'mach-ii-6-gutter', 'mach-ii-combo-gutter', 'bg7-box-gutter'],
        'excerpt'       => 'Lower the blue-coded vertical axis (bolt "E" counterclockwise) to correct a gutter whose ends drop below the center.',
        'published'     => '2022-05-31',
        'image_slug'    => 'blue-station-adjustment-mach-ll-gutter-machine-video-2',
        'body'          => <<<'HTML'
<!-- wp:paragraph --><p>Looking at the bottom of your gutter from one end, it's "running downhill" when the gutter bows slightly downward — the ends are lower than the center.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Refer to your gutter machine manual before making any adjustments.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p><strong>If your machine has multiple issues simultaneously — like "downhill" and "into/away from the house" — adjust only one thing at a time. If you're unsure how to adjust your gutter machine, contact the Service department first.</strong></p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Adjust the blue station</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>To fix a downhill gutter, adjust the vertical axis (color-coded blue) on the exit mount adjustment assembly. Raising or lowering the box and rollers controls the uphill/downhill effect.</p><!-- /wp:paragraph -->
<!-- wp:list {"ordered":true} --><ol><li>Loosen the "F" bolt on top of the box assembly.</li><li>Turn the "E" bolt <strong>counterclockwise</strong> to lower the box and correct a downhill gutter.</li><li>Retighten the "F" bolts.</li></ol><!-- /wp:list -->
HTML,
    ],
    [
        'source_url'    => 'https://support.newtechmachinery.com/article/gutter-is-running-uphill/',
        'title'         => 'The Gutter Is Running "Uphill"',
        'machine_slugs' => ['mach-ii-5-gutter', 'mach-ii-6-gutter', 'mach-ii-combo-gutter', 'bg7-box-gutter'],
        'excerpt'       => 'Raise the blue-coded vertical axis (bolt "E" clockwise) to correct a gutter whose ends rise above the center.',
        'published'     => '2022-05-31',
        'image_slug'    => 'blue-station-adjustment-mach-ll-gutter-machine-video-2',
        'body'          => <<<'HTML'
<!-- wp:paragraph --><p>If there's a slight bow upward toward the sky when looking at the bottom of your gutter from one end, the gutter is "running uphill" — the ends are higher than the center.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Refer to your gutter machine manual before making any adjustments.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p><strong>If your machine has multiple issues simultaneously — like "uphill" and "into/away from the house" — adjust only one thing at a time. If you're unsure how to adjust your gutter machine, contact the Service department first.</strong></p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Adjust the blue station</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>To fix an uphill gutter, adjust the vertical axis (color-coded blue) on the exit mount adjustment assembly. Raising or lowering the box and rollers controls the uphill/downhill effect.</p><!-- /wp:paragraph -->
<!-- wp:list {"ordered":true} --><ol><li>Loosen the "F" bolt on top of the box assembly.</li><li>Turn the "E" bolt <strong>clockwise</strong> to raise the box and correct an uphill gutter.</li><li>Retighten the "F" bolts.</li></ol><!-- /wp:list -->
HTML,
    ],
    [
        'source_url'    => 'https://support.newtechmachinery.com/article/material-isnt-tracking-properly/',
        'title'         => 'Material Isn\'t Tracking Properly in the BG7™ Machine',
        'machine_slugs' => $bg7,
        'excerpt'       => 'Set up the entry guide and entry drum extension correctly so material enters and exits the BG7 in alignment.',
        'published'     => '2022-06-06',
        'image_slug'    => 'bg7-forming-gutter',
        'body'          => <<<'HTML'
<!-- wp:paragraph --><p>If the dimensions of the gutter coming out of the machine are incorrect, or the material has shape issues, the material isn't tracking properly. Making sure the Entry Guide or Entry Drum Extension is set up and aligned properly helps ensure your material enters and exits the machine in proper alignment.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Refer to the <a href="https://newtechmachinery.com/learning-center/manual/bg7-box-gutter-machine-with-plc-controller-manual/">BG7 machine manual</a> for proper setup of the Entry Guide and Entry Drum Extension.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>For coil mounted on top of the gutter machine, pull the Entry Drum up and tighten the bolts. For any coil loaded on the free-standing decoiler, move the Entry Drum down and tighten the bolts. When running your machine with the Back Flange Assembly, move the Entry Drum out. When running Straight Back or Hook profiles — and when transporting your machine to a job site — push the Entry Drum in.</p><!-- /wp:paragraph -->
HTML,
    ],
];
