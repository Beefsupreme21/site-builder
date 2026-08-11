---
paths:
  - 'tests/**'
---

# Tests

## RefreshDatabase applied globally in Pest.php
RefreshDatabase is applied once in tests/Pest.php via ->use(...)->in('Feature'); do not add it per test file.

## Action tests live in tests/Feature/Actions/{Domain}
Every Action has a matching test at tests/Feature/Actions/{Domain}/{Action}Test.php that calls (new X)->handle(...) directly — no HTTP. Cover the happy path plus each validation rule with `})->throws(ValidationException::class);`. Controller tests live in tests/Feature/Controllers and assert routing, props, and redirects only.
