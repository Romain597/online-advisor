-- Remove all data
DELETE FROM `accounts`;
DELETE FROM `accounts_types`;
DELETE FROM `ratings_subjects`;
DELETE FROM `ratings_subjects_types`;
DELETE FROM `ratings_comments`;

-- Add a admin account
REPLACE INTO `accounts` (`id_account`, `account_type_id`, `account_login_identifier`, `account_login_password`, `account_pseudo`, `account_token`, `account_add_date`, `account_last_visit`) VALUES
(1, 1, 'user@orange.fr', '$2y$10$ts/8PLVhwuBFBo1VaCmbRuWoKjOe0SxZy6jfA5NsjF9HmR/pcaPbe', 'Romain', '5f4e51bd58a32605186540', '2020-09-01 00:00:00', '2020-09-01 00:00:00');

-- Add a basic type of account
REPLACE INTO `accounts_types` (`id_account_type`, `account_type_name`) VALUES
(1, 'basic');

-- add a dummy scoring to admin account
REPLACE INTO `ratings_subjects` (`id_rating_subject`, `rating_subject_account_id`, `rating_subject_type_id`, `rating_subject_name`, `rating_subject_title`, `rating_subject_description`, `rating_subject_rank`, `rating_subject_add_date`, `rating_subject_update_date`) VALUES
(1, 1, 1, 'Livre enfant', 'Le Voyage au Centre de la Terre', 'Avis sur le livre pour enfant \"Le Voyage au Centre de la Terre\" de Jule Verne', 5, '2020-09-01 00:00:00', NULL);

-- add 3 categories of scorings
REPLACE INTO `ratings_subjects_types` (`id_rating_subject_type`, `rating_subject_type_name`) VALUES
(1, 'Oeuvre'),
(2, 'Objet'),
(3, 'Prestation');