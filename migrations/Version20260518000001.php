<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260518000001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial schema: customer, repair_order, part';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE customer (
            id SERIAL NOT NULL,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) DEFAULT NULL,
            phone VARCHAR(50) DEFAULT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE TABLE repair_order (
            id SERIAL NOT NULL,
            customer_id INT DEFAULT NULL,
            reference VARCHAR(50) NOT NULL,
            status VARCHAR(50) NOT NULL,
            total_amount DOUBLE PRECISION NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            description VARCHAR(1000) DEFAULT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_REPAIR_ORDER_REFERENCE ON repair_order (reference)');
        $this->addSql('ALTER TABLE repair_order ADD CONSTRAINT FK_REPAIR_ORDER_CUSTOMER FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('CREATE TABLE part (
            id SERIAL NOT NULL,
            reference VARCHAR(50) NOT NULL,
            label VARCHAR(255) NOT NULL,
            sale_price DOUBLE PRECISION NOT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_PART_REFERENCE ON part (reference)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE repair_order DROP CONSTRAINT FK_REPAIR_ORDER_CUSTOMER');
        $this->addSql('DROP TABLE part');
        $this->addSql('DROP TABLE repair_order');
        $this->addSql('DROP TABLE customer');
    }
}
