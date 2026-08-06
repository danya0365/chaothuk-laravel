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

        // Paths already root-relative (leading "/") or that start with a known
        // public disk prefix (e.g. "storage/..." from MockImageService) are
        // served as-is from the public web root — do NOT pass through
        // Storage::url(), which would resolve them relative to the disk root
        // and double-prefix (storage/storage/...).
        if (str_starts_with($path, '/') || str_starts_with($path, 'storage/')) {
            return url('/' . ltrim($path, '/'));
        }

        // Anything else (user uploads, etc.) is disk-relative to the public
        // disk root (storage/app/public).
        return Storage::url($path);
    }
}
