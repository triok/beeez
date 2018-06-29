-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июн 26 2018 г., 23:16
-- Версия сервера: 5.6.38
-- Версия PHP: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `lavoro`
--

-- --------------------------------------------------------

--
-- Структура таблицы `applications`
--

CREATE TABLE `applications` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `job_id` int(10) UNSIGNED DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `admin_remarks` text COLLATE utf8mb4_unicode_ci,
  `deadline` timestamp NULL DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `job_price` double(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `applications`
--

INSERT INTO `applications` (`id`, `user_id`, `job_id`, `remarks`, `admin_remarks`, `deadline`, `status`, `created_at`, `updated_at`, `job_price`) VALUES
(1, 3, 2, NULL, NULL, NULL, 'pending', '2018-05-27 12:48:11', '2018-05-27 12:48:11', 5.00);

-- --------------------------------------------------------

--
-- Структура таблицы `bookmarks`
--

CREATE TABLE `bookmarks` (
  `id` int(10) UNSIGNED NOT NULL,
  `job_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `fav` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cat_order` int(11) DEFAULT '0',
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `name`, `desc`, `cat_order`, `parent_id`, `created_at`, `updated_at`) VALUES
(1, 'default', 'Default category', 0, NULL, '2018-05-02 14:24:02', '2018-05-02 14:24:02'),
(2, 'subcategory1', NULL, 0, 1, '2018-05-02 14:25:28', '2018-05-02 14:25:28'),
(3, 'Main Project', NULL, 0, NULL, '2018-05-02 14:25:41', '2018-05-02 14:25:41'),
(4, 'Subcategory', NULL, 0, 3, '2018-05-02 14:25:53', '2018-05-02 14:25:53'),
(5, 'subcategory 2', NULL, 0, 3, '2018-05-02 14:26:15', '2018-05-02 14:26:15'),
(6, 'default', 'Default category', 0, NULL, '2018-05-22 19:23:56', '2018-05-22 19:23:56'),
(7, 'Techs', NULL, 0, NULL, '2018-05-24 18:15:46', '2018-05-24 18:15:46'),
(8, 'Copywriting', NULL, 0, NULL, '2018-05-24 18:16:15', '2018-05-24 18:16:15'),
(9, 'Drawing', NULL, 0, NULL, '2018-05-24 18:16:36', '2018-05-24 18:16:36'),
(10, 'Coding', NULL, 0, NULL, '2018-05-24 18:16:40', '2018-05-24 18:16:40'),
(11, 'Uploading data', NULL, 0, NULL, '2018-05-24 18:16:52', '2018-05-24 18:16:52');

-- --------------------------------------------------------

--
-- Структура таблицы `conversations`
--

CREATE TABLE `conversations` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `application_id` int(10) UNSIGNED NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `difficulty_level`
--

CREATE TABLE `difficulty_level` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `difficulty_level`
--

INSERT INTO `difficulty_level` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'beginner', '2018-05-02 14:24:02', '2018-05-02 14:24:02'),
(2, 'easy', '2018-05-02 14:24:02', '2018-05-02 14:24:02'),
(3, 'intermediate', '2018-05-02 14:24:02', '2018-05-02 14:24:02'),
(4, 'advanced', '2018-05-02 14:24:02', '2018-05-02 14:24:02');

-- --------------------------------------------------------

--
-- Структура таблицы `jobs`
--

CREATE TABLE `jobs` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `instructions` longtext COLLATE utf8mb4_unicode_ci,
  `end_date` timestamp NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `price` double(8,2) NOT NULL DEFAULT '0.00',
  `difficulty_level_id` int(10) UNSIGNED DEFAULT NULL,
  `time_for_work` int(11) NOT NULL DEFAULT '1',
  `status` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `jobs`
--

