ALTER TABLE fields CHANGE field_type field_type enum('TEXT','TEXTAREA','SINGLESELECT','MULTISELECT','MEDIA','NOMEN','NUMBER') NOT NULL DEFAULT 'TEXT';
ALTER TABLE fields ADD min DECIMAL(20,10) AFTER sort_index;
ALTER TABLE fields ADD max DECIMAL(20,10) AFTER min;
ALTER TABLE fields ADD step DECIMAL(20,10) AFTER max;
ALTER TABLE fields ADD min_color VARCHAR(255) AFTER step;
ALTER TABLE fields ADD max_color VARCHAR(255) AFTER min_color;
