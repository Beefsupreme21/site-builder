---
paths:
  - 'app/Actions/**'
---

# Actions

## Actions own validation with inline Validator
Validation lives INSIDE each Action via Validator::make()->validate() so the guarantee travels with the operation (callable from HTTP, queue, commands, tests). Controllers pass request()->all() and skip FormRequests. Input normalization (e.g. ColorPalette::normalizeHex) happens at the top of handle(), before Validator::make().

## Wrap all DB writes in DB::transaction()
Every Action that performs any DB write wraps that write in DB::transaction(). Group all writes (create + create child, swap + swap, etc.) inside a single transaction closure. Validation via Validator::make()->validate() stays OUTSIDE the transaction. Return values come from the closure.

## Actions are plain handle() classes, no container
Domain actions are plain classes exposing a single public handle() entry point. Dependencies (models, request()->all(), ids) are passed as method arguments. Do not register actions in the container, constructor-inject, or use app()/resolve().
