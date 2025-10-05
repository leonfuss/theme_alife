# Frontpage Course Display Setup

This document explains how to control which courses appear on the frontpage and in what order.

## Automatic Setup

**The custom fields are created automatically when the theme is installed or upgraded!**

When you install or upgrade the ALIFE theme, it will automatically create:
- A custom field category called "Frontpage Settings"
- A checkbox field: "Show on Frontpage" (shortname: `showonfrontpage`)
- A text field: "Course Numbering" (shortname: `coursenumbering`)

You can verify they exist by going to **Site administration → Courses → Course custom fields**.

## Manual Setup (if needed)

If for some reason the fields weren't created automatically, you can create them manually:

### 1. Create Custom Course Fields

Go to **Site administration → Courses → Course custom fields**

#### Field 1: Show on Frontpage (Checkbox)
1. Click "Add a new category" (if you don't have one)
   - Name: `Frontpage Settings`
   - Click "Save changes"

2. Click "Add a new custom field" → Select "Checkbox"
   - Short name: `showonfrontpage`
   - Name: `Show on Frontpage`
   - Description: `Enable this to display this course on the site frontpage`
   - Click "Save changes"

#### Field 2: Course Numbering (Text field)
1. Click "Add a new custom field" → Select "Text input"
   - Short name: `coursenumbering`
   - Name: `Course Numbering`
   - Description: `Number to display on course card and for sorting. Example: 1, 2, 3, etc.`
   - Default value: `999`
   - Click "Save changes"

### 2. Configure Courses

For each course you want to appear on the frontpage:

1. Go to the course
2. Click the gear icon (⚙️) → "Edit settings"
3. Scroll down to "Custom fields" section
4. Check "Show on Frontpage" ✓
5. Set "Course Numbering" to a number (1, 2, 3, etc.)
   - Lower numbers appear first
   - The number will be displayed on the course card (01, 02, 03, etc.)
   - Example: Number 1 = displays "01" and appears first
6. Click "Save and display"

### 3. How It Works

- **Only courses with "Show on Frontpage" checked will appear on the frontpage**
- Courses are sorted by their numbering (1, 2, 3, etc.)
- The numbering is displayed on the course card as a badge
- If no courses have these fields set, all courses will be shown (backwards compatibility)

### Example Configuration

| Course Name | Show on Frontpage | Numbering | Card Display | Result |
|-------------|------------------|-----------|--------------|--------|
| Introduction to Psychology | ✓ | 1 | 01 | Appears first with "01" badge |
| Digital Media | ✓ | 2 | 02 | Appears second with "02" badge |
| Advanced Statistics | ✓ | 3 | 03 | Appears third with "03" badge |
| Old Course | ✗ | - | - | Does not appear |

## Troubleshooting

**Q: I set up the custom fields but courses still show in the wrong order**
- Make sure you purge the Moodle cache: `php admin/cli/purge_caches.php`
- Check that the field shortnames are exactly: `showonfrontpage` and `coursenumbering`

**Q: No courses are showing on the frontpage**
- Check that at least one course has "Show on Frontpage" enabled
- If no courses have the custom field set, all courses will show (backwards compatibility)

**Q: How do I change the order or displayed number?**
- Edit the course settings and change the "Course Numbering" value
- Lower numbers appear first (1 before 2, 2 before 3, etc.)
- The number you set will be displayed on the card (e.g., 1 shows as "01")

**Q: The number isn't displaying on my course card**
- Make sure the course has "Show on Frontpage" checked
- Make sure you've run the theme upgrade: Visit Site administration → Notifications
- Purge the cache after making changes

## Technical Details

- Custom field short names: `showonfrontpage` (checkbox), `coursenumbering` (text)
- Implementation: `theme/alife/classes/output/core/course_renderer.php`
- Sorting: Ascending by numbering (lower number = appears first)
- Number display: Uses `data-coursenumber` attribute and CSS `::before` pseudo-element
- Default numbering: 999 (if not set)
- Backwards compatibility: Also checks old `frontpagepriority` field name
