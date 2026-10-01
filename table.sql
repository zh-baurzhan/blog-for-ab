SET NAMES utf8mb4;
CREATE TABLE categories (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    slug        VARCHAR(120) NOT NULL UNIQUE,
    description TEXT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE articles (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    image        VARCHAR(255) NULL,
    title        VARCHAR(255) NOT NULL,
    slug         VARCHAR(255) NOT NULL UNIQUE,
    description  VARCHAR(500) NULL,
    body         TEXT NOT NULL,
    views        INT UNSIGNED NOT NULL DEFAULT 0,
    published_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_published (published_at)
);
CREATE TABLE article_category (
    article_id  INT UNSIGNED NOT NULL,
    category_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (article_id, category_id),
    FOREIGN KEY (article_id)  REFERENCES articles(id)   ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);
