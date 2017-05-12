<?php

namespace Akatsuki\Component\Torrent;

use Akatsuki\Component\Bencode\BencodeSerializable;

/**
 * Class Info
 *
 * @package Akatsuki\Component\Torrent
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
class Info implements BencodeSerializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $peaceLength;

    /**
     * @var string
     */
    private $pieces;

    /**
     * @var int
     */
    private $length;

    /**
     * @var bool
     */
    private $private;

    /**
     * @var File[]
     */
    private $files;

    /**
     * @var array
     */
    private $extraMeta;

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Info
     */
    public function setName(string $name): Info
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getPeaceLength(): ?int
    {
        return $this->peaceLength;
    }

    /**
     * @param int $peaceLength
     *
     * @return Info
     */
    public function setPeaceLength(int $peaceLength): Info
    {
        $this->peaceLength = $peaceLength;

        return $this;
    }

    /**
     * @return string
     */
    public function getPieces(): ?string
    {
        return $this->pieces;
    }

    /**
     * @param string $pieces
     *
     * @return Info
     */
    public function setPieces(string $pieces): Info
    {
        $this->pieces = $pieces;

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
     * @return Info
     */
    public function setLength(int $length): Info
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPrivate(): ?bool
    {
        return $this->private;
    }

    /**
     * @param bool $private
     *
     * @return Info
     */
    public function setPrivate(bool $private): Info
    {
        $this->private = $private;

        return $this;
    }

    /**
     * @return File[]
     */
    public function getFiles(): ?array
    {
        return $this->files;
    }

    /**
     * @param File[] $files
     *
     * @return Info
     */
    public function setFiles(array $files): Info
    {
        $this->files = (function (File ...$files) { return $files; })(...$files);

        return $this;
    }

    /**
     * @return array
     */
    public function getExtraMeta(): ?array
    {
        return $this->extraMeta;
    }

    /**
     * @param array $extraMeta
     *
     * @return Info
     */
    public function setExtraMeta(array $extraMeta): Info
    {
        $this->extraMeta = $extraMeta;

        return $this;
    }

    /**
     * @return array
     */
    public function bencodeSerialize(): array
    {
        $files = [];
        foreach ($this->getFiles() ?? [] as $file) {
            $files[] = $file->bencodeSerialize();
        }

        $main = [
            'name' => $this->getName(),
            'length' => $this->getLength(),
            'piece length' => $this->getPeaceLength(),
            'pieces' => $this->getPieces(),
            'private' => $this->isPrivate() ? 1 : null,
            'files' => count($files) > 0 ? $files : null
        ];

        $main = array_filter($main);

        return array_merge($main, $this->getExtraMeta());
    }
}