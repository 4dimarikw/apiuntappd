<?php

namespace Domain\Entity\Interfaces;

interface IEntity
{
    public function getEntityName(): string;
    
    public function setImage(string $image_url): void;

    public function getImage(): ?string;

    public function getImageColumn(): string;

    public function getLabelColumn(): string;

    public function getLabel(): ?string;


    public function getEntityId(): ?string;

    public function getEntityDTOClass(): string;

}
