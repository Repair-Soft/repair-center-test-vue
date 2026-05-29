<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'repair_order')]
class RepairOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public $id;

    #[ORM\Column(length: 50, unique: true)]
    public $reference;

    // Statuts possibles : PENDING, IN_PROGRESS, WAITING_PARTS, DONE, DELIVERED, CANCELLED
    #[ORM\Column(length: 50)]
    public $status = 'PENDING';

    #[ORM\ManyToOne(targetEntity: Customer::class)]
    #[ORM\JoinColumn(nullable: true)]
    public $customer;

    #[ORM\Column(type: 'float')]
    public $totalAmount = 0;

    #[ORM\Column(type: 'datetime')]
    public $createdAt;

    #[ORM\Column(length: 1000, nullable: true)]
    public $description;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }
}
