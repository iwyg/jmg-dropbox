<?php

/*
 * This File is part of the Thapp\Jmg package
 *
 * (c) iwyg <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Thapp\Jmg\Loader\Dropbox;

use Dropbox\Client;
use Dropbox\Exception as DboxException;
use Thapp\Jmg\Loader\AbstractLoader;
use Thapp\Jmg\Exception\SourceLoaderException;

/**
 * @class DropboxLoader
 *
 * @package Thapp\Jmg
 * @version $Id$
 * @author iwyg <mail@thomas-appel.com>
 */
class Loader extends AbstractLoader
{
    /** @var Client */
    private $client;

    /** @var string */
    private $prefix;

    /**
     * Constructor.
     *
     * @param Client $client
     * @param string $prefix
     */
    public function __construct(Client $client, $prefix = null)
    {
        $this->client = $client;
        $this->prefix = $prefix;
    }

    /**
     * {@inheritdoc}
     */
    public function load($file)
    {
        if (!$handle = $this->readStream($file)) {
            throw new SourceLoaderException(sprintf('Could not load source "%s".', $file));
        }

        if (!$resource = $this->validate($handle)) {
            throw new SourceLoaderException(sprintf('source "%s" is not an image', $file));
        }

        return $resource;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($path)
    {
        return null !== $this->client->getMetaData($this->getPrefixed($path));
    }

    /**
     * {@inheritdoc}
     */
    private function getPrefixed($path)
    {
        if (0 === mb_strpos($path, '.')) {
            $path = mb_substr($path, 1);
        }

        $path = ltrim($path, '/');

        return '/' . ltrim(0 !== mb_strlen($path) ? ($this->prefix ?: '') . $path : ($this->prefix ?: ''), '/');
    }

    /**
     * {@inheritdoc}
     */
    private function readStream($path)
    {
        $stream = tmpfile();

        if (null === $this->client->getFile($this->getPrefixed($path), $stream)) {
            fclose($stream);

            return false;
        }

        rewind($stream);

        return $stream;
    }
}
