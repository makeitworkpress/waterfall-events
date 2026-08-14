# Waterfall Events

Waterfall Events upgrades the [Waterfall WordPress theme](https://makeitwork.press/wordpress-themes/waterfall/) with event capabilities: an `events` post type, event metadata (dates, price, registration, locations, organizers), and front-end views including a FullCalendar calendar, a Google Maps map with clustering and filtering, and an events list.

The plugin is an *extension* of Waterfall rather than a standalone plugin. It does not register its own admin page or its own asset pipeline; instead it injects configuration arrays into the theme's configuration handler, which then performs the actual registration through the theme's `WP_Register`, `WP_Enqueue` and `WP_Custom_Fields` packages.

- **Version:** 0.1.8
- **Requires:** WordPress, the Waterfall theme (as template or parent), PHP 7.0+
- **Recommended:** Elementor (needed to place the widgets), a Google Maps API key
- **Text domain:** `wfe`
- **License:** GPL-3.0

## Table of contents

- [Installation](#installation)
- [Configuration](#configuration)
- [What the plugin registers](#what-the-plugin-registers)
- [Event fields](#event-fields)
- [Elementor widgets](#elementor-widgets)
- [Components and templates](#components-and-templates)
- [Multisite](#multisite)
- [Hook reference](#hook-reference)
- [Architecture](#architecture)
- [Development](#development)
- [Caveats](#caveats)

## Installation

1. Install and activate the Waterfall theme (or a child theme with `Template: waterfall`). Without it the plugin disables itself and shows an admin notice.
2. Drop this plugin into `wp-content/plugins/waterfall-events` and activate it.
3. Go to **Settings → Permalinks** and press *Save*. The plugin registers a post type and four taxonomies but does not flush rewrite rules itself, so this step is required for `/events/…` URLs to resolve.
4. Set a Google Maps API key under **Waterfall → Maps API Key** if you intend to use the map, the single-event location map, or the address autocomplete in the admin.

The plugin keeps itself up to date from its GitHub repository through `makeitworkpress/wp-updater`, which is bundled in `vendor/`.

## Configuration

Settings are appended to the theme's own options screen rather than a separate page.

**WP Admin → Waterfall → Event Settings** (stored in the `waterfall_options` option):

| Setting | Description |
| --- | --- |
| `events_currency` | Global currency symbol, used as a fallback when an event has no `wfe_currency` of its own. |
| `events_cluster_icon_path` | URL to a directory containing custom marker cluster icons named `m1.png` … `m5.png`. Defaults to the bundled icons in `assets/img/`. |
| `events_enable_syncing` | Multisite only, super admins only. Reveals the per-event cross-site sync fields. |
| `events_calendar_multisite_source` | Multisite only, super admins only. Lets the calendar widget pull events from every public site in the network. |

The Google Maps key is a *theme* option (`maps_api_key`), shared with all other Waterfall extensions.

## What the plugin registers

Defined in `config/general.php` and handed to the theme as the `register` configuration.

**Post type `events`** — hierarchical, has an archive, `show_in_rest`, `dashicons-calendar`, menu position 20. Supports `author`, `comments`, `editor`, `excerpt`, `thumbnail`, `title` and `custom-fields`.

**Taxonomies** (all on `events`, all `show_in_rest`, all shown as admin columns):

| Taxonomy | Hierarchical | Rewrite slug |
| --- | --- | --- |
| `events_category` | yes | `events/category` |
| `events_tag` | no | `events/tag` |
| `events_location` | yes | `events/location` |
| `events_organizer` | yes | `events/organizer` |

All slugs pass through `_x()` and are therefore translatable. The plugin also removes the core attachment rewrite rule `events/[^/]+/([^/]+)/?$` via `rewrite_rules_array`, because it would otherwise shadow the taxonomy archives.

## Event fields

Registered in `config/meta.php` and rendered by the theme's `WP_Custom_Fields` package.

### Event post meta — "Event Settings" box

| Meta key | Type | Notes |
| --- | --- | --- |
| `wfe_type` | select | `normal` (default) or `multiday`. |
| `wfe_description` | textarea | Short description, used by the summary and the map info window. |
| `wfe_registration` | url | Registration link. |
| `wfe_registration_label` | text | Button label, falls back to *Register*. |
| `wfe_multiday_date` | repeatable | Only for `multiday` events. Rows of `title`, `date`, `starttime`, `endtime`. |
| `wfe_startdate` / `wfe_enddate` | datepicker | Stored as Unix timestamps. |
| `wfe_starttime` / `wfe_endtime` | time | `HH:MM` strings. |
| `wfe_currency` | text | Overrides the global `events_currency`. |
| `wfe_cost` | number | Event price. |
| `wfe_website` | url | Event website. |
| `wfe_location` | location | Composite field with `street`, `number`, `postal_code`, `city`, `country`, `lat`, `lng`. |
| `wfe_location_icon` | media | Custom map marker for this event. |
| `wfe_disable_components` | checkbox | Suppresses the automatic summary/details/organizers/locations output on the single event. |
| `wfe_sort_date` | *derived* | Integer timestamp written on `save_post`, used for sorting events chronologically. Not a UI field. |

### Term meta

- **`events_organizer`** → `wfe_organizer_meta` with `email`, `phone`, `website`.
- **`events_location`** → `wfe_location_meta` with a `location` composite field, plus `email`, `phone`, `website` and `location_icon`.

An event's location may come from either its own `wfe_location` meta or from an assigned `events_location` term; the components prefer the post meta and fall back to the term when no coordinates are present.

## Elementor widgets

Elementor is the intended way to output events. All three widgets appear under the **Waterfall** widget category. There are no shortcodes, and the archive template is not modified.

- **Events Calendar** (`wfe-calendar`) — FullCalendar 5.9. Month, week, day or list view; optional network-wide source on multisite. Extensive styling controls for the header, table, events and list view.
- **Events Map** (`wfe-map`) — Google Maps. Center coordinates, zoom, fit-to-bounds, marker clustering with a configurable grid size, custom map JSON styles, height and border radius. Optional visitor-facing filters for country, category and tag.
- **Events List** (`wfe-events`) — list or grid of events with configurable columns, gap, excerpt, location, date, price, categories, tags and registration button. Sorts by event date (via `wfe_sort_date`), publication date or title, with optional pagination and `schema.org/Event` markup.

Both the calendar and the map emit their data as an inline configuration object in the footer (`wfeCalendar<id>` / `wfeMap<id>`) which the front-end JavaScript picks up. Both re-initialise inside the Elementor editor preview.

## Components and templates

Everything the plugin renders goes through `Waterfall_Events\Views\Components\*`, all extending the abstract `Component` class:

```php
use Waterfall_Events\Views\Components as Components;

// Echo a component
new Components\Dates( ['id' => get_the_ID(), 'separator' => '—'], true, true );

// Or capture its markup
$summary = new Components\Summary( ['post_id' => get_the_ID()] );
$markup  = $summary->render( false );
```

| Component | Renders |
| --- | --- |
| `Summary` | Description, registration button and a nested `Dates` component. |
| `Dates` | Formatted start/end dates and times, collapsing multi-day events to first and last day when summarised. |
| `Details` | A card with dates, price, categories, tags, website and registration link. |
| `Price` | Currency and amount. |
| `Organizers` | `events_organizer` terms with their contact details. |
| `Locations` | Addresses plus an embedded map. |
| `Map` | Map canvas with optional filter selects. |
| `Calendar` | Mount point for FullCalendar. |
| `Events` | Delegates to the theme's `posts` molecule. |

`Component::render()` resolves `templates/components/{name}.php`, extracts `$props` into local variables and includes the file. On single events, `Views\Single_Events` renders `Summary` on `components_content_before` and `Details` + `Organizers` + `Locations` on `components_content_after`, unless `wfe_disable_components` is set.

### Overriding a template

There is **no** `locate_template()` lookup, so dropping files into your theme has no effect. Override the path with a filter instead:

```php
add_filter( 'wfe_components_template_dates', function( $template ) {
    return get_stylesheet_directory() . '/waterfall-events/dates.php';
} );

add_filter( 'wfe_components_props_dates', function( $props ) {
    $props['separator'] = 'until';
    return $props;
} );
```

## Multisite

When `events_enable_syncing` is on, each event gets a per-site switch (`wfe_event_sync_<blog_id>`) and a target selector (`wfe_event_sync_target_<blog_id>`). On save, `Controllers\Events::sync_save_events()` copies the event to each selected site: post content, all post meta, the featured image and the custom marker image are sideloaded, and the created post ID is written back so subsequent saves update rather than duplicate.

Syncing is one-way, and **taxonomy terms are not copied**. The target ID is exposed through the REST API so the block editor script can back-fill it after the first save.

## Hook reference

| Hook | Type | Description |
| --- | --- | --- |
| `wfe_language_path` | filter | Path passed to `load_plugin_textdomain`. |
| `waterfall_events_configurations` | filter | The full configuration array before it is injected into the theme. Only the keys `register`, `options`, `enqueue` and `elementor` are honoured. |
| `wfe_components_template_{name}` | filter | Absolute path to a component template. |
| `wfe_components_props_{name}` | filter | The props array passed to a component template. |

`{name}` is the lowercased component name: `calendar`, `dates`, `details`, `locations`, `map`, `organizers`, `price`, `summary`.

## Architecture

```
waterfall-events.php        Theme check, autoloader, boots Plugin on plugins_loaded
classes/
  class-plugin.php          Singleton, injects configurations on after_setup_theme @5
  class-base.php            Abstract declarative action/filter registration
  class-helper.php          Small static helpers
  controllers/              save_post logic: sort date + multisite syncing
  views/                    Single event output, components, Elementor widgets
config/                     Configuration arrays consumed by the theme
templates/components/       Component markup
assets/
  scripts/                  TypeScript sources (bundled by esbuild)
  less/                     LESS sources (compiled by esbuild)
  js/, css/                 Build output, committed so the plugin runs without a build step
  vendor/                   Vendored FullCalendar and MarkerClustererPlus
```

`Plugin::setup()` runs on `after_setup_theme` at priority **5**, deliberately before the theme's own `execute_configuration()` at priority 10, so the plugin's post types, fields and assets are merged in before anything is registered.

Class loading uses a custom `spl_autoload_register` (no Composer autoloader): `Waterfall_Events\Views\Components\Calendar` maps to `classes/views/components/class-calendar.php`, and `MakeitWorkPress\<Package>\<Class>` maps to `vendor/makeitworkpress/<package>/src/<Class>.php`.

### Constants

- `WFE_PATH` — plugin directory path
- `WFE_URI` — plugin directory URL

## Development

The front-end is written in TypeScript and LESS, and bundled with [esbuild](https://esbuild.github.io/).

```sh
npm install
npm run build        # bundle TypeScript and LESS, minified
npm run watch        # rebuild on change
npm run type-check   # tsc --noEmit, esbuild itself does not type-check
```

| Source | Output |
| --- | --- |
| `assets/scripts/waterfall-events.ts` | `assets/js/waterfall-events.min.js` |
| `assets/scripts/wfe-admin.ts` | `assets/js/wfe-admin.min.js` |
| `assets/less/waterfall-events.less` | `assets/css/waterfall-events.min.css` |

The build is defined in `esbuild.mjs`, which registers a small esbuild plugin that hands `.less` entry points to the `less` compiler so all `@import` directives are resolved in one place and partials are tracked in watch mode.

### Script layout

```
assets/scripts/
  waterfall-events.ts       Front-end entry point, boots every module on document ready
  wfe-admin.ts              Block editor entry point, back-fills the multisite sync fields
  globals.d.ts              Ambient declarations for wfe, FullCalendar, MarkerClusterer, wp, jQuery, elementorFrontend
  types.ts                  Shapes of the config objects that PHP prints into the footer
  utils.ts                  ready(), getGlobalConfig(), isElementorPreview()
  modules/
    calendar.ts             FullCalendar setup
    calendar-elementor.ts   Re-init inside the Elementor editor preview
    map.ts                  Google Maps setup, markers, info windows, clustering, filtering
    map-elementor.ts        Re-init inside the Elementor editor preview
```

`types.ts` mirrors `Views\Components\Map::echo_config_JS()` and `Views\Components\Calendar::echo_config_JS()`. When you change what those methods print, update the interfaces alongside them.

Type definitions for the Google Maps JavaScript API come from `@types/google.maps`. The other runtime globals are enqueued separately by WordPress or the theme and are only described in `globals.d.ts`.

PHP dependencies come from Composer, but are loaded by the plugin's own autoloader:

```sh
composer install
```

## Caveats

- **Elementor is effectively required** to display the calendar, map or events list. There are no shortcodes and the events archive is not customised.
- **The map needs a Google Maps API key** on the theme options page, otherwise the map canvas renders empty and the admin address autocomplete does not work.
- **Permalinks must be flushed manually** after activation.
- The calendar and the map both query *all* published events with `posts_per_page => -1` and inline them into the page. This does not scale to very large event archives.
- Map filtering happens entirely client-side on the already-loaded marker set; no AJAX request is involved.
- Assets (FullCalendar, MarkerClusterer, `waterfall-events.min.js`, the plugin CSS) are enqueued on every front-end page rather than conditionally.
- `Waterfall_Events\Ajax` and `Waterfall_Events\Views\Archive_Events` are currently empty placeholders, and `config/customizer.php` is a stub that is not loaded.
