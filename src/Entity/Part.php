<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'part')]
class Part
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public $id;

    #[ORM\Column(length: 50, unique: true)]
    public $reference;

    #[ORM\Column(length: 255)]
    public $label;

    #[ORM\Column(type: 'float')]
    public $salePrice;
}
