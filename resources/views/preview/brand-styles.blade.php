@php
    use App\Support\ColorPalette;

    $primaryHex = $site?->primary_color ?? ColorPalette::DEFAULT_PRIMARY;
    $secondaryHex = $site?->secondary_color ?? ColorPalette::DEFAULT_SECONDARY;

    $primary = ColorPalette::fromHex($primaryHex);
    $secondary = ColorPalette::fromHex($secondaryHex);
@endphp
<style>
    :root {
        --primary: {{ $primary[500] }};
        --primary-light: {{ $primary[50] }};
        --primary-dark: {{ $primary[700] }};
        --primary-darker: {{ $primary[900] }};
        --primary-darker-40: {{ ColorPalette::toRgba($primary[900], 0.4) }};
        --primary-darker-30: {{ ColorPalette::toRgba($primary[900], 0.3) }};
        --primary-darker-70: {{ ColorPalette::toRgba($primary[900], 0.7) }};

        --secondary: {{ $secondary[500] }};
        --secondary-dark: {{ $secondary[700] }};
    }
</style>
