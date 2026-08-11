---
paths:
  - 'app/Models/**'
---

# Models

## Mass assignment via #[Fillable] attribute
Use the Eloquent #[Fillable([...])] class attribute for mass-assignment allow-lists, not a protected $fillable property. No $guarded anywhere.

## Accessors via the Attribute class, appended with #[Appends]
Computed attributes are `protected function name(): Attribute` returning `Attribute::get(...)`. Serialize them by default with the #[Appends([...])] class attribute rather than the legacy getXxxAttribute() magic-method style.

## No model event hooks; writes belong to Actions
Do not create records from booted()/created() hooks — an implicit write cannot join the caller's transaction and fires in contexts that never wanted it. Side-effect writes live in the Action that owns the operation. Models may keep the helper the Action calls (e.g. Site::createDefaultHomePage()); factories and seeders that need the same setup call it explicitly (afterCreating, or inline in the seeder).
