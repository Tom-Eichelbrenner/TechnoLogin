<?php

namespace App\Model;

interface TimeStampedInterface
{
    public function getCreatedAt(): ?\DateTimeInterface;
    public function setCreatedAt(\DateTime $createdAt);
    public function getUpdatedAt(): ?\DateTimeInterface;
    public function setUpdatedAt(\DateTime $updatedAt);
}