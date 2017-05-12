<?php

namespace Akatsuki\Component\Torrent;

use Akatsuki\Component\Bencode\BencodeSerializable;

/**
 * Class TorrentFile
 *
 * @package Akatsuki\Component\Torrent
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
class File implements BencodeSerializable
{
    /**
     * @var array
     */
    private $path;

    /**
     * @var int
     */
    private $length;

    /**
     * @return array
     */
    public function getPath(): ?array
    {
        return $this->path;
    }

    public function getFullPath(): ?string
    {
        if (null !== $this->path) {
            return implode(DIRECTORY_SEPARATOR, $this->path);
        }

        return null;
    }

    /**
     * @param array $path
     *
     * @return File
     */
    public function setPath(array $path): File
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return int
     */
    public function getLength(): ?int
    {
        return $this->length;
    }

    /**
     * @param int $length
     *
     * @return File
     */
    public function setLength(int $length): File
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return array
     */
    public function bencodeSerialize(): array
    {
        return [
            'path' => $this->getPath(),
            'length' => $this->getLength()
        ];
    }
}