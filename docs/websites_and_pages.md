# Websites and Pages

The core of the monitoring system is built around **Websites** and **Pages**.

## Data Model

### Websites
A `Website` represents a project or domain that a user wants to monitor. It belongs to a `User`.

**Fields:**
- `id`: Unique identifier.
- `user_id`: The ID of the owner.
- `name`: Human-readable name (e.g., "Marketing Site").
- `base_url`: Optional root URL (e.g., "https://example.com").

**Relationships:**
- Belongs to a `User`.
- Has many `Pages`.

### Pages
A `Page` represents a specific URL that is monitored for uptime.

**Fields:**
- `id`: Unique identifier.
- `website_id`: Parent website.
- `url`: The full URL to check (e.g., "https://example.com/pricing").
- `is_up`: Boolean indicating current status.
- `last_checked_at`: Timestamp of the last check.
- `last_status_code`: HTTP status code of the last check.
- `last_response_time_ms`: Response time in milliseconds.

**Relationships:**
- Belongs to a `Website`.
- Has many `PageChecks` (history).
