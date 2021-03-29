<?php

namespace CRON\NeosHashedAssets\FusionObjects;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Package\Exception\UnknownPackageException;
use Neos\Flow\Package\FlowPackageInterface;
use Neos\Flow\Package\PackageManager;
use Neos\Fusion\Exception;
use Neos\Fusion\FusionObjects\ResourceUriImplementation;

/**
 * A Fusion object that behaves the same as Neos.Fusion:ResourceUri
 * but appends '?<sha1>' to the URI, <sha1> being the SHA1 hash of the
 * resource file's content.
 *
 * Only fusion property "path" is supported, expected with the syntax "resource://<Package>/Public/..."
 */
class HashedResourceUriImplementation extends ResourceUriImplementation
{

    /**
     * @Flow\Inject
     * @var PackageManager
     */
    protected $packageManager;

    public function evaluate()
    {
        $resourceUri = parent::evaluate();

        // append a sha1, if possible
        $path = $this->fusionValue('path');
        if (strpos($path, 'resource://') === 0) {
            $matches = [];
            if (preg_match('#^resource://([^/]+)(/Public/.*)#', $path, $matches) !== 1) {
                throw new Exception(sprintf('The specified path "%s" does not point to a public resource.', $path), 1617009244);
            }
            $package = $matches[1];
            $relativePath = $matches[2];
            try {
                $packagePath = $this->packageManager->getPackage($package)->getPackagePath();
            } catch (UnknownPackageException $e) {
                throw new Exception(sprintf('The specified path "%s" does not point to a valid package.', $path), 1617009246);
            }
            $filename = $packagePath . FlowPackageInterface::DIRECTORY_RESOURCES . $relativePath;
            if (!is_readable($filename)) {
                throw new Exception(sprintf('The specified path "%s" does not point to a readable filename.', $path), 1617009245);
            }

            $sha1 = sha1_file($filename);
            $resourceUri .= (strpos($resourceUri, '?') === false) ? '?' : '&';
            $resourceUri .= $sha1;
        }

        return $resourceUri;
    }

}
