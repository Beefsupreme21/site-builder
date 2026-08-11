---
paths:
  - 'database/migrations/**'
---

# Migrations

## One-way migrations, no down()
Migrations do not define a down() method. Rollbacks are not used or tested, so new migrations can be one-way (up() only).
