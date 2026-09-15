-- Run this once on the existing database before creating new accounts.
ALTER TABLE users
    MODIFY password VARCHAR(255) NOT NULL;
