<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200319081808 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F8A1E38E');
        $this->addSql('DROP INDEX IDX_C53D045F8A1E38E ON image');
        $this->addSql('ALTER TABLE image ADD event_gallery_id INT DEFAULT NULL, CHANGE portofolio_page_id nature_gallery_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FE8991C18 FOREIGN KEY (nature_gallery_id) REFERENCES portofolio_page (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F5C0976A0 FOREIGN KEY (event_gallery_id) REFERENCES portofolio_page (id)');
        $this->addSql('CREATE INDEX IDX_C53D045FE8991C18 ON image (nature_gallery_id)');
        $this->addSql('CREATE INDEX IDX_C53D045F5C0976A0 ON image (event_gallery_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FE8991C18');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F5C0976A0');
        $this->addSql('DROP INDEX IDX_C53D045FE8991C18 ON image');
        $this->addSql('DROP INDEX IDX_C53D045F5C0976A0 ON image');
        $this->addSql('ALTER TABLE image ADD portofolio_page_id INT DEFAULT NULL, DROP nature_gallery_id, DROP event_gallery_id');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F8A1E38E FOREIGN KEY (portofolio_page_id) REFERENCES portofolio_page (id)');
        $this->addSql('CREATE INDEX IDX_C53D045F8A1E38E ON image (portofolio_page_id)');
    }
}
