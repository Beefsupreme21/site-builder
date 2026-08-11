---
paths:
  - 'app/Http/Controllers/**'
---

# Controllers

## Thin controllers delegate writes to Actions
Keep controllers thin: eager-load, render (inertia or view), and route every write through an Action as (new X)->handle(...). Query Eloquent directly in controllers/actions — no repository or query layer. Read-only aggregation for a page's props belongs in the controller, not an Action.

## Render web pages via the global inertia() helper
Return web views with the global inertia('page', props) helper, not Inertia::render().

## Redirect to named routes with to_route(), never back()
Redirect with to_route('name', $params). Do not use back() or redirect()->back(): the referer is unavailable to API and mobile clients, so every write must resolve its own destination from the models it touched.
