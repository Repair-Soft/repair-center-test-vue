<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'customer')]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public $id;

    #[ORM\Column(length: 255)]
    public $name;

    #[ORM\Column(length: 255, nullable: true)]
    public $email;

    #[ORM\Column(length: 50, nullable: true)]
    public $phone;
}
