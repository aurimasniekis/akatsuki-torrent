<?php

namespace Akatsuki\Component\Torrent;

use Akatsuki\Component\Torrent\Exception\FileNotReadableException;
use DateTime;
use Akatsuki\Component\Bencode\Decoder;
use Akatsuki\Component\Bencode\Encoder;

/**
 * Class Manager
 *
 * @package Akatsuki\Component\Torrent
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
class Manager
{
    /**
     * @var Decoder
     */
    private $decoder;

    /**
     * @var Encoder
     */
    private $encoder;

    public function __construct(Decoder $decoder, Encoder $encoder)
    {
        $this->decoder = $decoder;
        $this->encoder = $encoder;
    }

    public function loadFromFile(string $file): Torrent
    {
        if (false === file_exists($file) || false === is_readable($file)) {
            throw new FileNotReadableException($file);
        }

        $content = file_get_contents($file);

        return $this->loadFromString($content);
    }

    public function loadFromString(string $source): Torrent
    {
        $dictionary = $this->decoder->decode($source);

        $torrent = new Torrent();

        if (isset($dictionary['announce'])) {
            $torrent->setAnnounce($dictionary['announce']);
            unset($dictionary['announce']);
        }

        if (isset($dictionary['announce-list'])) {
            $torrent->setAnnounceList($dictionary['announce-list']);
            unset($dictionary['announce-list']);
        }
        if (isset($dictionary['comment'])) {
            $torrent->setComment($dictionary['comment']);
            unset($dictionary['comment']);
        }
        if (isset($dictionary['created by'])) {
            $torrent->setCreatedBy($dictionary['created by']);
            unset($dictionary['created by']);
        }
        if (isset($dictionary['creation date'])) {
            $createdAt = new DateTime();
            $createdAt->setTimestamp($dictionary['creation date']);

            $torrent->setCreatedAt($createdAt);
            unset($dictionary['creation date']);
        }
        if (isset($dictionary['info'])) {
            $info = $dictionary['info'];

            if (isset($info['name'])) {
                $torrent->getInfo()->setName($info['name']);
                unset($info['name']);
            }

            if (isset($info['length'])) {
                $torrent->getInfo()->setLength($info['length']);
                unset($info['length']);
            }

            if (isset($info['piece length'])) {
                $torrent->getInfo()->setPeaceLength($info['piece length']);
                unset($info['piece length']);
            }

            if (isset($info['pieces'])) {
                $torrent->getInfo()->setPieces($info['pieces']);
                unset($info['pieces']);
            }

            if (isset($info['private'])) {
                if (1 === $info['private']) {
                    $torrent->getInfo()->setPrivate(true);
                } else {
                    $torrent->getInfo()->setPrivate(false);
                }

                unset($info['private']);
            }

            if (isset($info['files']) && is_array($info['files'])) {
                $files = [];
                foreach ($info['files'] as $file) {
                    $torrentFile = new File();

                    if (isset($file['path'])) {
                        $torrentFile->setPath($file['path']);
                    }

                    if (isset($file['length'])) {
                        $torrentFile->setLength($file['length']);
                    }

                    $files[] = $torrentFile;
                }

                $torrent->getInfo()->setFiles($files);
                unset($info['files']);
            }

            if (count($info) > 0) {
                $torrent->getInfo()->setExtraMeta($info);
            }

            unset($dictionary['info']);
        }

        if (count($dictionary) > 0) {
            $torrent->setExtraMeta($dictionary);
        }

        return $torrent;
    }

    public function generate(Torrent $torrent): string
    {
        return $this->encoder->encode($torrent);
    }
}