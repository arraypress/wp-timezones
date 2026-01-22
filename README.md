# WordPress Timezones

A lightweight PHP library for working with timezone data in WordPress. Simple, static methods for timezone identifiers, regions, offsets, and utilities.

## Features

- 🕐 Complete PHP timezone list (400+ timezones)
- 🌍 Region grouping and filtering
- 🎯 Simple static API - no instantiation needed
- 📝 Multiple output formats for dropdowns
- 🔍 Search and validation utilities
- ⏰ UTC offset calculation and formatting
- 🎨 Gutenberg/React compatible formats

## Installation
```bash
composer require arraypress/wp-timezones
```

## Basic Usage
```php
use ArrayPress\Timezones\Timezones;

// Get all timezones
$timezones = Timezones::all();
// Returns: ['Africa/Abidjan', 'Africa/Accra', ...]

// Get formatted label
$label = Timezones::get_label( 'America/New_York' ); // "America/New York"

// Check if timezone exists
if ( Timezones::exists( 'Europe/London' ) ) {
    // Valid timezone
}

// Validate and sanitize user input
$timezone = Timezones::sanitize( $_POST['timezone'] ); // "America/New_York" or null
```

## Dropdown Options
```php
// Gutenberg/React format (value/label)
$options = Timezones::get_value_label_options();
// Returns: [['value' => 'America/New_York', 'label' => 'America/New York'], ...]

// Key/value format
$options = Timezones::get_key_value_options();
// Returns: ['America/New_York' => 'America/New York', ...]

// With empty option
$options = Timezones::get_value_label_options( true, '— Select Timezone —' );

// With UTC offset in labels
$options = Timezones::get_options_with_offset();
// Returns: [['value' => 'America/New_York', 'label' => '(UTC-05:00) America/New York'], ...]

// Grouped by region
$grouped = Timezones::get_grouped_options();
// Returns: ['America' => [['value' => 'America/New_York', 'label' => 'New York'], ...], ...]
```

## UTC Offsets
```php
// Get offset in seconds
$offset = Timezones::get_offset( 'America/New_York' ); // -18000

// Get formatted offset string
$offset = Timezones::get_offset_string( 'America/New_York' ); // "-05:00"
$offset = Timezones::get_offset_string( 'Asia/Kolkata' );     // "+05:30"
```

## Regions
```php
// Get region from timezone
$region = Timezones::get_region( 'America/New_York' ); // "America"

// Get city from timezone
$city = Timezones::get_city( 'America/New_York' ); // "New York"

// Get all regions
$regions = Timezones::get_regions();
// Returns: ['Africa', 'America', 'Antarctica', 'Arctic', 'Asia', 'Atlantic', 'Australia', 'Europe', 'Indian', 'Pacific']

// Get timezones by region
$american = Timezones::get_by_region( 'America' );

// Get region options for dropdown
$options = Timezones::get_region_options();
```

## Search
```php
// Search by identifier
$results = Timezones::search( 'york' );
// Returns: ['America/New_York']

// Search with limit
$results = Timezones::search( 'america', 5 );
```

## Helper Functions

Global functions are available for convenience:
```php
// Get formatted label
$label = get_timezone_label( 'America/New_York' ); // "America/New York"

// Get UTC offset
$offset = get_timezone_offset( 'America/New_York' ); // -18000

// Get formatted offset string
$offset = get_timezone_offset_string( 'America/New_York' ); // "-05:00"

// Get region
$region = get_timezone_region( 'America/New_York' ); // "America"

// Get dropdown options
$options = get_timezone_options();

// Get all regions
$regions = get_timezone_regions();

// Validate timezone
if ( is_valid_timezone( 'Europe/London' ) ) {
    // Valid
}

// Sanitize user input
$timezone = sanitize_timezone( $_POST['timezone'] );
```

## API Reference

| Method                                    | Description               | Return    |
|-------------------------------------------|---------------------------|-----------|
| `all()`                                   | Get all timezones         | `array`   |
| `exists($timezone)`                       | Check if exists           | `bool`    |
| `sanitize($timezone)`                     | Validate/sanitize         | `?string` |
| `get_label($timezone)`                    | Get formatted label       | `string`  |
| `get_region($timezone)`                   | Get region                | `string`  |
| `get_city($timezone)`                     | Get city/location         | `string`  |
| `get_offset($timezone)`                   | Get UTC offset (seconds)  | `?int`    |
| `get_offset_string($timezone)`            | Get formatted offset      | `string`  |
| `get_regions()`                           | Get all regions           | `array`   |
| `get_by_region($region)`                  | Timezones in region       | `array`   |
| `search($term, $limit)`                   | Search timezones          | `array`   |
| `get_options($format, $empty, $label)`    | Get dropdown options      | `array`   |
| `get_key_value_options($empty, $label)`   | Key/value format          | `array`   |
| `get_value_label_options($empty, $label)` | Value/label format        | `array`   |
| `get_options_with_offset($empty, $label)` | Options with UTC offset   | `array`   |
| `get_grouped_options()`                   | Grouped by region         | `array`   |
| `get_region_options($empty, $label)`      | Region dropdown options   | `array`   |

## Requirements

- PHP 7.4 or higher
- WordPress 6.0 or higher

## License

GPL-2.0-or-later