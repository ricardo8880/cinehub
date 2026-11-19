-- CineHub — atualização incremental para português brasileiro (pt-br)
-- Base: cinehub-atual.sql (MariaDB 10.4 / dump de 18-11-2026).
-- Execute SOMENTE após importar o banco original e fazer backup.
-- Seguro para reaplicar: insere traduções PT-BR que ainda não existam.
-- Mantém intocados posts, mídias, métricas e versões EN/FR/DE.
-- Não traduz automaticamente posts nem atribui vídeos em português.

SET NAMES utf8mb4;
START TRANSACTION;

INSERT INTO `category_localizations` (`category_id`, `locale`, `slug`, `name`, `description`, `seo_title`, `seo_description`, `is_active`, `created_at`, `updated_at`)
SELECT p.id, 'pt-br', t.`slug`, t.`name`, t.`description`, t.`seo_title`, t.`seo_description`, 1, NOW(), NOW()
FROM (
    SELECT 2 AS `entity_id`, 'filmes' AS `slug`, 'Filmes' AS `name`, 'Explore filmes, críticas e análises cinematográficas no CineHub.' AS `description`, 'Filmes, críticas e análises | CineHub' AS `seo_title`, 'Descubra filmes, críticas, análises e histórias do cinema no CineHub.' AS `seo_description`
    UNION ALL SELECT 3 AS `entity_id`, 'series' AS `slug`, 'Séries' AS `name`, 'Descubra séries, análises e histórias fascinantes da televisão no CineHub.' AS `description`, 'Séries de TV e análises | CineHub' AS `seo_title`, 'Explore séries de TV, análises de episódios e histórias envolventes no CineHub.' AS `seo_description`
    UNION ALL SELECT 4 AS `entity_id`, 'jogos' AS `slug`, 'Jogos' AS `name`, 'Explore jogos, análises e aventuras do universo gamer no CineHub.' AS `description`, 'Jogos e análises | CineHub' AS `seo_title`, 'Descubra jogos, análises, aventuras e novidades do universo gamer no CineHub.' AS `seo_description`
) AS t
INNER JOIN `categories` p ON p.id = t.entity_id
LEFT JOIN `category_localizations` existing ON existing.`category_id` = p.id AND existing.`locale` = 'pt-br'
WHERE existing.id IS NULL;

