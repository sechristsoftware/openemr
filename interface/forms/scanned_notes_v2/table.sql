CREATE TABLE IF NOT EXISTS form_scanned_notes_v2 (
 id                bigint(20)   NOT NULL auto_increment,
 activity          tinyint(1)   NOT NULL DEFAULT 1,  -- 0 if deleted
 notes             text         NOT NULL DEFAULT '',
 doc_id            int(11)      DEFAULT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM;
