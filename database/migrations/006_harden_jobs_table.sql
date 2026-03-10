-- Adds retry/observability columns in an idempotent way.

SET @tbl := 'jobs';

SET @add_attempts := (
    SELECT IF(
        (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = @tbl AND COLUMN_NAME = 'attempts') = 0,
        'ALTER TABLE jobs ADD COLUMN attempts INT NOT NULL DEFAULT 0 AFTER status',
        'SELECT 1'
    )
);
PREPARE stmt_attempts FROM @add_attempts;
EXECUTE stmt_attempts;
DEALLOCATE PREPARE stmt_attempts;

SET @add_max_attempts := (
    SELECT IF(
        (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = @tbl AND COLUMN_NAME = 'max_attempts') = 0,
        'ALTER TABLE jobs ADD COLUMN max_attempts INT NOT NULL DEFAULT 3 AFTER attempts',
        'SELECT 1'
    )
);
PREPARE stmt_max_attempts FROM @add_max_attempts;
EXECUTE stmt_max_attempts;
DEALLOCATE PREPARE stmt_max_attempts;

SET @add_last_error := (
    SELECT IF(
        (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = @tbl AND COLUMN_NAME = 'last_error') = 0,
        'ALTER TABLE jobs ADD COLUMN last_error TEXT NULL AFTER max_attempts',
        'SELECT 1'
    )
);
PREPARE stmt_last_error FROM @add_last_error;
EXECUTE stmt_last_error;
DEALLOCATE PREPARE stmt_last_error;

SET @add_processing_at := (
    SELECT IF(
        (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = @tbl AND COLUMN_NAME = 'processing_at') = 0,
        'ALTER TABLE jobs ADD COLUMN processing_at TIMESTAMP NULL AFTER last_error',
        'SELECT 1'
    )
);
PREPARE stmt_processing_at FROM @add_processing_at;
EXECUTE stmt_processing_at;
DEALLOCATE PREPARE stmt_processing_at;

SET @add_completed_at := (
    SELECT IF(
        (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = @tbl AND COLUMN_NAME = 'completed_at') = 0,
        'ALTER TABLE jobs ADD COLUMN completed_at TIMESTAMP NULL AFTER processing_at',
        'SELECT 1'
    )
);
PREPARE stmt_completed_at FROM @add_completed_at;
EXECUTE stmt_completed_at;
DEALLOCATE PREPARE stmt_completed_at;

SET @add_failed_at := (
    SELECT IF(
        (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = @tbl AND COLUMN_NAME = 'failed_at') = 0,
        'ALTER TABLE jobs ADD COLUMN failed_at TIMESTAMP NULL AFTER completed_at',
        'SELECT 1'
    )
);
PREPARE stmt_failed_at FROM @add_failed_at;
EXECUTE stmt_failed_at;
DEALLOCATE PREPARE stmt_failed_at;

SET @add_updated_at := (
    SELECT IF(
        (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = @tbl AND COLUMN_NAME = 'updated_at') = 0,
        'ALTER TABLE jobs ADD COLUMN updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP AFTER failed_at',
        'SELECT 1'
    )
);
PREPARE stmt_updated_at FROM @add_updated_at;
EXECUTE stmt_updated_at;
DEALLOCATE PREPARE stmt_updated_at;

