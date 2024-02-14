<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Channel\Model\ChannelInterface as BaseChannelInterface;
use Sylius\Component\Channel\Model\ChannelsAwareInterface;

/**
 * This class corresponds to the 'List' entity in Klaviyo. The reason for the naming is simple: PHP doesn't allow classes to be named 'List' ;)
 */
class MemberList implements MemberListInterface, ChannelsAwareInterface
{
    protected ?int $id = null;

    protected ?string $klaviyoId = null;

    protected ?string $name = null;

    /**
     * @psalm-var Collection<array-key, BaseChannelInterface>
     * @var Collection|BaseChannelInterface[]
     */
    protected Collection $channels;

    public function __construct()
    {
        $this->channels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKlaviyoId(): ?string
    {
        return $this->klaviyoId;
    }

    public function setKlaviyoId(?string $klaviyoId): void
    {
        $this->klaviyoId = $klaviyoId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getChannels(): Collection
    {
        return $this->channels;
    }

    public function addChannel(BaseChannelInterface $channel): void
    {
        if (!$this->hasChannel($channel)) {
            $this->channels->add($channel);
        }
    }

    public function removeChannel(BaseChannelInterface $channel): void
    {
        if ($this->hasChannel($channel)) {
            $this->channels->removeElement($channel);
        }
    }

    public function hasChannel(BaseChannelInterface $channel): bool
    {
        return $this->channels->contains($channel);
    }
}
