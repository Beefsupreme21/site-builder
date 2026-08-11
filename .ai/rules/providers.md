---
paths:
  - 'app/Providers/**'
---

# Providers

## Immutable dates via Date::use(CarbonImmutable::class)
Dates are globally immutable: Date::use(CarbonImmutable::class) is set in AppServiceProvider. Use now()/today() helpers and expect immutable dates; do not mutate or call mutable Carbon methods.

## The block route parameter uses an explicit binder
RouteFacade::bind('block', ...) in AppServiceProvider resolves BlockPage itself, scoping to the page parameter when one is present. An explicit binder takes precedence over implicit binding, so ->scoped() and Route::scopeBindings() do NOT apply to block — do not add them expecting page scoping, and change the binder if that scoping needs to change.
