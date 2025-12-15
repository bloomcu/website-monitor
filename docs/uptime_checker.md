## Website Uptime Checker

### 1. Scheduler (`routes/console.php`)
The scheduler runs hourly and dispatches a `CheckPageUptime` job for **every** page in the database.

### 2. Job: `CheckPageUptime`
This job is the orchestrator. It is responsible for:
1. Receiving a `Page` instance.
2. Calling the `PageUptimeChecker` service.
3. Creating a `PageCheck` history record.
4. Updating the `Page` model with the latest status.

### 3. Service: `PageUptimeChecker`
This is a stateless service that performs the actual work.
- Performs an HTTP GET request (10s timeout).
- Measures response time.
- Returns a standardized array: `['status', 'status_code', 'response_time_ms']`.
- Handles exceptions (e.g., timeouts, DNS failures) gracefully by returning a "down" status.

## Data Flow
1. **Scheduler** wakes up.
2. **Job** is pushed to queue.
3. **Worker** picks up Job.
4. **Service** pings URL.
5. **Job** saves result to DB.
