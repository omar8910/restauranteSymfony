<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240615163850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorias (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(45) NOT NULL, descripcion VARCHAR(200) NOT NULL, UNIQUE INDEX UNIQ_5E9F836C3A909126 (nombre), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pedidos (id INT AUTO_INCREMENT NOT NULL, restaurante_id INT NOT NULL, fecha DATE NOT NULL, enviado INT NOT NULL, INDEX IDX_6716CCAA38B81E49 (restaurante_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pedidos_productos (pedidos_id INT NOT NULL, productos_id INT NOT NULL, unidades INT NOT NULL, INDEX IDX_2FA41178213530F2 (pedidos_id), INDEX IDX_2FA41178ED07566B (productos_id), PRIMARY KEY(pedidos_id, productos_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE productos (id INT AUTO_INCREMENT NOT NULL, categoria_id INT NOT NULL, nombre VARCHAR(45) NOT NULL, descripcion VARCHAR(90) NOT NULL, peso DOUBLE PRECISION NOT NULL, stock INT NOT NULL, INDEX IDX_767490E63397707A (categoria_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurantes (id INT AUTO_INCREMENT NOT NULL, correo VARCHAR(90) NOT NULL, clave VARCHAR(45) NOT NULL, pais VARCHAR(45) NOT NULL, cp INT NOT NULL, ciudad VARCHAR(45) NOT NULL, direccion VARCHAR(200) NOT NULL, UNIQUE INDEX UNIQ_3B381D7A77040BC9 (correo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pedidos ADD CONSTRAINT FK_6716CCAA38B81E49 FOREIGN KEY (restaurante_id) REFERENCES restaurantes (id)');
        $this->addSql('ALTER TABLE pedidos_productos ADD CONSTRAINT FK_2FA41178213530F2 FOREIGN KEY (pedidos_id) REFERENCES pedidos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pedidos_productos ADD CONSTRAINT FK_2FA41178ED07566B FOREIGN KEY (productos_id) REFERENCES productos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE productos ADD CONSTRAINT FK_767490E63397707A FOREIGN KEY (categoria_id) REFERENCES categorias (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pedidos DROP FOREIGN KEY FK_6716CCAA38B81E49');
        $this->addSql('ALTER TABLE pedidos_productos DROP FOREIGN KEY FK_2FA41178213530F2');
        $this->addSql('ALTER TABLE pedidos_productos DROP FOREIGN KEY FK_2FA41178ED07566B');
        $this->addSql('ALTER TABLE productos DROP FOREIGN KEY FK_767490E63397707A');
        $this->addSql('DROP TABLE categorias');
        $this->addSql('DROP TABLE pedidos');
        $this->addSql('DROP TABLE pedidos_productos');
        $this->addSql('DROP TABLE productos');
        $this->addSql('DROP TABLE restaurantes');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
