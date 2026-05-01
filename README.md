# Custom Form Module - Complete Documentation

## Table of Contents

1. [Overview](#overview)
2. [Features](#features)
3. [Installation & Setup](#installation--setup)
4. [Configuration](#configuration)
5. [Usage](#usage)
6. [Database Schema](#database-schema)
7. [File Structure](#file-structure)
8. [Admin Panel Guide](#admin-panel-guide)
9. [Frontend Form Guide](#frontend-form-guide)
10. [Customization](#customization)
11. [Hooks & Filters](#hooks--filters)
12. [Troubleshooting](#troubleshooting)
13. [Security Considerations](#security-considerations)

---

## Overview

The Custom Form Module is a WordPress theme component that provides a complete form management system with:

- **Frontend Form**: A user-friendly form for collecting submissions
- **Admin Dashboard**: A professional admin panel for managing form entries
- **Security**: Google reCaptcha integration and comprehensive validation
- **File Uploads**: Support for multiple file types with size restrictions
- **Export Functionality**: Export filtered data to CSV

This module is built with:
- WordPress AJAX for smooth, non-blocking requests
- DataTables for advanced table management with sorting/filtering
- jQuery Validate for client-side form validation
- Google reCaptcha v2 for spam prevention
- Font Awesome icons for intuitive UI

---

## Features

### 🎯 Core Features

#### Frontend Form
- ✅ Minimal, responsive design
- ✅ Real-time form validation
- ✅ Required file upload with size & extension validation
- ✅ Google reCaptcha protection
- ✅ Error message display with color coding
- ✅ Support for: Name, Email, Phone, Message, File Upload
- ✅ SweetAlert notifications for feedback

#### Admin Dashboard
- ✅ Professional datatable interface
- ✅ Search functionality with debounce (800ms)
- ✅ Sort by any column
- ✅ Pagination with configurable rows per page
- ✅ Expandable row details (view full message)
- ✅ Download & Open file buttons
- ✅ Delete individual entries with confirmation
- ✅ Delete all entries with one click
- ✅ Export filtered data to CSV
- ✅ Processing loader with overlay
- ✅ Responsive design

### 🔒 Security Features

- WordPress nonce verification
- Input sanitization & escaping
- File type validation
- File size restrictions
- reCaptcha verification
- Server-side validation
- Secure file upload handling

### 📊 Admin Features

- Organized search section with background color
- Color-coded buttons (Green: Export, Red: Delete)
- Icon-based delete buttons
- File action buttons (Download, Open)
- Loading indicators for AJAX operations
- Professional table styling

---

## Installation & Setup

### Step 1: Theme Placement

The module is included in the Custom Form Theme at:
```
wp-content/themes/custom-form-theme/custom-form-module/
```

### Step 2: Verify Inclusion

The module is automatically loaded via `functions.php`:
```php
require_once get_template_directory() . '/custom-form-module/custom-form-module.php';
```

### Step 3: Create Database Table

The module automatically creates the required database table on theme activation. If it doesn't exist, create it manually by checking `database/create-table.php`.

### Step 4: Configure Settings

Edit `custom-form-module/config.php` with your settings (see Configuration section).

### Step 5: Add Form to Frontend

Use the shortcode on any page or post:
```
[custom_form]
```

---

## Configuration

### Step 1: Google reCaptcha Setup

1. Visit [Google reCaptcha Admin Console](https://www.google.com/recaptcha/admin)
2. Sign in with your Google account
3. Click "+" to create a new site
4. Configure:
   - **Display name**: Your site name
   - **reCaptcha type**: Select "reCaptcha v2" → "I'm not a robot" Checkbox
   - **Domains**: Add your domain(s)
5. Accept the reCaptcha terms and submit
6. Copy the **Site Key** and **Secret Key**

### Step 2: Update Configuration File

Edit `custom-form-module/config.php`:

```php
<?php
// Google reCaptcha Settings
define( 'CF_RECAPTCHA_SITE_KEY', 'YOUR_ACTUAL_SITE_KEY' );
define( 'CF_RECAPTCHA_SECRET_KEY', 'YOUR_ACTUAL_SECRET_KEY' );

// Bypass reCaptcha for testing (set to false in production)
define( 'CF_BYPASS_RECAPTCHA', false );

// File Upload Settings
define( 'CF_ALLOWED_FILE_EXTENSIONS', array( 'jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx' ) );
define( 'CF_MAX_FILE_SIZE', 5 * 1024 * 1024 ); // 5 MB in bytes
?>
```

### Configuration Options

| Constant | Type | Description | Default |
|----------|------|-------------|---------|
| `CF_RECAPTCHA_SITE_KEY` | String | Google reCaptcha Site Key | - |
| `CF_RECAPTCHA_SECRET_KEY` | String | Google reCaptcha Secret Key | - |
| `CF_BYPASS_RECAPTCHA` | Boolean | Skip reCaptcha verification (testing only) | `true` |
| `CF_ALLOWED_FILE_EXTENSIONS` | Array | Allowed file types | `['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']` |
| `CF_MAX_FILE_SIZE` | Integer | Maximum file size in bytes | `5242880` (5 MB) |

---

## Usage

### Frontend Form

#### Display Form

Add the shortcode to any page/post:
```
[custom_form]
```

#### Form Fields

1. **Name** (Required)
   - Minimum 3 characters
   - Text input

2. **Email** (Required)
   - Must be valid email format
   - Email input

3. **Phone** (Optional)
   - Must be 10 digits
   - Numeric input

4. **File Upload** (Required)
   - Allowed: JPG, JPEG, PNG, PDF, DOC, DOCX
   - Maximum: 5 MB
   - File input with validation

5. **Message** (Required)
   - Minimum 10 characters
   - Textarea

#### Submission Process

1. User fills form
2. Client-side validation checks format
3. reCaptcha verification (if enabled)
4. AJAX submission to backend
5. Server-side validation
6. File upload validation
7. Data saved to database
8. Success notification or error message

#### Error Handling

Errors are displayed in a red box with specific messages:
- "Name must be at least 3 characters"
- "Please enter a valid email"
- "File size exceeds maximum allowed size of 5 MB"
- "File type is not allowed"
- etc.

### Admin Panel

#### Accessing Admin Panel

Navigate to: **WordPress Admin → Custom Forms**

#### Features

**1. Search & Filter**
- Type in search box to filter by Name, Email, or Phone
- Results update with 800ms debounce to reduce server requests
- Search is case-insensitive

**2. Show Entries**
- Dropdown to select number of entries per page
- Options: 10, 25, 50, 100

**3. Table Columns**
- **Expand (+/-)**: View full message details
- **ID**: Entry ID (clickable for sorting)
- **Name**: Submitter's name
- **Email**: Submitter's email
- **Phone**: Phone number (if provided)
- **Created At**: Submission date/time
- **Actions**: Delete entry, Download file, Open file

**4. Expand Row Details**
- Click **+** to view full message and file
- Click **-** to collapse

**5. File Actions** (when file attached)
- **Download Icon (Blue)**: Download file to computer
- **Open Icon (Green)**: Open file in new browser tab

**6. Delete Entry**
- Click **Trash Icon** to delete single entry
- Confirmation dialog appears
- Entry deleted after confirmation

**7. Export Data**
- Click **Export to CSV** button
- Exports only filtered/searched results
- If no filter applied, exports all data
- File named: `custom-form-entries-YYYY-MM-DD.csv`

**8. Delete All**
- Click **Delete All Entries** button
- Confirmation dialog with warning
- Deletes all entries when confirmed
- Cannot be undone

---

## Database Schema

### Table Name
```
wp_custom_form_entries
```
(Prefix may vary based on WordPress installation)

### Column Structure

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT | Auto-incrementing primary key |
| `name` | VARCHAR(255) | Submitter's name |
| `email` | VARCHAR(255) | Submitter's email |
| `phone` | VARCHAR(20) | Phone number (optional) |
| `message` | LONGTEXT | Message content |
| `file_url` | VARCHAR(500) | URL to uploaded file |
| `created_at` | TIMESTAMP | Submission timestamp |

### Sample Query

```sql
SELECT * FROM wp_custom_form_entries 
WHERE name LIKE '%John%' 
ORDER BY created_at DESC
LIMIT 10;
```

---

## File Structure

```
custom-form-module/
├── README.md                          # Complete documentation
├── config.php                         # Configuration constants
├── custom-form-module.php             # Main module loader
│
├── admin/
│   ├── admin-menu.php                 # Admin menu registration
│   ├── admin-page.php                 # Admin page HTML & styles
│   ├── admin.js                       # DataTable & AJAX handlers
│   └── ajax-admin.php                 # Admin AJAX endpoints
│
├── frontend/
│   ├── form-shortcode.php             # Form HTML shortcode
│   ├── form.js                        # Form validation & submission
│   └── ajax-handler.php               # Frontend AJAX endpoints
│
├── database/
│   └── create-table.php               # Database table creation
│
└── exports/
    └── export-csv.php                 # CSV export functionality
```

### File Descriptions

#### `config.php`
- Centralized configuration
- reCaptcha keys
- File upload settings
- Easy to modify constants

#### `custom-form-module.php`
- Main loader file
- Includes all module files
- Enqueues scripts & styles
- Localizes AJAX data

#### `admin/admin-menu.php`
- Registers admin menu item
- Creates admin page

#### `admin/admin-page.php`
- Admin page HTML markup
- CSS styling for datatable
- Search section styling

#### `admin/admin.js`
- DataTables initialization
- AJAX handlers (delete, export)
- Debounced search
- Event handlers

#### `admin/ajax-admin.php`
- `cf_get_entries_ajax()` - Fetch entries for datatable
- `cf_get_entry_details_ajax()` - Fetch message details
- `cf_delete_entry_ajax()` - Delete single entry
- `cf_delete_all_entries_ajax()` - Delete all entries
- `cf_export_csv_ajax()` - Export filtered data to CSV

#### `frontend/form-shortcode.php`
- HTML form markup
- Form fields
- reCaptcha widget
- Nonce field

#### `frontend/form.js`
- jQuery Validate initialization
- Custom validation rules
- AJAX form submission
- Error display handling
- reCaptcha reset on success

#### `frontend/ajax-handler.php`
- `cf_verify_recaptcha()` - Verify reCaptcha token
- `cf_submit_form_ajax()` - Process form submission
- File upload validation
- Database insertion

#### `database/create-table.php`
- Database table creation
- Column definitions
- Indexes

#### `exports/export-csv.php`
- CSV file generation
- Data formatting
- Browser download

---

## Admin Panel Guide

### Dashboard Overview

```
┌─────────────────────────────────────────┐
│          Custom Forms                   │
├─────────────────────────────────────────┤
│  [Export to CSV] [Delete All Entries]  │
├─────────────────────────────────────────┤
│ ┌─────────────────────────────────────┐ │
│ │ Show Entries: [▼] Search: [_____]  │ │
│ └─────────────────────────────────────┘ │
├─────────────────────────────────────────┤
│ + │ ID │ Name │ Email │ Phone │ ... │ │
│─────────────────────────────────────────│
│ + │ 1  │ John │ j@... │ 1234  │ ... │ │
│   │    │ Message: "Hello world..."      │
│   │    │ File: [Download] [Open]       │
├─────────────────────────────────────────┤
│ Showing 1 to 1 of 1 entries             │
└─────────────────────────────────────────┘
```

### Common Tasks

#### Search Entries
1. Navigate to Custom Forms admin page
2. Type in search box
3. Results filter automatically
4. Click any column header to sort

#### View Full Message
1. Click **+** icon in the first column
2. Message details expand below row
3. Click **-** to collapse

#### Download Attached File
1. Locate entry with attachment
2. Click **Blue Download Icon**
3. File downloads to computer

#### Open File Online
1. Locate entry with attachment
2. Click **Green Open Icon**
3. File opens in new browser tab

#### Delete Single Entry
1. Click **Trash Icon** in Actions column
2. Confirmation dialog appears
3. Click **Delete** to confirm
4. Entry removed from table

#### Export Data
1. (Optional) Filter data using search box
2. Click **Export to CSV** button
3. CSV file downloads automatically
4. Open in Excel, Google Sheets, etc.

#### Delete All Entries
1. Click **Delete All Entries** button
2. Warning dialog appears
3. Click **Delete All** to confirm
4. All entries deleted immediately

---

## Frontend Form Guide

### Form Display

Form is displayed using shortcode:
```
[custom_form]
```

### Form Layout

```
┌────────────────────────────────┐
│     Custom Form                │
├────────────────────────────────┤
│ Name *                         │
│ [_________________________]    │
│                                │
│ Email *                        │
│ [_________________________]    │
│                                │
│ Phone                          │
│ [_________________________]    │
│                                │
│ File Upload *                  │
│ [Choose File]                  │
│ Allowed: JPG, PNG, PDF...      │
│                                │
│ Message *                      │
│ [__________________________]   │
│ [__________________________]   │
│                                │
│ [☐] I'm not a robot (reCaptcha)
│                                │
│ [        SUBMIT        ]        │
└────────────────────────────────┘
```

### Validation Rules

| Field | Rules | Example |
|-------|-------|---------|
| Name | Required, Min 3 chars | "John Doe" ✅ |
| Email | Required, Valid format | "john@example.com" ✅ |
| Phone | Optional, 10 digits if filled | "9876543210" ✅ |
| File | Required, Valid type, < 5MB | "resume.pdf" ✅ |
| Message | Required, Min 10 chars | "This is my message..." ✅ |

### Error Messages

```
┌──────────────────────────────────────┐
│ ⚠ Required fields are missing        │
└──────────────────────────────────────┘
```

Errors display in red box with specific messages for each field.

---

## Customization

### Modifying Form Fields

Edit `frontend/form-shortcode.php`:

```php
<div>
    <label for="cf-custom">Custom Field *</label>
    <input type="text" id="cf-custom" name="custom" required>
</div>
```

Update `frontend/form.js` validation rules:

```javascript
custom: {
    required: true,
    minlength: 3
},
```

Update database table in `database/create-table.php`:

```php
$sql .= "custom VARCHAR(255),";
```

Update AJAX handler in `frontend/ajax-handler.php`:

```php
$custom = sanitize_text_field( $_POST['custom'] ?? '' );
```

### Changing Colors

**Admin Page** - Edit `admin/admin-page.php`:
```php
background-color: #f8f9fa;  /* Search section background */
color: #dc3545;              /* Delete button color */
color: #28a745;              /* Export button color */
```

**Frontend Form** - Edit `style.css`:
```php
background-color: #f9f9f9;   /* Form background */
border-color: #007cba;       /* Input border on focus */
background-color: #f8d7da;   /* Error message background */
color: #721c24;              /* Error text color */
```

### Changing File Allowed Types

Edit `config.php`:

```php
define( 'CF_ALLOWED_FILE_EXTENSIONS', array( 'jpg', 'png', 'pdf', 'zip' ) );
```

### Increasing File Size Limit

Edit `config.php`:

```php
define( 'CF_MAX_FILE_SIZE', 10 * 1024 * 1024 ); // 10 MB
```

### Custom Validation Messages

Edit `frontend/form.js`:

```javascript
messages: {
    name: {
        required: "Your custom message",
        minlength: "Another custom message"
    },
    // ... more fields
}
```

---

## Hooks & Filters

### Available Hooks

#### Frontend Hooks

**Hook**: `cf_before_form`
```php
do_action( 'cf_before_form' );
```
Fires before form HTML is rendered.

**Hook**: `cf_after_form`
```php
do_action( 'cf_after_form' );
```
Fires after form HTML is rendered.

**Hook**: `cf_before_submission`
```php
do_action( 'cf_before_submission', $data );
```
Fires before data is saved to database.

**Hook**: `cf_after_submission`
```php
do_action( 'cf_after_submission', $entry_id, $data );
```
Fires after entry is successfully saved.

#### Admin Hooks

**Hook**: `cf_admin_page_title`
```php
apply_filters( 'cf_admin_page_title', 'Custom Forms' );
```
Filters admin page title.

**Hook**: `cf_table_columns`
```php
apply_filters( 'cf_table_columns', $columns );
```
Filters datatable columns.

### Custom Hook Example

In your theme's `functions.php`:

```php
add_action( 'cf_after_submission', function( $entry_id, $data ) {
    // Send email notification
    wp_mail( 'admin@example.com', 'New Form Submission', 'Entry #' . $entry_id );
}, 10, 2 );
```

---

## Troubleshooting

### Common Issues

#### reCaptcha Not Showing

**Problem**: reCaptcha widget missing on form

**Solutions**:
1. Check `CF_RECAPTCHA_SITE_KEY` is set in `config.php`
2. Verify `CF_BYPASS_RECAPTCHA` is `false`
3. Clear browser cache
4. Check browser console for errors (F12)
5. Ensure JavaScript is enabled

#### File Upload Fails

**Problem**: Getting file upload error

**Solutions**:
- Check file size < 5 MB
- Verify file extension is allowed
- Check WordPress upload directory permissions (755 or 775)
- Verify server upload_max_filesize setting
- Check `CF_MAX_FILE_SIZE` in config.php

#### Search Not Working

**Problem**: Search box not filtering results

**Solutions**:
1. Check browser console for JS errors
2. Clear browser cache
3. Verify database table exists
4. Check AJAX URL is correct
5. Verify nonce is valid
6. Check server error logs

#### Admin Page Blank

**Problem**: Admin page shows nothing

**Solutions**:
1. Check `database/create-table.php` for errors
2. Verify database table exists: `wp_custom_form_entries`
3. Check WordPress error logs
4. Verify jQuery is loaded
5. Check DataTables CSS is loaded

#### Form Validation Not Working

**Problem**: Invalid data being submitted

**Solutions**:
1. Clear browser cache
2. Check `frontend/form.js` is loaded
3. Verify jQuery Validate library is loaded
4. Check browser console for errors
5. Verify form IDs match JavaScript selectors

### Debug Mode

Enable WordPress debug mode in `wp-config.php`:

```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
```

Check logs at: `wp-content/debug.log`

### Browser Developer Tools

1. Open Developer Tools (F12)
2. Go to **Network** tab
3. Submit form
4. Check AJAX requests
5. Look at **Response** for error details
6. Check **Console** for JavaScript errors

---

## Security Considerations

### ✅ Implemented Security

1. **WordPress Nonce Verification**
   - All AJAX requests verified with nonce
   - Protection against CSRF attacks

2. **Input Sanitization**
   - All inputs sanitized using WordPress functions
   - `sanitize_text_field()`, `sanitize_email()`, etc.

3. **Output Escaping**
   - All output escaped using `esc_html()`, `esc_url()`, etc.
   - Protection against XSS attacks

4. **File Upload Security**
   - File type validation
   - File size limits
   - Extension whitelist
   - Uses WordPress `wp_handle_upload()`

5. **Google reCaptcha**
   - Server-side verification
   - Token validation
   - Protection against bot submissions

6. **Database Security**
   - Prepared statements
   - Parameterized queries
   - Protection against SQL injection

### 📋 Security Checklist

Before deploying to production:

- [ ] Set `CF_BYPASS_RECAPTCHA` to `false`
- [ ] Update `CF_RECAPTCHA_SITE_KEY` with real key
- [ ] Update `CF_RECAPTCHA_SECRET_KEY` with real key
- [ ] Set proper file permissions (755 for directories)
- [ ] Enable WordPress debug logging
- [ ] Use HTTPS for reCaptcha
- [ ] Test file upload restrictions
- [ ] Verify nonce validation
- [ ] Test form with invalid inputs
- [ ] Check database backups

### 🔐 Additional Hardening

1. **Limit File Types**
   ```php
   define( 'CF_ALLOWED_FILE_EXTENSIONS', array( 'pdf', 'doc', 'docx' ) );
   ```

2. **Reduce File Size**
   ```php
   define( 'CF_MAX_FILE_SIZE', 2 * 1024 * 1024 ); // 2 MB
   ```

3. **Rate Limiting** (add to `ajax-handler.php`)
   ```php
   // Implement rate limiting here
   ```

4. **Email Notifications** (add to `ajax-handler.php`)
   ```php
   wp_mail( 'admin@example.com', 'New Submission', $data );
   ```

---

## Performance Optimization

### Frontend Optimizations

1. **Debounced Search** (800ms)
   - Reduces server requests
   - Configured in `admin.js`

2. **AJAX Submission**
   - Non-blocking form submission
   - Smooth user experience

3. **Lazy Load Files**
   - Files loaded on expand only

### Backend Optimizations

1. **Database Indexing**
   - Indexes on `id`, `email`, `created_at`

2. **Pagination**
   - DataTables default: 10 per page
   - Configurable: 10, 25, 50, 100

3. **Search Optimization**
   - LIKE queries on indexed columns
   - Limited result set

---

## Frequently Asked Questions

### Q: How do I change the form fields?
A: Edit `frontend/form-shortcode.php`, update validation in `frontend/form.js`, and modify database in `database/create-table.php`.

### Q: Can I customize the admin table?
A: Yes, edit `admin/admin.js` and `admin/admin-page.php` to modify columns and styling.

### Q: How do I disable reCaptcha?
A: Set `CF_BYPASS_RECAPTCHA` to `true` in `config.php` (testing only).

### Q: Where are uploaded files stored?
A: In WordPress upload directory: `wp-content/uploads/`

### Q: Can I export data in other formats?
A: Currently CSV only. To add other formats, modify `exports/export-csv.php`.

### Q: How do I send email notifications?
A: Add a hook in your theme's `functions.php` using `cf_after_submission` hook.

### Q: Can I add more custom fields?
A: Yes, see "Customization" section above.

### Q: How do I change the admin menu position?
A: Edit `admin/admin-menu.php` and change `add_menu_page()` parameters.

---

## Support & Contact

For issues or questions:

1. **Check Documentation**: Review this file first
2. **Check Troubleshooting**: See troubleshooting section
3. **Enable Debug Mode**: Enable WP_DEBUG to see errors
4. **Check Browser Console**: F12 → Console for JavaScript errors
5. **Review Code Comments**: Inline comments in PHP files

---

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0 | May 2026 | Initial release |

---

## License

This module is part of the Custom Form Theme for WordPress.

---

## Thank You!

Thank you for using the Custom Form Module. We hope it serves your form management needs well!

For feedback or feature requests, please consider the customization and extension options provided in this documentation.

---

**Last Updated**: May 1, 2026  
**Module Version**: 1.0  
**WordPress Compatibility**: 5.0+  
**PHP Version**: 7.4+

### Google reCaptcha Configuration

1. **Get reCaptcha Keys:**
   - Visit [Google reCaptcha Admin Console](https://www.google.com/recaptcha/admin)
   - Create a new site
   - Choose reCaptcha v2 (I'm not a robot)
   - Copy the Site Key and Secret Key

2. **Update Configuration:**
   Open `custom-form-module/config.php` and update:

   ```php
   define( 'CF_RECAPTCHA_SITE_KEY', 'YOUR_RECAPTCHA_SITE_KEY' );
   define( 'CF_RECAPTCHA_SECRET_KEY', 'YOUR_RECAPTCHA_SECRET_KEY' );
   ```

3. **Enable/Disable reCaptcha:**
   ```php
   define( 'CF_BYPASS_RECAPTCHA', false ); // Set to true to disable for testing
   ```

### File Upload Configuration

Configure allowed file types and max file size:

```php
define( 'CF_ALLOWED_FILE_EXTENSIONS', array( 'jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx' ) );
define( 'CF_MAX_FILE_SIZE', 5 * 1024 * 1024 ); // 5 MB in bytes
```

## Features

### Admin Panel Features

1. **Search & Filter Section**
   - Background-colored section with search box and "Show Entries" dropdown
   - Debounced search to reduce server requests (800ms delay)
   - Responsive layout

2. **Enhanced Loader**
   - Black overlay with spinner and "Loading..." text
   - Positioned in the center of the page
   - Appears while AJAX requests are processing

3. **File Management Buttons**
   - **Download Button** (Blue): Download attached files
   - **Open Button** (Green): Open files in a new tab
   - Appears only if a file is attached

4. **Export & Delete**
   - **Export to CSV**: Export filtered/searched data only
   - **Delete All**: Delete all entries with confirmation
   - Color-coded buttons (Green for export, Red for delete)

### Frontend Form Features

1. **Validation**
   - Required fields marked with *
   - Real-time validation with error messages
   - File validation (size, extension, required)

2. **Google reCaptcha**
   - Prevents spam submissions
   - Can be bypassed via `CF_BYPASS_RECAPTCHA` for testing

3. **Error Display**
   - Red-colored error box for validation errors
   - Clear error messages for each field

4. **File Upload**
   - Required file upload field
   - Allowed extensions displayed
   - Max file size validation (5 MB)

## Troubleshooting

### reCaptcha Not Showing

- Make sure you've entered the Site Key in config.php
- Verify that `CF_BYPASS_RECAPTCHA` is set to `false`
- Check browser console for any JavaScript errors

### File Upload Issues

- Check file size is under 5 MB
- Verify file extension is in the allowed list
- Ensure WordPress upload directory has proper permissions

### Search Not Working

- Clear browser cache
- Verify database table exists
- Check server logs for AJAX errors

## File Structure

```
custom-form-module/
├── config.php (Configuration constants)
├── custom-form-module.php (Main loader)
├── admin/
│   ├── admin-page.php
│   ├── admin.js
│   └── ajax-admin.php
├── frontend/
│   ├── form-shortcode.php
│   ├── form.js
│   └── ajax-handler.php
├── database/
│   └── create-table.php
└── exports/
    └── export-csv.php
```

## Support

For issues or questions, please check the plugin files for inline documentation.
