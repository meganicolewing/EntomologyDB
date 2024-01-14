CREATE TABLE classifications(
    classifications_taxon VARCHAR(64),
    classifications_supertaxon VARCHAR(64),
    PRIMARY KEY (classifications_taxon)
);
ALTER TABLE classifications ADD CONSTRAINT fk_super
      FOREIGN KEY (classifications_supertaxon) REFERENCES classifications(classifications_taxon) ON DELETE NO ACTION;