-- Make the password hashed
ALTER TABLE users
    MODIFY password VARCHAR(255) NOT NULL;
