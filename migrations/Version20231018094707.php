<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231018094707 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE album (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, date_creation DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE album_groupe (album_id INT NOT NULL, groupe_id INT NOT NULL, INDEX IDX_D6C2B1961137ABCF (album_id), INDEX IDX_D6C2B1967A45358C (groupe_id), PRIMARY KEY(album_id, groupe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE album_genre (album_id INT NOT NULL, genre_id INT NOT NULL, INDEX IDX_F5E879DE1137ABCF (album_id), INDEX IDX_F5E879DE4296D31F (genre_id), PRIMARY KEY(album_id, genre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE artiste (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, info LONGTEXT DEFAULT NULL, photo VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, date_mort DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE artiste_groupe (artiste_id INT NOT NULL, groupe_id INT NOT NULL, INDEX IDX_5EC87A2021D25844 (artiste_id), INDEX IDX_5EC87A207A45358C (groupe_id), PRIMARY KEY(artiste_id, groupe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, auteur_id INT NOT NULL, post_id INT NOT NULL, texte LONGTEXT NOT NULL, INDEX IDX_67F068BC60BB6FE6 (auteur_id), INDEX IDX_67F068BC4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE discussion (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, date_creation DATE NOT NULL, date_separation DATE DEFAULT NULL, info LONGTEXT DEFAULT NULL, photo VARCHAR(255) NOT NULL, couverture VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_genre (groupe_id INT NOT NULL, genre_id INT NOT NULL, INDEX IDX_D6FDFDA87A45358C (groupe_id), INDEX IDX_D6FDFDA84296D31F (genre_id), PRIMARY KEY(groupe_id, genre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_musique (groupe_id INT NOT NULL, musique_id INT NOT NULL, INDEX IDX_287AB1F27A45358C (groupe_id), INDEX IDX_287AB1F225E254A1 (musique_id), PRIMARY KEY(groupe_id, musique_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE historique (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, musique_id INT NOT NULL, date_ecoute DATETIME NOT NULL, INDEX IDX_EDBFD5ECFB88E14F (utilisateur_id), INDEX IDX_EDBFD5EC25E254A1 (musique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, discussion_id INT NOT NULL, auteur_id INT NOT NULL, texte LONGTEXT NOT NULL, date_creation DATETIME NOT NULL, date_modif DATETIME DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_B6BD307F1ADED311 (discussion_id), INDEX IDX_B6BD307F60BB6FE6 (auteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE musique (id INT AUTO_INCREMENT NOT NULL, album_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, date_creation DATE NOT NULL, image VARCHAR(255) DEFAULT NULL, duree INT NOT NULL, nb_ecoutes INT NOT NULL, INDEX IDX_EE1D56BC1137ABCF (album_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE musique_genre (musique_id INT NOT NULL, genre_id INT NOT NULL, INDEX IDX_D23C44E925E254A1 (musique_id), INDEX IDX_D23C44E94296D31F (genre_id), PRIMARY KEY(musique_id, genre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, route VARCHAR(255) NOT NULL, id_redirect INT NOT NULL, id_optionnel INT DEFAULT NULL, date_notif DATETIME NOT NULL, INDEX IDX_BF5476CAFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE playlist (id INT AUTO_INCREMENT NOT NULL, auteur_id INT NOT NULL, nom VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, public TINYINT(1) NOT NULL, INDEX IDX_D782112D60BB6FE6 (auteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE playlist_musique (playlist_id INT NOT NULL, musique_id INT NOT NULL, INDEX IDX_512241A66BBD148 (playlist_id), INDEX IDX_512241A625E254A1 (musique_id), PRIMARY KEY(playlist_id, musique_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, auteur_id INT NOT NULL, groupe_id INT NOT NULL, texte LONGTEXT NOT NULL, image VARCHAR(255) DEFAULT NULL, date_creation DATETIME NOT NULL, INDEX IDX_5A8A6C8D60BB6FE6 (auteur_id), INDEX IDX_5A8A6C8D7A45358C (groupe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, pseudo VARCHAR(25) NOT NULL, premium_until DATE DEFAULT NULL, ban_until DATE DEFAULT NULL, date_creation DATETIME NOT NULL, verified TINYINT(1) NOT NULL, image VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_discussion (user_id INT NOT NULL, discussion_id INT NOT NULL, INDEX IDX_67DE3FE3A76ED395 (user_id), INDEX IDX_67DE3FE31ADED311 (discussion_id), PRIMARY KEY(user_id, discussion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_user (user_source INT NOT NULL, user_target INT NOT NULL, INDEX IDX_F7129A803AD8644E (user_source), INDEX IDX_F7129A80233D34C1 (user_target), PRIMARY KEY(user_source, user_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_commentaire_liker (user_id INT NOT NULL, commentaire_id INT NOT NULL, INDEX IDX_435E0DDCA76ED395 (user_id), INDEX IDX_435E0DDCBA9CD190 (commentaire_id), PRIMARY KEY(user_id, commentaire_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_commentaire (user_id INT NOT NULL, commentaire_id INT NOT NULL, INDEX IDX_CEEBA129A76ED395 (user_id), INDEX IDX_CEEBA129BA9CD190 (commentaire_id), PRIMARY KEY(user_id, commentaire_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_post_liker (user_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_EB134E8A76ED395 (user_id), INDEX IDX_EB134E84B89032C (post_id), PRIMARY KEY(user_id, post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_post (user_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_200B2044A76ED395 (user_id), INDEX IDX_200B20444B89032C (post_id), PRIMARY KEY(user_id, post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_artiste (user_id INT NOT NULL, artiste_id INT NOT NULL, INDEX IDX_C40A2B45A76ED395 (user_id), INDEX IDX_C40A2B4521D25844 (artiste_id), PRIMARY KEY(user_id, artiste_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_groupe_liker (user_id INT NOT NULL, groupe_id INT NOT NULL, INDEX IDX_EBACCCB9A76ED395 (user_id), INDEX IDX_EBACCCB97A45358C (groupe_id), PRIMARY KEY(user_id, groupe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_groupe (user_id INT NOT NULL, groupe_id INT NOT NULL, INDEX IDX_61EB971CA76ED395 (user_id), INDEX IDX_61EB971C7A45358C (groupe_id), PRIMARY KEY(user_id, groupe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_album (user_id INT NOT NULL, album_id INT NOT NULL, INDEX IDX_DB5A951BA76ED395 (user_id), INDEX IDX_DB5A951B1137ABCF (album_id), PRIMARY KEY(user_id, album_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_musique (user_id INT NOT NULL, musique_id INT NOT NULL, INDEX IDX_B61048B6A76ED395 (user_id), INDEX IDX_B61048B625E254A1 (musique_id), PRIMARY KEY(user_id, musique_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE album_groupe ADD CONSTRAINT FK_D6C2B1961137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE album_groupe ADD CONSTRAINT FK_D6C2B1967A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE album_genre ADD CONSTRAINT FK_F5E879DE1137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE album_genre ADD CONSTRAINT FK_F5E879DE4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE artiste_groupe ADD CONSTRAINT FK_5EC87A2021D25844 FOREIGN KEY (artiste_id) REFERENCES artiste (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE artiste_groupe ADD CONSTRAINT FK_5EC87A207A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE groupe_genre ADD CONSTRAINT FK_D6FDFDA87A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_genre ADD CONSTRAINT FK_D6FDFDA84296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_musique ADD CONSTRAINT FK_287AB1F27A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_musique ADD CONSTRAINT FK_287AB1F225E254A1 FOREIGN KEY (musique_id) REFERENCES musique (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE historique ADD CONSTRAINT FK_EDBFD5ECFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE historique ADD CONSTRAINT FK_EDBFD5EC25E254A1 FOREIGN KEY (musique_id) REFERENCES musique (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F1ADED311 FOREIGN KEY (discussion_id) REFERENCES discussion (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE musique ADD CONSTRAINT FK_EE1D56BC1137ABCF FOREIGN KEY (album_id) REFERENCES album (id)');
        $this->addSql('ALTER TABLE musique_genre ADD CONSTRAINT FK_D23C44E925E254A1 FOREIGN KEY (musique_id) REFERENCES musique (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE musique_genre ADD CONSTRAINT FK_D23C44E94296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE playlist ADD CONSTRAINT FK_D782112D60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE playlist_musique ADD CONSTRAINT FK_512241A66BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE playlist_musique ADD CONSTRAINT FK_512241A625E254A1 FOREIGN KEY (musique_id) REFERENCES musique (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE user_discussion ADD CONSTRAINT FK_67DE3FE3A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_discussion ADD CONSTRAINT FK_67DE3FE31ADED311 FOREIGN KEY (discussion_id) REFERENCES discussion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A803AD8644E FOREIGN KEY (user_source) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A80233D34C1 FOREIGN KEY (user_target) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_commentaire_liker ADD CONSTRAINT FK_435E0DDCA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_commentaire_liker ADD CONSTRAINT FK_435E0DDCBA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_commentaire ADD CONSTRAINT FK_CEEBA129A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_commentaire ADD CONSTRAINT FK_CEEBA129BA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_post_liker ADD CONSTRAINT FK_EB134E8A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_post_liker ADD CONSTRAINT FK_EB134E84B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_post ADD CONSTRAINT FK_200B2044A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_post ADD CONSTRAINT FK_200B20444B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_artiste ADD CONSTRAINT FK_C40A2B45A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_artiste ADD CONSTRAINT FK_C40A2B4521D25844 FOREIGN KEY (artiste_id) REFERENCES artiste (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_groupe_liker ADD CONSTRAINT FK_EBACCCB9A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_groupe_liker ADD CONSTRAINT FK_EBACCCB97A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_groupe ADD CONSTRAINT FK_61EB971CA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_groupe ADD CONSTRAINT FK_61EB971C7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_album ADD CONSTRAINT FK_DB5A951BA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_album ADD CONSTRAINT FK_DB5A951B1137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_musique ADD CONSTRAINT FK_B61048B6A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_musique ADD CONSTRAINT FK_B61048B625E254A1 FOREIGN KEY (musique_id) REFERENCES musique (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album_groupe DROP FOREIGN KEY FK_D6C2B1961137ABCF');
        $this->addSql('ALTER TABLE album_groupe DROP FOREIGN KEY FK_D6C2B1967A45358C');
        $this->addSql('ALTER TABLE album_genre DROP FOREIGN KEY FK_F5E879DE1137ABCF');
        $this->addSql('ALTER TABLE album_genre DROP FOREIGN KEY FK_F5E879DE4296D31F');
        $this->addSql('ALTER TABLE artiste_groupe DROP FOREIGN KEY FK_5EC87A2021D25844');
        $this->addSql('ALTER TABLE artiste_groupe DROP FOREIGN KEY FK_5EC87A207A45358C');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC60BB6FE6');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC4B89032C');
        $this->addSql('ALTER TABLE groupe_genre DROP FOREIGN KEY FK_D6FDFDA87A45358C');
        $this->addSql('ALTER TABLE groupe_genre DROP FOREIGN KEY FK_D6FDFDA84296D31F');
        $this->addSql('ALTER TABLE groupe_musique DROP FOREIGN KEY FK_287AB1F27A45358C');
        $this->addSql('ALTER TABLE groupe_musique DROP FOREIGN KEY FK_287AB1F225E254A1');
        $this->addSql('ALTER TABLE historique DROP FOREIGN KEY FK_EDBFD5ECFB88E14F');
        $this->addSql('ALTER TABLE historique DROP FOREIGN KEY FK_EDBFD5EC25E254A1');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F1ADED311');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F60BB6FE6');
        $this->addSql('ALTER TABLE musique DROP FOREIGN KEY FK_EE1D56BC1137ABCF');
        $this->addSql('ALTER TABLE musique_genre DROP FOREIGN KEY FK_D23C44E925E254A1');
        $this->addSql('ALTER TABLE musique_genre DROP FOREIGN KEY FK_D23C44E94296D31F');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAFB88E14F');
        $this->addSql('ALTER TABLE playlist DROP FOREIGN KEY FK_D782112D60BB6FE6');
        $this->addSql('ALTER TABLE playlist_musique DROP FOREIGN KEY FK_512241A66BBD148');
        $this->addSql('ALTER TABLE playlist_musique DROP FOREIGN KEY FK_512241A625E254A1');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D60BB6FE6');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D7A45358C');
        $this->addSql('ALTER TABLE user_discussion DROP FOREIGN KEY FK_67DE3FE3A76ED395');
        $this->addSql('ALTER TABLE user_discussion DROP FOREIGN KEY FK_67DE3FE31ADED311');
        $this->addSql('ALTER TABLE user_user DROP FOREIGN KEY FK_F7129A803AD8644E');
        $this->addSql('ALTER TABLE user_user DROP FOREIGN KEY FK_F7129A80233D34C1');
        $this->addSql('ALTER TABLE user_commentaire_liker DROP FOREIGN KEY FK_435E0DDCA76ED395');
        $this->addSql('ALTER TABLE user_commentaire_liker DROP FOREIGN KEY FK_435E0DDCBA9CD190');
        $this->addSql('ALTER TABLE user_commentaire DROP FOREIGN KEY FK_CEEBA129A76ED395');
        $this->addSql('ALTER TABLE user_commentaire DROP FOREIGN KEY FK_CEEBA129BA9CD190');
        $this->addSql('ALTER TABLE user_post_liker DROP FOREIGN KEY FK_EB134E8A76ED395');
        $this->addSql('ALTER TABLE user_post_liker DROP FOREIGN KEY FK_EB134E84B89032C');
        $this->addSql('ALTER TABLE user_post DROP FOREIGN KEY FK_200B2044A76ED395');
        $this->addSql('ALTER TABLE user_post DROP FOREIGN KEY FK_200B20444B89032C');
        $this->addSql('ALTER TABLE user_artiste DROP FOREIGN KEY FK_C40A2B45A76ED395');
        $this->addSql('ALTER TABLE user_artiste DROP FOREIGN KEY FK_C40A2B4521D25844');
        $this->addSql('ALTER TABLE user_groupe_liker DROP FOREIGN KEY FK_EBACCCB9A76ED395');
        $this->addSql('ALTER TABLE user_groupe_liker DROP FOREIGN KEY FK_EBACCCB97A45358C');
        $this->addSql('ALTER TABLE user_groupe DROP FOREIGN KEY FK_61EB971CA76ED395');
        $this->addSql('ALTER TABLE user_groupe DROP FOREIGN KEY FK_61EB971C7A45358C');
        $this->addSql('ALTER TABLE user_album DROP FOREIGN KEY FK_DB5A951BA76ED395');
        $this->addSql('ALTER TABLE user_album DROP FOREIGN KEY FK_DB5A951B1137ABCF');
        $this->addSql('ALTER TABLE user_musique DROP FOREIGN KEY FK_B61048B6A76ED395');
        $this->addSql('ALTER TABLE user_musique DROP FOREIGN KEY FK_B61048B625E254A1');
        $this->addSql('DROP TABLE album');
        $this->addSql('DROP TABLE album_groupe');
        $this->addSql('DROP TABLE album_genre');
        $this->addSql('DROP TABLE artiste');
        $this->addSql('DROP TABLE artiste_groupe');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE discussion');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE groupe_genre');
        $this->addSql('DROP TABLE groupe_musique');
        $this->addSql('DROP TABLE historique');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE musique');
        $this->addSql('DROP TABLE musique_genre');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE playlist');
        $this->addSql('DROP TABLE playlist_musique');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_discussion');
        $this->addSql('DROP TABLE user_user');
        $this->addSql('DROP TABLE user_commentaire_liker');
        $this->addSql('DROP TABLE user_commentaire');
        $this->addSql('DROP TABLE user_post_liker');
        $this->addSql('DROP TABLE user_post');
        $this->addSql('DROP TABLE user_artiste');
        $this->addSql('DROP TABLE user_groupe_liker');
        $this->addSql('DROP TABLE user_groupe');
        $this->addSql('DROP TABLE user_album');
        $this->addSql('DROP TABLE user_musique');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