INSERT INTO `jobs` (`id`, `name`, `desc`, `instructions`, `end_date`, `user_id`, `price`, `difficulty_level_id`, `time_for_work`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Upload 10 photos', '<p>Description: upload10 photos</p>', '<p>Instruction: upload 10 photos</p>', '2018-05-26', NULL, 10.00, 1, 1, 'open', NULL, '2018-05-24 18:30:55', '2018-05-24 18:30:55'),
(2, 'Write 2 text for products', '<p>Description write 2 text for products</p>', '<p>Instruction&nbsp;write 2 text for products</p>', '2018-05-25', NULL, 5.00, 1, 1, 'open', NULL, '2018-05-24 18:32:02', '2018-05-24 18:32:02'),
(3, 'Translate 2 text , about 2000 symbols', '<p>Description&nbsp;Translate 2 text , about 2000 symbols</p>', '<p>InstructionTranslate 2 text , about 2000 symbols</p>', '2018-05-27', NULL, 7.00, 1, 1, 'open', NULL, '2018-05-24 18:33:58', '2018-05-24 18:33:58');

-- --------------------------------------------------------

--
-- Структура таблицы `job_categories`
--

CREATE TABLE `job_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `job_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `job_categories`
--

INSERT INTO `job_categories` (`id`, `category_id`, `job_id`, `created_at`, `updated_at`) VALUES
(1, 5, 1, '2018-05-24 18:30:55', '2018-05-24 18:30:55'),
(2, 5, 2, '2018-05-24 18:32:02', '2018-05-24 18:32:02'),
(3, 5, 3, '2018-05-24 18:33:58', '2018-05-24 18:33:58');

-- --------------------------------------------------------

--
-- Структура таблицы `job_skills`
--

CREATE TABLE `job_skills` (
  `id` int(10) UNSIGNED NOT NULL,
  `job_id` int(10) UNSIGNED NOT NULL,
  `skill_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2017_07_14_194625_laratrust_setup_tables', 1),
(4, '2017_07_14_195600_create_difficulty_level_table', 1),
(5, '2017_07_14_220703_create_jobs_table', 1),
(6, '2017_07_14_221400_create_categories_table', 1),
(7, '2017_07_14_221406_create_job_categories_table', 1),
(8, '2017_07_15_085627_create_bookmarks_table', 1),
(9, '2017_07_15_085938_create_applications_table', 1),
(10, '2017_07_15_092838_create_payments_table', 1),
(11, '2017_07_19_093048_create_skills_table', 1),
(12, '2017_07_20_083748_create_user_skills_table', 1),
(13, '2017_07_20_083847_add_bio_to_user', 1),
(14, '2017_07_20_151336_create_conversations_table', 1),
(15, '2017_07_21_055608_create_modules_table', 1),
(16, '2017_08_03_165857_create_payouts_table', 1),
(17, '2017_08_03_191015_add_job_price_to_applications', 1),
(18, '2017_08_21_162450_AddStripeToUser', 1),
(19, '2018_05_01_111159_create_parent_id_in_category', 1),
(20, '2018_05_27_144819_time_for_work', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `modules`
--

CREATE TABLE `modules` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `modules`
--

INSERT INTO `modules` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'users', '2018-05-02 14:24:02', '2018-05-02 14:24:02'),
(2, 'profile', '2018-05-02 14:24:02', '2018-05-02 14:24:02'),
(3, 'jobs', '2018-05-02 14:24:02', '2018-05-02 14:24:02'),
(4, 'job-categories', '2018-05-02 14:24:02', '2018-05-02 14:24:02'),
(5, 'job-applications', '2018-05-02 14:24:02', '2018-05-02 14:24:02'),
(6, 'jobs-manager', '2018-05-02 14:24:02', '2018-05-02 14:24:02'),
(7, 'job-skills', '2018-05-02 14:24:02', '2018-05-02 14:24:02'),
(8, 'application-message', '2018-05-02 14:24:02', '2018-05-02 14:24:02'),
(9, 'payouts', '2018-05-02 14:24:02', '2018-05-02 14:24:02'),
(10, 'logs', '2018-05-02 14:24:02', '2018-05-02 14:24:02');

-- --------------------------------------------------------

--
-- Структура таблицы `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `payments`
--

CREATE TABLE `payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `job_id` int(10) UNSIGNED DEFAULT NULL,
  `amount` double(8,2) NOT NULL DEFAULT '0.00',
  `job_amount` double(8,2) NOT NULL DEFAULT '0.00',
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `paid_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `payouts`
--

CREATE TABLE `payouts` (
  `id` int(10) UNSIGNED NOT NULL,
  `application_id` int(10) UNSIGNED NOT NULL,
  `pay_method` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double(8,2) NOT NULL,
  `txn_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency` char(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `payment_email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paypal_verify_sign` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'create-users', 'Create Users', 'Create Users', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(2, 'read-users', 'Read Users', 'Read Users', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(3, 'update-users', 'Update Users', 'Update Users', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(4, 'delete-users', 'Delete Users', 'Delete Users', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(5, 'create-jobs', 'Create Jobs', 'Create Jobs', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(6, 'read-jobs', 'Read Jobs', 'Read Jobs', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(7, 'update-jobs', 'Update Jobs', 'Update Jobs', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(8, 'delete-jobs', 'Delete Jobs', 'Delete Jobs', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(9, 'create-job-categories', 'Create Job-categories', 'Create Job-categories', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(10, 'read-job-categories', 'Read Job-categories', 'Read Job-categories', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(11, 'update-job-categories', 'Update Job-categories', 'Update Job-categories', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(12, 'delete-job-categories', 'Delete Job-categories', 'Delete Job-categories', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(13, 'create-job-skills', 'Create Job-skills', 'Create Job-skills', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(14, 'read-job-skills', 'Read Job-skills', 'Read Job-skills', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(15, 'update-job-skills', 'Update Job-skills', 'Update Job-skills', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(16, 'delete-job-skills', 'Delete Job-skills', 'Delete Job-skills', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(17, 'create-job-applications', 'Create Job-applications', 'Create Job-applications', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(18, 'read-job-applications', 'Read Job-applications', 'Read Job-applications', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(19, 'update-job-applications', 'Update Job-applications', 'Update Job-applications', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(20, 'delete-job-applications', 'Delete Job-applications', 'Delete Job-applications', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(21, 'create-application-message', 'Create Application-message', 'Create Application-message', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(22, 'read-application-message', 'Read Application-message', 'Read Application-message', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(23, 'update-application-message', 'Update Application-message', 'Update Application-message', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(24, 'delete-application-message', 'Delete Application-message', 'Delete Application-message', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(25, 'create-payouts', 'Create Payouts', 'Create Payouts', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(26, 'read-payouts', 'Read Payouts', 'Read Payouts', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(27, 'update-payouts', 'Update Payouts', 'Update Payouts', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(28, 'delete-payouts', 'Delete Payouts', 'Delete Payouts', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(29, 'create-logs', 'Create Logs', 'Create Logs', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(30, 'read-logs', 'Read Logs', 'Read Logs', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(31, 'update-logs', 'Update Logs', 'Update Logs', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(32, 'delete-logs', 'Delete Logs', 'Delete Logs', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(33, 'read-profile', 'Read Profile', 'Read Profile', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(34, 'update-profile', 'Update Profile', 'Update Profile', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(35, 'create-jobs-manager', 'Create Jobs-manager', NULL, '2018-05-24 18:28:21', '2018-05-24 18:28:21'),
(36, 'update-jobs-manager', 'Update Jobs-manager', NULL, '2018-05-24 18:28:21', '2018-05-24 18:28:21'),
(37, 'read-jobs-manager', 'Read Jobs-manager', NULL, '2018-05-24 18:28:21', '2018-05-24 18:28:21'),
(38, 'delete-jobs-manager', 'Delete Jobs-manager', NULL, '2018-05-24 18:28:21', '2018-05-24 18:28:21');

-- --------------------------------------------------------

--
-- Структура таблицы `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `permission_role`
--

INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(5, 2),
(6, 2),
(7, 2),
(8, 2),
(9, 2),
(10, 2),
(11, 2),
(12, 2),
(13, 2),
(14, 2),
(15, 2),
(16, 2),
(17, 2),
(18, 2),
(19, 2),
(20, 2),
(21, 2),
(22, 2),
(23, 2),
(24, 2),
(33, 2),
(34, 2),
(1, 3),
(2, 3),
(3, 3),
(4, 3),
(5, 3),
(6, 3),
(7, 3),
(8, 3),
(9, 3),
(10, 3),
(11, 3),
(12, 3),
(17, 3),
(18, 3),
(19, 3),
(20, 3),
(35, 3),
(36, 3),
(37, 3),
(38, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `permission_user`
--

CREATE TABLE `permission_user` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `permission_id` int(10) UNSIGNED NOT NULL,
  `user_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Admin', 'Admin', '2018-05-22 19:23:55', '2018-05-22 19:23:55'),
(2, 'manager', 'Manager', 'Manager', '2018-05-22 19:23:56', '2018-05-22 19:23:56'),
(3, 'user', 'User', 'User', '2018-05-22 19:23:56', '2018-05-22 19:23:56');

-- --------------------------------------------------------

--
-- Структура таблицы `role_user`
--

CREATE TABLE `role_user` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `user_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `role_user`
--

INSERT INTO `role_user` (`user_id`, `role_id`, `user_type`) VALUES
(1, 1, 'App\\User'),
(2, 2, 'App\\User'),
(3, 3, 'App\\User'),
(4, 3, 'App\\User');

-- --------------------------------------------------------

--
-- Структура таблицы `skills`
--

CREATE TABLE `skills` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `time_for_work`
--

CREATE TABLE `time_for_work` (
  `id` int(10) UNSIGNED NOT NULL,
  `value` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `confirmation_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `stripe_public_key` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_secret_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `confirmation_code`, `remember_token`, `created_at`, `updated_at`, `bio`, `stripe_public_key`, `stripe_secret_key`) VALUES
(1, 'Admin', 'admin@app.com', '$2y$10$pqlNx9hxk6fWM1EeLZUc.uMWJXgqikHpdKQKaG.9CX4hihgFpsU2G', NULL, 'E5u7Dyq8m41YuLdhq0GKXYnpJRsSUNEvoFVIJuWf7eWA1g542oFUHbgutNY1', '2018-05-22 19:23:56', '2018-05-22 19:23:56', NULL, NULL, NULL),
(2, 'Manager', 'manager@app.com', '$2y$10$4vCX6Vq/6paVvcWw2MCWh.2UMEq4aQI.FkUPqFtRrXaewwNyJH6hq', NULL, 'yqsCRVaqiTBBt6QQCI5OKRVjBkYS9LHWl9ZTr9dWsw2cy6ALImBUOT4LtX9Q', '2018-05-22 19:23:56', '2018-05-22 19:23:56', NULL, NULL, NULL),
(3, 'User', 'user@app.com', '$2y$10$sdM4NQfrrjJD53GYjY5H8OHsT45NWnbSGA5JESAWOwuidaJMaHIWi', NULL, 'MRT8hlxsrKhc3fHQhFH3FEvmphA8OsFWVoIx7EEd3NHguoLoRGxdUnMc3cZt', '2018-05-22 19:23:56', '2018-05-22 19:23:56', NULL, NULL, NULL),
(4, 'Roman', 'bikkembergs@bk.ru', '$2y$10$LP610KrYP4.3fe0BQ2a/7elooEG5U7lW7ackL7IDAnR/cRUWKK9.O', NULL, 'BevRG7uMh477UEUimx4AG0ItzdaK4JSxW67vGmm7X6Bkt16O49ZC11FBKwf6', '2018-05-24 18:12:15', '2018-05-24 18:12:15', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `user_skills`
--

CREATE TABLE `user_skills` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `skill_id` int(10) UNSIGNED NOT NULL,
  `skill_level` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `applications_user_id_foreign` (`user_id`),
  ADD KEY `applications_job_id_foreign` (`job_id`);

--
-- Индексы таблицы `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookmarks_job_id_user_id_unique` (`job_id`,`user_id`),
  ADD KEY `bookmarks_user_id_foreign` (`user_id`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversations_user_id_foreign` (`user_id`),
  ADD KEY `conversations_application_id_foreign` (`application_id`);

--
-- Индексы таблицы `difficulty_level`
--
ALTER TABLE `difficulty_level`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_user_id_foreign` (`user_id`),
  ADD KEY `jobs_difficulty_level_id_foreign` (`difficulty_level_id`);

--
-- Индексы таблицы `job_categories`
--
ALTER TABLE `job_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `job_categories_category_id_job_id_unique` (`category_id`,`job_id`),
  ADD KEY `job_categories_job_id_foreign` (`job_id`);

--
-- Индексы таблицы `job_skills`
--
ALTER TABLE `job_skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_skills_job_id_foreign` (`job_id`),
  ADD KEY `job_skills_skill_id_foreign` (`skill_id`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `modules_name_unique` (`name`);

--
-- Индексы таблицы `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Индексы таблицы `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_job_id_foreign` (`job_id`),
  ADD KEY `payments_user_id_foreign` (`user_id`),
  ADD KEY `payments_paid_by_foreign` (`paid_by`);

--
-- Индексы таблицы `payouts`
--
ALTER TABLE `payouts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payouts_txn_id_unique` (`txn_id`),
  ADD KEY `payouts_user_id_foreign` (`user_id`);

--
-- Индексы таблицы `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`);

--
-- Индексы таблицы `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `permission_role_role_id_foreign` (`role_id`);

--
-- Индексы таблицы `permission_user`
--
ALTER TABLE `permission_user`
  ADD PRIMARY KEY (`user_id`,`permission_id`,`user_type`),
  ADD KEY `permission_user_permission_id_foreign` (`permission_id`);

--
-- Индексы таблицы `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Индексы таблицы `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`user_id`,`role_id`,`user_type`),
  ADD KEY `role_user_role_id_foreign` (`role_id`);

--
-- Индексы таблицы `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `skills_name_unique` (`name`);

--
-- Индексы таблицы `time_for_work`
--
ALTER TABLE `time_for_work`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Индексы таблицы `user_skills`
--
ALTER TABLE `user_skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_skills_user_id_foreign` (`user_id`),
  ADD KEY `user_skills_skill_id_foreign` (`skill_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `difficulty_level`
--
ALTER TABLE `difficulty_level`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `job_categories`
--
ALTER TABLE `job_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `job_skills`
--
ALTER TABLE `job_skills`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `payouts`
--
ALTER TABLE `payouts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT для таблицы `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `time_for_work`
--
ALTER TABLE `time_for_work`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `user_skills`
--
ALTER TABLE `user_skills`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD CONSTRAINT `bookmarks_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookmarks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_difficulty_level_id_foreign` FOREIGN KEY (`difficulty_level_id`) REFERENCES `difficulty_level` (`id`),
  ADD CONSTRAINT `jobs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `job_categories`
--
ALTER TABLE `job_categories`
  ADD CONSTRAINT `job_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `job_categories_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `job_skills`
--
ALTER TABLE `job_skills`
  ADD CONSTRAINT `job_skills_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `job_skills_skill_id_foreign` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_paid_by_foreign` FOREIGN KEY (`paid_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `payouts`
--
ALTER TABLE `payouts`
  ADD CONSTRAINT `payouts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `permission_user`
--
ALTER TABLE `permission_user`
  ADD CONSTRAINT `permission_user_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permission_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_skills`
--
ALTER TABLE `user_skills`
  ADD CONSTRAINT `user_skills_skill_id_foreign` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_skills_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
