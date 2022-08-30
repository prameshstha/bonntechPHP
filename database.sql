CREATE DATABASE IF NOT EXISTS bonntechPHP;
USE bonntechPHP;
-- create
CREATE TABLE contacts (
  id INTEGER PRIMARY KEY,
  first_name TEXT NOT NULL,
    last_name TEXT NOT NULL,
    mobile TEXT NOT NULL,
    email TEXT NOT NULL,
    postcode TEXT NOT NULL
);


