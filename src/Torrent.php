<?php

namespace Akatsuki\Component\Torrent;

use DateTime;
use Akatsuki\Component\Bencode\BencodeSerializable;

/**
 * Class Torrent
 *
 * @package Akatsuki\Component\Torrent
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
class Torrent implements BencodeSerializable
{
    /**
     * The exponent to use when making the pieces
     *
     * @var int
     */
    private $pieceLengthExp;

    /**
     * The announce URL
     *
     * @var string
     */
    private $announce;

    /**
     * The list of announce URLs
     *
     * @var array
     */
    private $announceList;

    /**
     * Optional comment
     *
     * @var string
     */
    private $comment;

    /**
     * Optional string that informs clients who or what created this torrent
     *
     * @var string
     */
    private $createdBy;

    /**
     * The unix timestamp of when the torrent was created
     *
     * @var DateTime
     */
    private $createdAt;

    /**
     * Info about the file(s) in the torrent
     *
     * @var Info
     */
    private $info;

    /**
     * Any non-standard fields in the torrent meta data
     *
     * @var array
     */
    private $extraMeta;

    public function __construct()
    {
        $this->pieceLengthExp = 18;
        $this->createdBy = 'Akatsuki Torrent Lib v1.0';
        $this->announceList = [];
        $this->extraMeta = [];
        $this->info = new Info();
    }

    /**
     * @return int
     */
    public function getPieceLengthExp(): int
    {
        return $this->pieceLengthExp;
    }

    /**
     * @param int $pieceLengthExp
     *
     * @return Torrent
     */
    public function setPieceLengthExp(int $pieceLengthExp): Torrent
    {
        $this->pieceLengthExp = $pieceLengthExp;

        return $this;
    }

    /**
     * @return string
     */
    public function getAnnounce(): ?string
    {
        return $this->announce;
    }

    /**
     * @param string $announce
     *
     * @return Torrent
     */
    public function setAnnounce(string $announce): Torrent
    {
        $this->announce = $announce;

        return $this;
    }

    /**
     * @return array
     */
    public function getAnnounceList(): array
    {
        return $this->announceList;
    }

    /**
     * @param array $announceList
     *
     * @return Torrent
     */
    public function setAnnounceList(array $announceList): Torrent
    {
        $this->announceList = $announceList;

        return $this;
    }

    public function addAnnounce(string $url): Torrent
    {
        $this->announceList[] = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     *
     * @return Torrent
     */
    public function setComment(string $comment): Torrent
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedBy(): string
    {
        return $this->createdBy;
    }

    /**
     * @param string $createdBy
     *
     * @return Torrent
     */
    public function setCreatedBy(string $createdBy): Torrent
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     *
     * @return Torrent
     */
    public function setCreatedAt(DateTime $createdAt): Torrent
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Info
     */
    public function getInfo(): Info
    {
        return $this->info;
    }

    /**
     * @param Info $info
     *
     * @return Torrent
     */
    public function setInfo(Info $info): Torrent
    {
        $this->info = $info;

        return $this;
    }

    /**
     * @return array
     */
    public function getExtraMeta(): array
    {
        return $this->extraMeta;
    }

    /**
     * @param array $extraMeta
     *
     * @return Torrent
     */
    public function setExtraMeta(array $extraMeta): Torrent
    {
        $this->extraMeta = $extraMeta;

        return $this;
    }

    /**
     * @return array
     */
    public function bencodeSerialize(): array
    {
        $main = [
            'announce' => $this->getAnnounce(),
            'announce-list' => $this->getAnnounceList(),
            'comment' => $this->getComment(),
            'created by' => $this->getCreatedBy(),
            'creation date' => $this->getCreatedAt()->getTimestamp(),
            'info' => $this->getInfo()->bencodeSerialize()
        ];

        $main = array_filter($main);

        return array_merge($main, $this->getExtraMeta());
    }
}