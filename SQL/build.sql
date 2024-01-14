DROP DATABASE IF EXISTS entomological_archive;

CREATE DATABASE entomological_archive;
USE entomological_archive;

SOURCE classifications.sql;
SOURCE setup_phylum.sql;
SOURCE species.sql;
SOURCE tags.sql;
SOURCE species_tags.sql
SOURCE samples.sql;
SOURCE enthusiasts.sql;
SOURCE requests.sql;
SOURCE general_species_view.sql;
SOURCE specific_species_view.sql;
SOURCE samples_view.sql;
SOURCE requests_made_view.sql;
SOURCE taxonomy_view.sql
SOURCE add_data.sql;