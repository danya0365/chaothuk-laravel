<?php

if (! function_exists('image_url')) {
    /**
     * Normalize a stored image path (possibly relative like "storage/...") into
     * an absolute URL. Handles absolute URLs (http...), /storage/... (leading
     * slash), and bare relative paths. Falls back to $fallback when empty.
     */
    function image_url(?string $path, ?string $fallback = null): ?string
    {
        if (blank($path)) {
            return $fallback;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, '/')) {
            return url($path);
        }

        return Storage::url($path);
    }
}
