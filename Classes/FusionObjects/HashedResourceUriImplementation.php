<?php

namespace CRON\NeosHashedAssets\FusionObjects;

use Neos\Fusion\FusionObjects\ResourceUriImplementation;

/**
 * A Fusion object that behaves the same as Neos.Fusion:ResourceUri
 * but appends '?<sha1>' to the URI, <sha1> being the SHA1 hash of the
 * resource file's content.
 */
class HashedResourceUriImplementation extends ResourceUriImplementation
{
    public function evaluate()
    {
        // get the URI that Neos.Fusion:ResourceUri returns
        $resourceUri = parent::evaluate();

        // the URI can be used to create the SHA1 hash from the file content
        $sha1 = sha1_file($resourceUri);

        return $resourceUri . ($sha1 ? '?' . $sha1 : '');
    }
}
