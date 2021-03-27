# neos-hashed-assets
==============================================

This package provides functionality to avoid caching of assets that were changed.

Use `CRON.NeosHashedAssets:HashedResourceUri` in the same manner as Neos.Fusion:ResourceUri.
Using it will append `?<sha1>` to the URI, sha1 being the SHA1 hash calculated from the file's content.

The URI with the appended SHA1 hash will be cached until the cache is invalidated, for example by
`flow flow:cache:flush`.

When the asset changes and yields a different SHA1 hash, the URI will change and therefore the browser will load the
changed asset and not load it from cache.
