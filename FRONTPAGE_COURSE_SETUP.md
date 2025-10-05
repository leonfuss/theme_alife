# Frontpage Course Display Setup

This document explains how to control which courses appear on the frontpage and in what order.

## Setup Instructions

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

#### Field 2: Frontpage Priority (Text field)
1. Click "Add a new custom field" → Select "Text input"
   - Short name: `frontpagepriority`
   - Name: `Frontpage Priority`
   - Description: `Priority for frontpage display (lower number = higher priority). Example: 1, 2, 3, etc.`
   - Default value: `999`
   - Click "Save changes"

### 2. Configure Courses

For each course you want to appear on the frontpage:

1. Go to the course
2. Click the gear icon (⚙️) → "Edit settings"
3. Scroll down to "Custom fields" section
4. Check "Show on Frontpage" ✓
5. Set "Frontpage Priority" to a number (1, 2, 3, etc.)
   - Lower numbers appear first
   - Example: Priority 1 = first position, Priority 2 = second position
6. Click "Save and display"

### 3. How It Works

- **Only courses with "Show on Frontpage" checked will appear on the frontpage**
- Courses are sorted by their priority number (1, 2, 3, etc.)
- If no courses have these fields set, all courses will be shown (backwards compatibility)

### Example Configuration

| Course Name | Show on Frontpage | Priority | Result |
|-------------|------------------|----------|--------|
| Introduction to Psychology | ✓ | 1 | Appears first |
| Digital Media | ✓ | 2 | Appears second |
| Advanced Statistics | ✓ | 3 | Appears third |
| Old Course | ✗ | - | Does not appear |

## Troubleshooting

**Q: I set up the custom fields but courses still show in the wrong order**
- Make sure you purge the Moodle cache: `php admin/cli/purge_caches.php`
- Check that the field shortnames are exactly: `showonfrontpage` and `frontpagepriority`

**Q: No courses are showing on the frontpage**
- Check that at least one course has "Show on Frontpage" enabled
- If no courses have the custom field set, all courses will show (backwards compatibility)

**Q: How do I change the order?**
- Edit the course settings and change the "Frontpage Priority" number
- Lower numbers appear first (1 before 2, 2 before 3, etc.)

## Technical Details

- Custom field short names: `showonfrontpage` (checkbox), `frontpagepriority` (text)
- Implementation: `theme/alife/classes/output/core/course_renderer.php`
- Sorting: Ascending by priority (lower number = higher priority)
- Default priority: 999 (if not set)