INSERT INTO `genre_localizations` (`genre_id`, `locale`, `name`, `slug`, `is_active`, `created_at`, `updated_at`)
SELECT p.id, 'pt-br', t.`name`, t.`slug`, 1, NOW(), NOW()
FROM (
    SELECT 1 AS `entity_id`, 'Aventura' AS `name`, 'aventura' AS `slug`
    UNION ALL SELECT 2 AS `entity_id`, 'Ação' AS `name`, 'acao' AS `slug`
    UNION ALL SELECT 3 AS `entity_id`, 'RPG' AS `name`, 'rpg' AS `slug`
    UNION ALL SELECT 4 AS `entity_id`, 'Mundo aberto' AS `name`, 'mundo-aberto' AS `slug`
    UNION ALL SELECT 6 AS `entity_id`, 'Fantasia' AS `name`, 'fantasia' AS `slug`
    UNION ALL SELECT 8 AS `entity_id`, 'Ficção científica' AS `name`, 'ficcao-cientifica' AS `slug`
    UNION ALL SELECT 9 AS `entity_id`, 'Terror' AS `name`, 'terror' AS `slug`
    UNION ALL SELECT 10 AS `entity_id`, 'Mistério' AS `name`, 'misterio' AS `slug`
    UNION ALL SELECT 11 AS `entity_id`, 'Drama' AS `name`, 'drama' AS `slug`
    UNION ALL SELECT 12 AS `entity_id`, 'Suspense' AS `name`, 'suspense' AS `slug`
    UNION ALL SELECT 13 AS `entity_id`, 'Animação' AS `name`, 'animacao' AS `slug`
    UNION ALL SELECT 15 AS `entity_id`, 'Comédia' AS `name`, 'comedia' AS `slug`
    UNION ALL SELECT 16 AS `entity_id`, 'Crime' AS `name`, 'crime' AS `slug`
    UNION ALL SELECT 17 AS `entity_id`, 'Documentário' AS `name`, 'documentario' AS `slug`
    UNION ALL SELECT 18 AS `entity_id`, 'Família' AS `name`, 'familia' AS `slug`
    UNION ALL SELECT 20 AS `entity_id`, 'História' AS `name`, 'historia' AS `slug`
    UNION ALL SELECT 21 AS `entity_id`, 'Música' AS `name`, 'musica' AS `slug`
    UNION ALL SELECT 22 AS `entity_id`, 'Plataforma' AS `name`, 'plataforma' AS `slug`
    UNION ALL SELECT 23 AS `entity_id`, 'Quebra-cabeça' AS `name`, 'quebra-cabeca' AS `slug`
    UNION ALL SELECT 24 AS `entity_id`, 'Corrida' AS `name`, 'corrida' AS `slug`
    UNION ALL SELECT 25 AS `entity_id`, 'Romance' AS `name`, 'romance' AS `slug`
    UNION ALL SELECT 26 AS `entity_id`, 'Ritmo' AS `name`, 'ritmo' AS `slug`
    UNION ALL SELECT 28 AS `entity_id`, 'Simulação' AS `name`, 'simulacao' AS `slug`
    UNION ALL SELECT 29 AS `entity_id`, 'Esportes' AS `name`, 'esportes' AS `slug`
    UNION ALL SELECT 30 AS `entity_id`, 'Estratégia' AS `name`, 'estrategia' AS `slug`
    UNION ALL SELECT 31 AS `entity_id`, 'Sobrevivência' AS `name`, 'sobrevivencia' AS `slug`
    UNION ALL SELECT 32 AS `entity_id`, 'Guerra' AS `name`, 'guerra' AS `slug`
    UNION ALL SELECT 33 AS `entity_id`, 'Faroeste' AS `name`, 'faroeste' AS `slug`
) AS t
INNER JOIN `genres` p ON p.id = t.entity_id
LEFT JOIN `genre_localizations` existing ON existing.`genre_id` = p.id AND existing.`locale` = 'pt-br'
WHERE existing.id IS NULL;

INSERT INTO `blog_category_localizations` (`blog_category_id`, `locale`, `name`, `is_active`, `created_at`, `updated_at`)
SELECT p.id, 'pt-br', t.`name`, 1, NOW(), NOW()
FROM (
    SELECT 1 AS `entity_id`, 'Minhas Escolhas' AS `name`
) AS t
INNER JOIN `blog_categories` p ON p.id = t.entity_id
LEFT JOIN `blog_category_localizations` existing ON existing.`blog_category_id` = p.id AND existing.`locale` = 'pt-br'
WHERE existing.id IS NULL;

INSERT INTO `blog_genre_localizations` (`blog_genre_id`, `locale`, `name`, `slug`, `is_active`, `created_at`, `updated_at`)
SELECT p.id, 'pt-br', t.`name`, t.`slug`, 1, NOW(), NOW()
FROM (
    SELECT 1 AS `entity_id`, 'Minhas Escolhas' AS `name`, 'minhas-escolhas' AS `slug`
) AS t
INNER JOIN `blog_genres` p ON p.id = t.entity_id
LEFT JOIN `blog_genre_localizations` existing ON existing.`blog_genre_id` = p.id AND existing.`locale` = 'pt-br'
WHERE existing.id IS NULL;

COMMIT;

-- Resultado esperado deste dump após uma primeira execução:
-- 3 categorias, 28 gêneros, 1 categoria do blog e 1 gênero do blog em pt-br.
-- Novos conteúdos precisam ser publicados pelo admin, na aba Português (Brasil).
-- As métricas começarão do zero para pt-br e serão gravadas automaticamente.
