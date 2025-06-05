## Executive Summary

The **OWBN Coordinator Toolkit** is a modular, extensible WordPress plugin designed to unify and streamline cross-site functionality across the OWBN web infrastructure. This plugin allows individual OWBN properties—such as `council.owbn.net`, `archivist.owbn.net`, and others—to coordinate content and functionality through shared tooling and webhook-based synchronization.

Each tool (or module) operates independently while adhering to a standard structure that supports:

- Site-local activation
- Master/remote designation for content authority
- Webhook messaging between sites
- Extensible admin UIs and content rendering

This toolkit is intended to be **the foundation for all cross-functional coordinator-level tooling** in OWBN’s online ecosystem.

## Overview

The OWBN Coordinator Toolkit is not a monolithic system, but a structured plugin framework where each **tool** (e.g., the Custom Content Database or Territory Voting System) is encapsulated in its own folder under `/tools/`.

Each site using this plugin may selectively activate one or more tools based on its responsibilities. Sites are also responsible for identifying which tools they are the **master** of. For example:

- `archivist.owbn.net` is master of the **CCDB** tool and serves authoritative content for it.
- `council.owbn.net` may be the master for **modules** such as voting, while relying on the archivist site to retrieve and store finalized documents.

The plugin includes the base bootstrapping logic, and each tool contains its own implementation logic, including:

- Custom Post Types (CPTs)
- Admin UIs
- Shortcodes
- Rendering logic
- JavaScript and CSS assets
- Webhook route definitions

## Core Design Principles

### Modular Tools

Each tool is a self-contained folder within `/tools/`:

- `ccdb/`
- `territory/`
- `_template/` (for scaffolding future tools)

Tools contain the following files:

- `init.php`: Loads the tool and registers hooks
- `cpt.php`: Registers post types, taxonomies
- `admin-ui.php`: Manages settings, metaboxes, admin menus
- `hooks.php`: Handles WordPress-level hooks
- `webhook.php`: Defines routes and receives external updates
- `shortcode.php`: Adds front-end shortcodes
- `render.php`: Shared render helpers and template utilities
- `readme.txt`: Tool-level documentation

Each tool may also define:

- `toolkit-<tool>.js`
- `toolkit-<tool>.css`

### Master Site Declarations

Each site declares which tools it is master for. This can be done via:

- PHP constants in `wp-config.php`
- An admin UI page (planned)
- A JSON config file (planned)

Other sites recognize that master and defer control or synchronize content accordingly via webhooks.

## File Structure

```
owbn-coordinator-toolkit/
├── assets/
│   ├── css/
│   │   ├── style.css
│   │   ├── toolkit-ccdb.css
│   │   └── toolkit-territory.css
│   └── js/
│       ├── toolkit.js
│       ├── toolkit-ccdb.js
│       └── toolkit-territory.js
├── includes/
│   ├── core/
│   │   ├── init.php
│   │   ├── helpers.php
│   │   └── webhook-router.php
│   ├── admin/
│   │   └── settings.php
│   └── render/
│       └── render-functions.php
├── tools/
│   ├── ccdb/
│   │   ├── admin-ui.php
│   │   ├── cpt.php
│   │   ├── hooks.php
│   │   ├── init.php
│   │   ├── render.php
│   │   ├── shortcode.php
│   │   ├── webhook.php
│   │   └── readme.txt
│   ├── territory/
│   │   └── (same structure)
│   └── _template/
│       └── (used to scaffold new tools)
├── languages/
│   └── owbn-coordinator-toolkit.pot
├── owbn-coordinator-toolkit.php
├── readme.txt
└── README.md
```

## Activation and Configuration

1. Upload or clone the plugin to `/wp-content/plugins/owbn-coordinator-toolkit/`.
2. Activate via the WordPress admin interface.
3. In `includes/core/init.php`, declare which tools to load by adding entries to the `$tools` array.
4. Optionally define master roles:

```
define( 'OWBN_CT_MASTER_FOR_CCDB', true );
define( 'OWBN_CT_MASTER_FOR_TERRITORY', false );
```

## Webhook Infrastructure

The webhook system is centralized in `includes/core/webhook-router.php`. Each tool registers its own webhook endpoints. These endpoints respond to authenticated requests from other OWBN sites and provide data syncing between masters and consumers.

Planned features include:

- Automatic webhook route discovery per tool
- Authenticated cross-domain dispatch
- Queue-based retry system
- Audit logging

## Tool Asset Loading

Each tool’s JS and CSS are isolated in `/assets/js/` and `/assets/css/`, named as:

- `toolkit-<tool>.js`
- `toolkit-<tool>.css`

They are conditionally enqueued by tool-specific hooks to avoid global bloat and preserve performance.

## Versioning and Metadata

Each file contains standardized headers including:

- `@version`
- `@author`
- `@tool` (where applicable)

These are automatically maintained to aid in build tooling and documentation sync.

## Future Enhancements

- Admin UI to manage tool activation and master declarations
- Dynamic discovery of available tools
- REST API compatibility
- Permission-based webhook routing
- Full multilingual support
- JSON-based tool manifest

## Changelog

### 0.1.0

- Initial plugin architecture defined
- Tool scaffolding established: `ccdb`, `territory`
- Asset structure implemented per tool
- Webhook router base introduced
- Readme generation standardized