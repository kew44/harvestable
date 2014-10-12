-- Database: harvest

-- DROP DATABASE harvest;

CREATE DATABASE harvest
  WITH OWNER = postgres
       ENCODING = 'UTF8'
       TABLESPACE = pg_default
       LC_COLLATE = 'English_United States.1252'
       LC_CTYPE = 'English_United States.1252'
       CONNECTION LIMIT = -1;

-- Table: oauth

-- DROP TABLE oauth;

CREATE TABLE oauth
(
  access_token text,
  instance_url text,
  app_instance text
)
WITH (
  OIDS=FALSE
);
ALTER TABLE oauth
  OWNER TO postgres;
