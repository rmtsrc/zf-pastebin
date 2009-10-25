-- scripts/schema.sqlite.sql
--
-- You will need load your database schema with this SQL.

CREATE TABLE pastebin (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    short_id VARCHAR(6) NOT NULL,
    name VARCHAR(32) NOT NULL DEFAULT 'Anonymous',
    code TEXT NULL,
    language VARCHAR(32) NOT NULL DEFAULT 'php',
    expires DATETIME NULL DEFAULT NULL,
    created DATETIME NOT NULL
);

CREATE INDEX "id" ON "pastebin" ("id");
