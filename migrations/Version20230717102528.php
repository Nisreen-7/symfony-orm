<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230717102528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE promo (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, cursus VARCHAR(255) DEFAULT NULL, start_date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promo_student (promo_id INT NOT NULL, student_id INT NOT NULL, INDEX IDX_BF571701D0C07AFF (promo_id), INDEX IDX_BF571701CB944F1A (student_id), PRIMARY KEY(promo_id, student_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE promo_student ADD CONSTRAINT FK_BF571701D0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promo_student ADD CONSTRAINT FK_BF571701CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE note CHANGE student_id student_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promo_student DROP FOREIGN KEY FK_BF571701D0C07AFF');
        $this->addSql('ALTER TABLE promo_student DROP FOREIGN KEY FK_BF571701CB944F1A');
        $this->addSql('DROP TABLE promo');
        $this->addSql('DROP TABLE promo_student');
        $this->addSql('ALTER TABLE note CHANGE student_id student_id INT NOT NULL');
    }
}
