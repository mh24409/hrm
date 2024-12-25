-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
--
-- Table structure for table `acc_coas`
--

CREATE TABLE `acc_coas` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `head_level` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED NOT NULL,
  `acc_type_id` bigint UNSIGNED NOT NULL,
  `is_cash_nature` tinyint(1) NOT NULL DEFAULT '0',
  `is_bank_nature` tinyint(1) NOT NULL DEFAULT '0',
  `is_budget` tinyint(1) NOT NULL DEFAULT '0',
  `is_depreciation` tinyint(1) NOT NULL DEFAULT '0',
  `depreciation_rate` int DEFAULT NULL,
  `is_subtype` tinyint(1) NOT NULL DEFAULT '0',
  `subtype_id` bigint UNSIGNED DEFAULT NULL,
  `is_stock` tinyint(1) NOT NULL DEFAULT '0',
  `is_fixed_asset_schedule` tinyint(1) NOT NULL DEFAULT '0',
  `note_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `asset_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dep_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `acc_coas`
--

INSERT INTO `acc_coas` (`id`, `uuid`, `account_code`, `account_name`, `head_level`, `parent_id`, `acc_type_id`, `is_cash_nature`, `is_bank_nature`, `is_budget`, `is_depreciation`, `depreciation_rate`, `is_subtype`, `subtype_id`, `is_stock`, `is_fixed_asset_schedule`, `note_no`, `asset_code`, `dep_code`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'e96caced-b619-4713-a407-98bc8c40b6d1', '1', 'Assets', 1, 0, 1, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:15:54', '2024-05-05 17:15:54', NULL),
(2, '43e0aa60-c478-47e6-b300-8a3c981439cb', '2', 'Liabilities', 1, 0, 2, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:15:54', '2024-05-05 17:15:54', NULL),
(3, '2416daed-5852-4939-aaa6-c5e01dcfb610', '3', 'Income', 1, 0, 3, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:15:54', '2024-05-05 17:15:54', NULL),
(4, 'df87b75b-0663-4f8b-8116-779080e13993', '4', 'Expenses', 1, 0, 4, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:15:54', '2024-05-05 17:15:54', NULL),
(5, '9bfa16bb-e072-4fe6-b71c-5ef04f419d8b', '5', 'Share Holder Equity', 1, 0, 5, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:15:54', '2024-05-05 17:15:54', NULL),
(6, 'ffb32b28-4c66-43dc-a7a4-4c11c3b2bb8c', '11', 'Current Asset', 2, 1, 1, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:16:18', '2024-05-05 17:16:18', NULL),
(7, 'b1ae5df2-affa-4ae4-9395-e383ac720ce2', '12', 'Fixed Assets', 2, 1, 1, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:16:39', '2024-05-05 17:16:39', NULL),
(8, '9cdad573-14a0-4330-9aab-a17ea84eb0c8', '21', 'Current Liabilities', 2, 2, 2, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:16:55', '2024-05-05 17:16:55', NULL),
(9, 'a6f5dd9e-31c3-4e70-846a-63d56b2dfb24', '22', 'Long Term Liabilities', 2, 2, 2, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:17:05', '2024-05-05 17:17:05', NULL),
(10, 'c5ead3ed-a991-4a43-b6c3-3dd72e97bb1d', '31', 'Direct Income', 2, 3, 3, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:17:20', '2024-05-05 17:17:20', NULL),
(11, '735f589d-2101-443c-82a5-54386b95e6fd', '32', 'Indirect Income', 2, 3, 3, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:17:30', '2024-05-05 17:17:30', NULL),
(12, '5e454b40-c898-4787-9ad5-013f7bfd5b87', '41', 'Cost of Goods Solds', 2, 4, 4, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:17:41', '2024-05-05 17:17:41', NULL),
(13, 'f5566ddf-c25e-4614-959d-a169541fe7bb', '42', 'Over Head Cost', 2, 4, 4, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:17:51', '2024-05-05 17:17:51', NULL),
(14, '098c7edb-9377-40ca-8142-e24bc3dd1c84', '51', 'Equity', 2, 5, 5, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:18:01', '2024-05-05 17:18:01', NULL),
(15, 'b5a09c37-3a28-498c-aac8-ba755abdf46e', '1101', 'Account Receivable', 3, 6, 1, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:18:43', '2024-05-05 17:18:43', NULL),
(16, '085e69a0-355f-494b-8de5-2ab4de13e443', '1102', 'Advance', 3, 6, 1, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:19:00', '2024-05-05 17:19:00', NULL),
(17, 'da8d1eb4-6fc6-432e-8404-c31fb5d88173', '1103', 'Cash', 3, 6, 1, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:19:11', '2024-05-05 17:19:11', NULL),
(18, 'f5dbdd3d-3fa7-4798-808c-ba1327897477', '1104', 'Cash at Bank', 3, 6, 1, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:19:23', '2024-05-05 17:19:23', NULL),
(19, 'c5536d60-b52b-444c-827b-a29be9c5c414', '1105', 'Inventory', 3, 6, 1, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:19:33', '2024-05-05 17:19:33', NULL),
(20, 'da015745-c56c-46a7-9fa8-8b84511fe273', '1106', 'Prepaid Expenses', 3, 6, 1, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:19:43', '2024-05-05 17:19:43', NULL),
(21, 'b98a7c3c-5e4d-42f1-bfca-5b28926c86b3', '1201', 'Goodwills', 3, 7, 1, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:20:21', '2024-05-05 17:20:21', NULL),
(22, '82490308-c330-4d22-a6ef-17b25e57e467', '1202', 'Property & Equipment', 3, 7, 1, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:20:27', '2024-05-05 17:20:27', NULL),
(23, 'a89a8aa3-a0b0-45ca-ac5f-f6ed966e9bfc', '2101', 'Accounts Payable', 3, 8, 2, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:20:56', '2024-05-05 17:20:56', NULL),
(24, '0d690279-7815-449f-beed-e147c4ecd55f', '2102', 'Accrued Expenses', 3, 8, 2, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:21:05', '2024-05-05 17:21:05', NULL),
(25, '69aae18a-fb5a-4300-94f5-8f65ed5a981f', '2103', 'Depreciations', 3, 8, 2, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:21:14', '2024-05-05 17:21:14', NULL),
(26, 'd685a235-56e8-4d01-81d0-32b9782c912c', '2104', 'Provisions', 3, 8, 2, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:21:23', '2024-05-05 17:21:23', NULL),
(27, '24fc9007-dfe3-4329-9055-9ae296febb2d', '2105', 'Unearned Revenue', 3, 8, 2, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:21:33', '2024-05-05 17:21:33', NULL),
(28, '8f0a4cc2-6cbb-4c0c-ab9b-28124b5a19d8', '2201', 'Long-Term Debit', 3, 9, 2, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:21:46', '2024-05-05 17:43:27', NULL),
(29, '5d26363d-615b-459f-aa86-8fb43b0ec1cd', '2202', 'Other Long-Term  Liabilities', 3, 9, 2, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:21:56', '2024-05-05 17:21:56', NULL),
(30, '6800dbe9-854c-4204-81a7-cb2a6b1a4b93', '3101', 'Construction Income', 3, 10, 3, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:22:24', '2024-05-05 17:22:24', NULL),
(31, 'd8e73749-8709-4b53-b344-4bd3463b2772', '3102', 'Reimbursement Income', 3, 10, 3, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:22:35', '2024-05-05 17:22:35', NULL),
(32, 'd22cf397-b8d4-4cf9-b858-04157cf310a4', '3103', 'Sales Accounts', 3, 10, 3, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:22:45', '2024-05-05 17:22:45', NULL),
(33, '23d4d604-3570-4927-92ec-1b893a54db10', '4101', 'Cost of Goods Sold', 3, 12, 4, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:23:10', '2024-05-05 17:23:10', NULL),
(34, '8814f38c-7db7-44cf-adcf-33d0377f4106', '4102', 'Job Expenses', 3, 12, 4, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:23:25', '2024-05-05 17:23:25', NULL),
(35, 'e343cc45-d123-4067-8579-44baf863b452', '4201', 'Automobile', 3, 13, 4, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:24:12', '2024-05-05 17:24:12', NULL),
(36, '15025f55-7633-4177-bb85-47b8d94569fa', '4202', 'Bank Service Charges', 3, 13, 4, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:24:21', '2024-05-05 17:24:21', NULL),
(37, '4c60c01a-1f71-48bc-a43c-945307e16ebe', '4203', 'Employeer ICF Expense', 3, 13, 4, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:24:32', '2024-05-05 17:24:32', NULL),
(38, 'd97f1b4e-ea82-4698-95d9-81b83b162ba5', '4204', 'Insurance', 3, 13, 4, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:24:42', '2024-05-05 17:24:42', NULL),
(39, '9edd99bc-2325-4cd3-8cee-54ac04ea7dcb', '4205', 'Interest Expenses', 3, 13, 4, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:24:52', '2024-05-05 17:24:52', NULL),
(40, '78ba83a7-d225-4b18-be33-f7b34cc1ee4c', '4206', 'Payroll Expenses', 3, 13, 4, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:25:01', '2024-05-05 17:25:01', NULL),
(41, 'f9e92839-32be-4662-b7a1-a032e8cb8152', '4207', 'Postage', 3, 13, 4, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:25:11', '2024-05-05 17:25:11', NULL),
(42, 'd42dced8-d5d2-46b2-b267-416cd3ef84d1', '4208', 'Professional Fees', 3, 13, 4, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:25:21', '2024-05-05 17:25:21', NULL),
(43, 'e1f95cb1-4613-4130-9697-024dc2c520cd', '4209', 'Purchase Account', 3, 13, 4, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:25:31', '2024-05-05 17:25:31', NULL),
(44, 'e893adbd-daf1-484d-b4a3-9c9308b915f3', '4210', 'Repairs', 3, 13, 4, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:25:41', '2024-05-05 17:25:41', NULL),
(45, 'b6dfaac1-76bb-4904-8618-193115046e5e', '4211', 'State Income Tax', 3, 13, 4, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:25:50', '2024-05-05 17:25:50', NULL),
(46, '3d11884c-df6a-406d-95a4-d1dc37acd7e4', '4212', 'Tools and Macchnery', 3, 13, 4, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:26:01', '2024-05-05 17:26:01', NULL),
(47, 'f6fa7b14-e4b5-4a4b-acf0-0f871cb2d572', '4213', 'Utilities', 3, 13, 4, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:26:11', '2024-05-05 17:26:11', NULL),
(48, '374813ee-dc69-41b6-bec4-f2ed14615c7b', '5101', 'Equity Capital', 3, 14, 5, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:26:32', '2024-05-05 17:26:32', NULL),
(49, '0047d009-c289-428a-bf81-0010fa9ac20d', '5102', 'Retained Earnings', 3, 14, 5, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:26:41', '2024-05-05 17:26:41', NULL),
(50, 'c2c856b0-56ef-44fe-8c25-5fef33764571', '1101001', 'Customer Receivable', 4, 15, 1, 0, 0, 0, 0, NULL, 1, 2, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:27:33', '2024-05-05 17:27:33', NULL),
(51, 'ab3a6614-54e3-4e4b-a16e-acbdda8f2437', '1101002', 'Employee Receivable', 4, 15, 1, 0, 0, 0, 0, NULL, 1, 1, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:27:50', '2024-05-05 17:27:50', NULL),
(52, '32333522-4df9-4a87-8114-bcf2fb0c7659', '1102001', 'Advance Against Customer', 4, 16, 1, 0, 0, 0, 0, NULL, 1, 2, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:28:28', '2024-05-05 17:28:28', NULL),
(53, '8e008cf4-f6a2-4973-8666-2453b9dde6ee', '1102002', 'Advance against Employee', 4, 16, 1, 0, 0, 0, 0, NULL, 1, 1, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:28:54', '2024-05-05 17:28:54', NULL),
(54, '06b4e236-cbe5-4fa3-a71b-3f82485bed68', '1103001', 'Cash In Hand', 4, 17, 1, 1, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:29:23', '2024-05-05 17:29:23', NULL),
(55, '014cd7b9-8bb3-4615-8a94-a1efc5e6ec1d', '1103002', 'Petty Cash', 4, 17, 1, 1, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:29:37', '2024-05-05 17:29:37', NULL),
(56, 'e5db98d2-e8db-4b1a-b43d-c9d1d7a9ee2c', '1104001', 'ABC Bank', 4, 18, 1, 0, 1, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:29:51', '2024-05-05 17:29:51', NULL),
(57, '2e2a7acc-094b-440a-ae7f-01c8533fcd4b', '1201001', 'Goodwill', 4, 21, 1, 0, 0, 0, 0, 15, 0, NULL, 0, 1, NULL, 'GD001', NULL, 1, 1, 1, '2024-05-05 17:30:53', '2024-05-05 17:30:53', NULL),
(58, '56d15f32-d4e9-4693-a87b-8e5fee3482da', '2101001', 'Supplier Payable', 4, 23, 2, 0, 0, 0, 0, NULL, 1, 3, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:31:31', '2024-05-05 17:31:31', NULL),
(59, '3d3faa8a-143f-4ef4-9015-c0bb9b38f230', '2103001', 'Depreciation of Goodwill', 4, 25, 2, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:42:16', '2024-05-05 17:42:16', NULL),
(60, '94ee5905-e5e2-4764-bcdf-91ea8ffcd5f4', '2104001', 'Provision for npf contribution', 4, 26, 2, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:42:29', '2024-05-05 17:42:29', NULL),
(61, 'e190f2d0-1068-4e53-9aa0-ab581544e4db', '2104002', 'Provision for State Income Tax', 4, 26, 2, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:42:40', '2024-05-05 17:42:40', NULL),
(62, '6bd80958-4b14-4283-a746-1ae147b6055a', '2105001', 'property sales', 4, 27, 2, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:42:57', '2024-05-05 17:42:57', NULL),
(63, 'eeb201cd-0f6c-4b5e-9c3d-0f5cafbaaaf7', '2201001', 'Debits', 4, 28, 2, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:43:17', '2024-05-05 17:43:36', NULL),
(64, 'a5895cc6-7de3-46de-9b60-a2e75dd4b82e', '2202001', 'Other Long-Term  Liabilities', 4, 29, 2, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:43:49', '2024-05-05 17:43:49', NULL),
(65, '983672c2-0164-49dd-ac04-722b35449f0c', '3103001', 'Sales Account', 4, 32, 3, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:44:23', '2024-05-05 17:44:23', NULL),
(66, 'fb9347fe-0b7d-484d-adda-687cd78d692e', '4201001', 'Purchase', 4, 35, 4, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:45:09', '2024-05-05 17:45:09', NULL),
(67, '81713b79-fdd5-4f0d-872e-02e43eb782ba', '4203001', 'Employeer 1% ICF Expense', 4, 37, 4, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:45:32', '2024-05-05 17:45:32', NULL),
(68, '6dc51d3e-0f3c-443f-88cc-8d366f533fdd', '4206001', 'Employee 10 % NPF Expenses', 4, 40, 4, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:45:45', '2024-05-05 17:45:45', NULL),
(69, '3419cce3-a3e5-471d-9c19-0f5793a2747f', '4206002', 'Employee 5 % NPF Expenses', 4, 40, 4, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:45:55', '2024-05-05 17:45:55', NULL),
(70, 'cf9f10c9-59dd-40d1-9e66-8eb28dfb1008', '4206003', 'Salary Expense', 4, 40, 4, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:46:06', '2024-05-05 17:46:06', NULL),
(71, '82d582b5-0ad1-4667-8f0e-c35190ea4a1c', '4211001', 'State Income Tax', 4, 45, 4, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:46:19', '2024-05-05 17:46:19', NULL),
(72, 'e7b843d4-e8a2-4501-b956-fa991fb72dfb', '4213001', 'Electic Bill', 4, 47, 4, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:46:30', '2024-05-05 17:46:30', NULL),
(73, '168428bc-652a-49df-8bad-5e132b48b267', '4213002', 'House Rent', 4, 47, 4, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:46:40', '2024-05-05 17:46:40', NULL),
(74, 'e10dd7a8-86d4-4bbc-9901-8458e4152873', '5101001', 'Capital Fund', 4, 48, 5, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:47:02', '2024-05-05 17:47:02', NULL),
(75, '23ed5515-e95d-4618-bb3f-b66c0bc8f6eb', '5102001', 'Current year Profit & Loss', 4, 49, 5, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:47:18', '2024-05-05 17:47:18', NULL),
(76, '5af4833a-e33c-4a88-895a-d285efbb6213', '5102002', 'Last year Profit & Loss', 4, 49, 5, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, NULL, NULL, NULL, 1, 1, 1, '2024-05-05 17:47:31', '2024-05-05 17:47:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `acc_monthly_balances`
--

CREATE TABLE `acc_monthly_balances` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `financial_year_id` bigint UNSIGNED NOT NULL,
  `acc_coa_id` bigint UNSIGNED NOT NULL,
  `balance1` double NOT NULL,
  `balance2` double NOT NULL,
  `balance3` double NOT NULL,
  `balance4` double NOT NULL,
  `balance5` double NOT NULL,
  `balance6` double NOT NULL,
  `balance7` double NOT NULL,
  `balance8` double NOT NULL,
  `balance9` double NOT NULL,
  `balance10` double NOT NULL,
  `balance11` double NOT NULL,
  `balance12` double NOT NULL,
  `total_balance` double NOT NULL,
  `updated_date` timestamp NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_opening_balances`
--

CREATE TABLE `acc_opening_balances` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `financial_year_id` bigint UNSIGNED NOT NULL,
  `acc_coa_id` bigint UNSIGNED NOT NULL,
  `acc_subtype_id` bigint UNSIGNED DEFAULT NULL,
  `acc_subcode_id` bigint UNSIGNED DEFAULT NULL,
  `debit` double DEFAULT NULL,
  `credit` double DEFAULT NULL,
  `open_date` date NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_predefine_accounts`
--

CREATE TABLE `acc_predefine_accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cash_code` int NOT NULL,
  `bank_code` int NOT NULL,
  `advance` int NOT NULL,
  `fixed_asset` int NOT NULL,
  `purchase_code` int NOT NULL,
  `purchase_discount` int NOT NULL,
  `sales_code` int NOT NULL,
  `customer_code` int NOT NULL,
  `supplier_code` int NOT NULL,
  `costs_of_good_solds` int NOT NULL,
  `vat` int NOT NULL,
  `tax` int NOT NULL,
  `inventory_code` int NOT NULL,
  `current_year_profit_loss_code` int NOT NULL,
  `last_year_profit_loss_code` int NOT NULL,
  `salary_code` int DEFAULT NULL,
  `employee_salary_expense` int DEFAULT NULL,
  `prov_state_tax` int DEFAULT NULL,
  `state_tax` int DEFAULT NULL,
  `sales_discount` int DEFAULT NULL,
  `shipping_cost1` int DEFAULT NULL,
  `shipping_cost2` int DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `prov_npf_code` int DEFAULT NULL,
  `emp_npf_contribution` int DEFAULT NULL,
  `empr_npf_contribution` int DEFAULT NULL,
  `emp_icf_contribution` int DEFAULT NULL,
  `empr_icf_contribution` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `acc_predefine_accounts`
--

INSERT INTO `acc_predefine_accounts` (`id`, `uuid`, `cash_code`, `bank_code`, `advance`, `fixed_asset`, `purchase_code`, `purchase_discount`, `sales_code`, `customer_code`, `supplier_code`, `costs_of_good_solds`, `vat`, `tax`, `inventory_code`, `current_year_profit_loss_code`, `last_year_profit_loss_code`, `salary_code`, `employee_salary_expense`, `prov_state_tax`, `state_tax`, `sales_discount`, `shipping_cost1`, `shipping_cost2`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `prov_npf_code`, `emp_npf_contribution`, `empr_npf_contribution`, `emp_icf_contribution`, `empr_icf_contribution`) VALUES
(4, '69497a30-1048-48c3-abc5-e926bf88caa3', 37, 17, 15, 51, 61, 99, 47, 36, 48, 47, 62, 50, 87, 85, 84, 44, 44, 50, 50, 75, 64, 62, 47, 39, '2022-09-23 19:20:01', '2023-09-12 22:35:43', NULL, 87, 85, 84, 44, 44);

-- --------------------------------------------------------

--
-- Table structure for table `acc_quarters`
--

CREATE TABLE `acc_quarters` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quarter` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `financial_year_id` bigint DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_subcodes`
--

CREATE TABLE `acc_subcodes` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `acc_subtype_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_no` int DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_subtypes`
--

CREATE TABLE `acc_subtypes` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtype_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `acc_subtypes`
--

INSERT INTO `acc_subtypes` (`id`, `uuid`, `subtype_name`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '4b20585e-40a7-49d9-9c21-8cad4b57fa05', 'Employee', 1, 39, NULL, '2023-02-17 17:26:09', '2023-02-17 17:26:09', NULL),
(2, '67fd5161-3a34-4540-aa03-038f6b42c718', 'Customer', 1, 39, NULL, '2023-02-17 17:26:17', '2023-02-17 17:26:17', NULL),
(3, 'b22eafba-1416-433b-974b-49afc520dda1', 'Supplier', 1, 39, NULL, '2023-02-17 17:26:26', '2023-02-17 17:26:26', NULL),
(4, 'b22eafba-1416-433b-974b-49afc520ddau', 'None', 1, 39, NULL, '2023-02-17 17:26:26', '2023-02-17 17:26:26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `acc_transactions`
--

CREATE TABLE `acc_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auto_create` tinyint(1) NOT NULL DEFAULT '0',
  `acc_coa_id` bigint UNSIGNED NOT NULL,
  `financial_year_id` bigint UNSIGNED NOT NULL,
  `acc_subtype_id` bigint UNSIGNED DEFAULT NULL,
  `acc_subcode_id` bigint UNSIGNED DEFAULT NULL,
  `voucher_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voucher_type_id` bigint UNSIGNED DEFAULT NULL,
  `reference_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voucher_date` date DEFAULT NULL,
  `narration` tinytext COLLATE utf8mb4_unicode_ci,
  `cheque_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_date` date DEFAULT NULL,
  `is_honour` tinyint(1) NOT NULL DEFAULT '0',
  `ledger_comment` tinytext COLLATE utf8mb4_unicode_ci,
  `debit` double(18,2) DEFAULT NULL,
  `credit` double(18,2) DEFAULT NULL,
  `reverse_code` bigint UNSIGNED NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `is_year_closed` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_types`
--

CREATE TABLE `acc_types` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_type_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_vouchers`
--

CREATE TABLE `acc_vouchers` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `acc_coa_id` bigint UNSIGNED NOT NULL,
  `financial_year_id` bigint UNSIGNED NOT NULL,
  `acc_subtype_id` bigint UNSIGNED DEFAULT NULL,
  `acc_subcode_id` bigint UNSIGNED DEFAULT NULL,
  `voucher_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voucher_type` bigint UNSIGNED DEFAULT NULL,
  `reference_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voucher_date` date DEFAULT NULL,
  `narration` tinytext COLLATE utf8mb4_unicode_ci,
  `cheque_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_date` date DEFAULT NULL,
  `is_honour` tinyint(1) NOT NULL DEFAULT '0',
  `ledger_comment` tinytext COLLATE utf8mb4_unicode_ci,
  `debit` double(18,2) DEFAULT NULL,
  `credit` double(18,2) DEFAULT NULL,
  `reverse_code` bigint UNSIGNED DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_voucher_types`
--

CREATE TABLE `acc_voucher_types` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `voucher_type_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `voucher_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `acc_voucher_types`
--

INSERT INTO `acc_voucher_types` (`id`, `uuid`, `voucher_type_name`, `voucher_type`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '79e5aaob-0a6e-40ed-b3fb-175a4b373350', 'Debit Voucher', 'DV', 1, '2022-09-24 00:04:47', '2022-09-24 00:04:47', NULL),
(2, '79e5naob-0a6e-40ed-b3fb-175a4b378350', 'Credit Voucher', 'CV', 1, '2022-09-24 00:04:47', '2022-09-24 00:04:47', NULL),
(3, '79e5agob-0a3e-40ed-b3fb-177a4b373350', 'Contra Voucher', 'CT', 1, '2022-09-24 00:04:47', '2022-09-24 00:04:47', NULL),
(4, '79e5faob-0a6y-40td-b3fb-175a4b375350', 'Journal Voucher', 'JV', 1, '2022-09-24 00:04:47', '2022-09-24 00:04:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint UNSIGNED NOT NULL,
  `log_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `causer_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint UNSIGNED DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(1, 'Setting (Application)', 'Application created', 'Modules\\Setting\\Entities\\Application', 'created', 1, NULL, NULL, '{\"attributes\": {\"id\": 1, \"logo\": null, \"email\": \"info@bdtask.com\", \"phone\": \"880-258970255\", \"title\": \"HRM\", \"prefix\": \"BT\", \"tax_no\": \"43242424\", \"address\": \"B-25, Mannan Plaza, 4th Floor Khilkhet, Dhaka-1229, Bangladesh\", \"favicon\": null, \"rtl_ltr\": 1, \"website\": \"https://www.bdtask.com\", \"created_at\": \"2022-10-13T04:46:42.000000Z\", \"deleted_at\": null, \"fixed_date\": 0, \"icf_amount\": 0, \"updated_at\": \"2023-01-10T11:10:42.000000Z\", \"currency_id\": 1, \"footer_text\": \"BDTASK © 2022. All Rights Reserved.\", \"language_id\": 1, \"login_image\": null, \"sidebar_logo\": null, \"floating_number\": 1, \"soc_sec_npf_tax\": 0, \"state_income_tax\": 5, \"employer_contribution\": 0, \"negative_amount_symbol\": 1, \"sidebar_collapsed_logo\": null}}', NULL, '2024-05-12 22:35:44', '2024-05-12 22:35:44'),
(2, 'Setting (Currency)', 'You have created a currency', 'Modules\\Setting\\Entities\\Currency', 'created', 1, NULL, NULL, '{\"attributes\": {\"id\": 1, \"title\": \"Taka\", \"status\": 1, \"symbol\": \"৳\", \"country_id\": 14, \"created_at\": \"2024-05-13T04:35:44.000000Z\", \"updated_at\": \"2024-05-13T04:35:44.000000Z\"}}', NULL, '2024-05-12 22:35:44', '2024-05-12 22:35:44'),
(3, 'User Type', 'You have created a User Type', 'Modules\\UserManagement\\Entities\\UserType', 'created', 1, NULL, NULL, '{\"attributes\": {\"id\": 1, \"uuid\": \"\", \"is_active\": 1, \"created_at\": \"2024-05-13T04:35:48.000000Z\", \"created_by\": null, \"deleted_at\": null, \"updated_at\": \"2024-05-13T04:35:48.000000Z\", \"updated_by\": null, \"user_type_title\": \"Admin\"}}', NULL, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(4, 'User Type', 'You have created a User Type', 'Modules\\UserManagement\\Entities\\UserType', 'created', 2, NULL, NULL, '{\"attributes\": {\"id\": 2, \"uuid\": \"\", \"is_active\": 1, \"created_at\": \"2024-05-13T04:35:48.000000Z\", \"created_by\": null, \"deleted_at\": null, \"updated_at\": \"2024-05-13T04:35:48.000000Z\", \"updated_by\": null, \"user_type_title\": \"Employee\"}}', NULL, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(5, 'User', 'created', 'App\\Models\\User', 'created', 1, NULL, NULL, '[]', NULL, '2024-05-12 22:35:48', '2024-05-12 22:35:48');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` int DEFAULT NULL,
  `currency_id` int DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prefix` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `tax_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rtl_ltr` tinyint NOT NULL DEFAULT '1' COMMENT '1=LTR,2=RTL',
  `negative_amount_symbol` tinyint NOT NULL DEFAULT '1' COMMENT '1=-,2=()',
  `floating_number` tinyint NOT NULL DEFAULT '1' COMMENT '1 = 0, 2 = 0.0 ,3= 0.00, 4= 0.000 ',
  `fixed_date` tinyint(1) NOT NULL DEFAULT '0',
  `footer_text` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sidebar_logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `favicon` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sidebar_collapsed_logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `state_income_tax` int NOT NULL DEFAULT '5',
  `soc_sec_npf_tax` int NOT NULL DEFAULT '0',
  `employer_contribution` int NOT NULL DEFAULT '0' COMMENT 'Employer Contribution in Percent',
  `icf_amount` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `language_id`, `currency_id`, `title`, `phone`, `email`, `website`, `prefix`, `address`, `tax_no`, `rtl_ltr`, `negative_amount_symbol`, `floating_number`, `fixed_date`, `footer_text`, `logo`, `sidebar_logo`, `favicon`, `sidebar_collapsed_logo`, `login_image`, `created_at`, `updated_at`, `deleted_at`, `state_income_tax`, `soc_sec_npf_tax`, `employer_contribution`, `icf_amount`) VALUES
(1, 1, 1, 'HRM', '880-258970255', 'info@bdtask.com', 'https://www.bdtask.com', 'BT', 'B-25, Mannan Plaza, 4th Floor Khilkhet, Dhaka-1229, Bangladesh', '43242424', 1, 1, 1, 0, 'BDTASK. All Rights Reserved.', 'application/1716991729logo.jpg', 'application/1716991729sidebar-logo.png', 'application/1716991729favicon.png', 'application/1716991729sidebar-collapsed-logo.png', 'application/1716991729login-image.jpg', '2022-10-12 22:46:42', '2024-05-29 08:08:49', NULL, 5, 0, 0, 0);
-- --------------------------------------------------------

--
-- Table structure for table `apply_leaves`
--

CREATE TABLE `apply_leaves` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `leave_type_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED DEFAULT NULL,
  `leave_apply_start_date` date DEFAULT NULL,
  `leave_apply_end_date` date DEFAULT NULL,
  `leave_apply_date` date NOT NULL,
  `total_apply_day` int DEFAULT NULL,
  `leave_approved_start_date` date DEFAULT NULL,
  `leave_approved_end_date` date DEFAULT NULL,
  `total_approved_day` int DEFAULT NULL,
  `is_approved_by_manager` tinyint(1) NOT NULL DEFAULT '0',
  `approved_by_manager` bigint UNSIGNED DEFAULT NULL,
  `manager_approved_date` date DEFAULT NULL,
  `manager_approved_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `leave_approved_date` date DEFAULT NULL,
  `reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------


--
-- Table structure for table `appsettings`
--

CREATE TABLE `appsettings` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acceptablerange` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `googleapi_authkey` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appsettings`
--

INSERT INTO `appsettings` (`id`, `uuid`, `latitude`, `longitude`, `acceptablerange`, `googleapi_authkey`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '6763c06b-43e0-4e29-9787-882e41029a28', NULL, NULL, NULL, 'Google API Auth Key', 1, NULL, '2024-05-16 07:00:01', '2024-05-16 07:00:01', NULL);

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `machine_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `machine_state` bigint UNSIGNED NOT NULL DEFAULT '0',
  `time` datetime NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attn_checkin_checkouts`
--

CREATE TABLE `attn_checkin_checkouts` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `state` bigint UNSIGNED NOT NULL,
  `timestamp` timestamp NOT NULL,
  `type` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attn_user_infos`
--

CREATE TABLE `attn_user_infos` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` bigint UNSIGNED NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_device_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awards`
--

CREATE TABLE `awards` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `gift` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `awarded_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `uuid`, `bank_name`, `branch_name`, `account_name`, `account_number`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '89bce523-54d2-467b-8a84-707b66e3f2b1', 'City Bank', 'Nikunja-2', 'Bdtask', '123456789', 39, NULL, '2023-05-07 17:55:41', '2023-05-07 17:55:41', NULL),
(2, 'ac742be2-4e58-406e-b484-562c20f87df4', 'dsfg', 'gdsf', 'sdf', '4345', 39, NULL, '2023-09-06 23:16:27', '2023-09-06 23:16:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bank_infos`
--

CREATE TABLE `bank_infos` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `account_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acc_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `route_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bban_num` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_address` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_infos`
--

INSERT INTO `bank_infos` (`id`, `uuid`, `employee_id`, `account_name`, `acc_number`, `bank_name`, `route_number`, `branch_name`, `bban_num`, `branch_address`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '51a2b004-38a5-4e77-be09-f22bd25b2d63', 1, 'Walter Langley', '572', 'Joelle Clay', '930', 'Pamela Marshall', NULL, NULL, 1, 39, NULL, '2023-04-16 20:46:44', '2023-04-16 20:46:44', NULL),
(2, 'd315a517-590a-4908-9886-4db7f53ae492', 2, 'Leigh Gallegos', '990', 'Britanney Allen', '536', 'Vivian Kelley', NULL, NULL, 1, 39, NULL, '2023-04-16 20:51:16', '2023-04-16 20:51:16', NULL),
(3, 'dace3b8f-6752-45cd-bce9-dcd375ed65e2', 3, 'Sophia Horton', '871', 'Stephen Casey', '856', 'Jeremy Rodriguez', NULL, NULL, 1, 39, NULL, '2023-04-16 20:56:09', '2023-04-16 20:56:09', NULL),
(4, 'c00dfae1-d355-4b06-a3b4-bd84a7dd5274', 4, 'Valentine Riley', '441', 'Deacon Guy', '467', 'Heather Richard', NULL, NULL, 1, 39, NULL, '2023-04-16 20:57:55', '2023-04-16 20:57:55', NULL),
(6, '1e1cf14d-932d-45b6-81b1-3e5ed8c93e27', 6, 'Dominic Mack', '426', 'Rahim Tran', '243', 'Ryan Bryant', NULL, NULL, 1, 39, NULL, '2023-04-16 21:02:55', '2023-04-16 21:02:55', NULL),
(9, '9db01eb2-27f6-4d92-a068-0e964ef1becf', 9, 'Hasad French', '160', 'Flynn Franks', '177', 'Damon Ramirez', NULL, NULL, 1, 39, NULL, '2023-04-16 21:29:27', '2023-04-16 21:29:27', NULL),
(10, 'b6c908dd-2aae-4782-a52c-61133964e887', 10, 'Perry Holman', '691', 'Jada Rowland', '694', 'Denise Reese', NULL, NULL, 1, 39, NULL, '2023-04-16 21:30:45', '2023-04-16 21:30:45', NULL),
(12, '97a39e0e-aa3c-4475-967f-a03025de9eb3', 12, 'Ivory Daniels', '440', 'Erich Haynes', '488', 'Ezra Wolfe', NULL, NULL, 1, 39, NULL, '2023-04-16 21:46:07', '2023-04-16 21:46:07', NULL),
(14, '7cec4f10-b9d1-4f5b-93de-61c7e8587ef2', 14, 'Allegra Contreras', '465', 'Illiana Trujillo', '951', 'Willa Olson', NULL, NULL, 1, 39, NULL, '2023-04-16 21:51:54', '2023-04-16 21:51:54', NULL),
(15, '0adc18be-8de5-4403-8bb5-54cf3b5f2114', 15, 'Ila Rodriquez', '487', 'Lacey Mcfadden', '727', 'Sharon Sharpe', NULL, NULL, 1, 39, NULL, '2023-04-16 21:52:58', '2023-04-16 21:52:58', NULL),
(18, 'fc416a05-8e70-4631-904a-943eba9c9300', 18, 'Kelsey Burton', '382', 'Aline Knowles', '69', 'Mary Blackburn', NULL, NULL, 1, 39, NULL, '2023-04-16 22:06:25', '2023-04-16 22:06:25', NULL),
(19, '21006971-8e08-4f36-aac6-1a9fb74c8a46', 19, 'Isabelle Watkins', '260', 'Preston Roach', '168', 'Marcia Lowe', NULL, NULL, 1, 39, NULL, '2023-04-16 23:15:04', '2023-04-16 23:15:04', NULL),
(20, '573e67a5-956a-45c4-9733-58c6d5791eb1', 20, 'Ibrahim Nabid', '02152565425', 'Islami Bank Ltd.', '15652', 'Nikunja', NULL, NULL, 1, 39, NULL, '2023-04-17 16:30:51', '2023-04-17 16:30:51', NULL),
(21, 'a9a7ccb6-db0a-4a52-a613-539c7dc5fd49', 21, 'Raja Sweeney', '439', 'Herrod Lyons', '547', 'Hayden Sanford', NULL, NULL, 1, 39, NULL, '2023-04-25 21:03:15', '2023-04-25 21:03:15', NULL),
(22, 'a08164e1-f0d5-49c1-b279-f2ad96b83919', 22, 'Bevis Tucker', '639', 'Brenden Wilcox', '765', 'Andrew Nunez', NULL, NULL, 1, 39, NULL, '2023-05-08 23:46:26', '2023-05-08 23:46:26', NULL),
(23, '484efac3-a9f6-4efa-8d73-3c4a9baa6de1', 23, 'Kirestin Hale', '569', 'Zane Jackson', '514', 'Brynne Oneill', NULL, NULL, 1, 39, NULL, '2023-05-08 23:54:25', '2023-05-08 23:54:25', NULL),
(24, 'cd8d7ce4-c7d3-4c81-b68a-8cba77b8ae24', 24, 'Wynne Mccall', '155', 'Astra Pierce', '984', 'Oscar Cummings', NULL, NULL, 1, 39, NULL, '2023-05-09 00:02:21', '2023-05-09 00:02:21', NULL),
(25, 'fbf6ff63-aed4-4453-b8fe-d048c02c7557', 25, 'Sloane Castro', '543', 'Nicole Moore', '255', 'Howard Noble', NULL, NULL, 1, 39, NULL, '2023-05-09 00:04:52', '2023-05-09 00:04:52', NULL),
(26, '6ba1f8f9-65c7-458a-904f-4891b2dbce45', 26, 'Lysandra Baker', '507', 'Uta Gregory', '649', 'Amal Frost', NULL, NULL, 1, 39, NULL, '2023-05-12 17:40:02', '2023-05-12 17:40:02', NULL),
(27, 'a4cfdb2b-7be9-4415-b4d3-c3d94a7b1af6', 27, 'Alana Suarez', '870', 'Kelsie Langley', '499', 'Lydia Curry', NULL, NULL, 1, 39, NULL, '2023-05-12 19:43:29', '2023-05-12 19:43:29', NULL),
(28, 'ba18bf11-a119-4adb-9558-e2351cddf041', 28, 'Derek Irwin', '602', 'Keaton Huffman', '452', 'Isabelle Spears', NULL, NULL, 1, 39, NULL, '2023-05-12 20:25:24', '2023-05-12 20:25:24', NULL),
(29, '9a12eba3-614e-44fc-aa74-9a9220a21c55', 29, 'Len Madden', '979', 'Kevyn Whitaker', '609', 'Levi Sawyer', NULL, NULL, 1, 39, NULL, '2023-05-12 21:33:27', '2023-05-12 21:33:27', NULL),
(30, '958f8e74-ebe3-4303-849f-a578fe8eeb35', 1, 'Nerea Sanders', '184', 'Len Cannon', '530', 'Isadora Roy', NULL, NULL, 1, 39, NULL, '2023-06-04 18:36:27', '2023-06-04 18:36:27', NULL),
(32, 'b36da77c-bb85-454f-9424-65d6676803a4', 3, 'Ishmael Ingram', '235', 'Lydia Rowe', '55', 'Brennan Mcgee', NULL, NULL, 1, 39, NULL, '2023-06-04 18:38:41', '2023-06-04 18:38:41', NULL),
(33, '594928be-cc70-45c8-abff-a81093bca477', 4, 'Jakeem Phelps', '876', 'Lester Mayo', '28', 'Allegra Klein', NULL, NULL, 1, 39, NULL, '2023-06-07 18:42:22', '2023-06-07 18:42:22', NULL),
(34, '81527249-bb04-4cfe-8335-894e8e06d16f', 1, 'Sopoline Velasquez', '998', 'Tanek Morrison', '11', 'Galvin Morales', NULL, NULL, 1, 39, NULL, '2023-06-07 23:20:59', '2023-06-07 23:20:59', NULL),
(35, 'a3040abd-b2a3-48d9-a86a-86963989cba7', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 39, NULL, '2023-06-10 19:01:54', '2023-06-10 19:01:54', NULL),
(36, 'a1e2c451-b09c-47c9-abd6-ff563abadde8', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 39, NULL, '2023-06-10 21:12:23', '2023-06-10 21:12:23', NULL),
(37, '6c161f6f-e885-4fef-9edb-7e609cc0c19c', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 39, NULL, '2023-06-10 21:19:39', '2023-06-10 21:19:39', NULL),
(38, '15740319-11f5-40f5-974b-42745e50399a', 5, 'Anastasia Roach', '284', 'Dale Frye', '477', 'Brendan Neal', NULL, NULL, 1, 39, NULL, '2023-06-10 21:44:35', '2023-06-10 21:44:35', NULL),
(39, '4c2d2928-d1b4-4115-a7ba-c4164bf08c0f', 6, 'Hedda Hancock', '30', 'Eden Henderson', '332', 'Xanthus Zimmerman', NULL, NULL, 1, 39, NULL, '2023-06-12 00:09:38', '2023-06-12 00:09:38', NULL),
(40, '4b84e754-5c9e-40ca-b4d8-58ffa07ca8cc', 7, 'Nyssa Barker', '221', 'Imelda Burt', '802', 'Shay Pratt', NULL, NULL, 1, 39, NULL, '2023-06-12 00:11:05', '2023-06-12 00:11:05', NULL),
(41, 'cb79d8c8-2a62-4ca3-b854-b018207be3af', 8, 'Xander Williams', '651', 'Briar Rojas', '774', 'Gil Boyd', NULL, NULL, 1, 39, NULL, '2023-06-12 00:28:19', '2023-06-12 00:28:19', NULL),
(42, 'a6afd1b0-2e8d-4b96-8ff4-86b1626ed910', 9, 'Asher Andrews', '626', 'Ann Blair', '42', 'Jerome Harding', NULL, NULL, 1, 39, NULL, '2023-06-12 00:29:38', '2023-06-12 00:29:38', NULL),
(43, 'c9c59022-a138-4fdf-a957-139aff743078', 10, 'Maite Ferguson', '591', 'Samantha Montgomery', '657', 'Roary Wilkinson', NULL, NULL, 1, 39, NULL, '2023-06-12 00:46:58', '2023-06-12 00:46:58', NULL),
(44, '061cb5e0-fb26-47a0-9bdc-daa12a76ef1b', 11, 'Lilah Mueller', '550', 'Deanna Holland', '794', 'Bernard Williams', NULL, NULL, 1, 39, NULL, '2023-06-12 01:02:35', '2023-06-12 01:02:35', NULL),
(45, 'c613fff8-f02e-4f63-afb9-5b9a2d12a9f3', 1, 'Baxter Stafford', '297', 'Fallon Briggs', '38', 'September Downs', NULL, NULL, 1, 39, NULL, '2023-06-12 17:11:48', '2023-06-12 17:11:48', NULL),
(46, '3a85c51a-5c1b-4aa8-8184-10ebd73ec382', 2, 'Phelan Chase', '755', 'Davis Jacobson', '665', 'Barry Sullivan', NULL, NULL, 1, 39, NULL, '2023-06-12 18:52:30', '2023-06-12 18:52:30', NULL),
(47, '92f1129d-972d-4577-8122-2bd209a19210', 3, 'Natalie Carlson', '211', 'Patricia Munoz', '758', 'Irma Mosley', NULL, NULL, 1, 39, NULL, '2023-06-15 00:45:57', '2023-06-15 00:45:57', NULL),
(48, '27ce73f3-081f-4d38-a310-2fc06effa4ce', 1, 'Hayley Reilly', '59', 'Kendall Mccoy', '157', 'Geoffrey Black', NULL, NULL, 1, 39, NULL, '2023-06-17 23:11:10', '2023-06-17 23:11:10', NULL),
(49, '34926ba8-3701-492d-90a5-503a0f6397c1', 2, 'TaShya Boone', '656', 'Dora Walker', '130', 'George Sears', NULL, NULL, 1, 39, NULL, '2023-06-19 21:42:49', '2023-06-19 21:42:49', NULL),
(50, 'bf04b1f4-213c-4945-bee7-ad97d5393758', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 39, NULL, '2023-07-04 23:36:01', '2023-07-04 23:36:01', NULL),
(51, '1b2fccea-d744-4d4b-a803-9bb96e332db9', 4, 'Anastasia Miller', '2', 'Skyler Cervantes', '57', 'Nero Durham', NULL, NULL, 1, 39, NULL, '2023-07-15 22:47:51', '2023-07-15 22:47:51', NULL),
(52, 'bc613ebb-2f60-4ae8-a7a4-93386a38d88b', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 39, NULL, '2023-09-06 23:15:20', '2023-09-06 23:15:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `candidate_education`
--

CREATE TABLE `candidate_education` (
  `id` bigint UNSIGNED NOT NULL,
  `candidate_id` bigint UNSIGNED NOT NULL,
  `degree` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `university` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cgpa` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8mb4_unicode_ci,
  `sequence` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_information`
--

CREATE TABLE `candidate_information` (
  `id` bigint UNSIGNED NOT NULL,
  `candidate_rand_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alternative_phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `present_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permanent_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `picture` text COLLATE utf8mb4_unicode_ci,
  `ssn` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` int DEFAULT NULL,
  `state` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_interviews`
--

CREATE TABLE `candidate_interviews` (
  `id` bigint UNSIGNED NOT NULL,
  `candidate_id` bigint UNSIGNED NOT NULL,
  `interviewer` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position_id` bigint UNSIGNED NOT NULL,
  `interview_date` date NOT NULL,
  `interview_marks` double NOT NULL,
  `written_marks` double DEFAULT NULL,
  `mcq_marks` double DEFAULT NULL,
  `total_marks` double DEFAULT NULL,
  `recommandation` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `selection` tinyint(1) NOT NULL DEFAULT '0',
  `details` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_selections`
--

CREATE TABLE `candidate_selections` (
  `id` bigint UNSIGNED NOT NULL,
  `candidate_id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `position_id` bigint UNSIGNED NOT NULL,
  `selection_terms` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_shortlists`
--

CREATE TABLE `candidate_shortlists` (
  `id` bigint UNSIGNED NOT NULL,
  `candidate_id` bigint UNSIGNED NOT NULL,
  `position_id` bigint UNSIGNED NOT NULL,
  `shortlist_date` date NOT NULL,
  `interview_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_work_experiences`
--

CREATE TABLE `candidate_work_experiences` (
  `id` bigint UNSIGNED NOT NULL,
  `candidate_id` bigint UNSIGNED NOT NULL,
  `company_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `working_period` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duties` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supervisor` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sequence` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `certificate_types`
--

CREATE TABLE `certificate_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `certificate_types`
--

INSERT INTO `certificate_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(7, 'SSC', '2023-04-15 19:05:42', '2023-04-15 19:05:42'),
(8, 'Single Domain SSL Certificates', '2023-04-17 16:28:46', '2023-04-17 16:28:46');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `uuid`, `country_name`, `country_code`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '69ba88c8-1e27-4d01-b974-a320dba6f4e3', 'Afghanistan', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(2, '5d209625-6a42-42c5-9c1f-86d665d7d486', 'Albania', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(3, 'f9cf80d6-ec65-402d-92d2-e4c489d98baf', 'Algeria', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(4, '9a9f86f8-4c90-4ba8-96c5-a2922aa3bda7', 'Andorra', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(5, '167dca75-d5b0-4a65-b2e4-4e9a97d1d003', 'Angola', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(6, '70467888-a1f5-45f2-9cfd-273ffcd1ca65', 'Antigua and Barbuda', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(7, '0204f728-76fd-4e76-a2e7-cba8d40b8ad2', 'Argentina', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(8, '79e31d5e-fac8-4e41-9423-7afb2afc5ff5', 'Armenia', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(9, '73d2516c-851e-415a-aa39-13653a449381', 'Australia', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(10, 'c6dbc2f8-c3be-49f6-a4a6-ba8e4da52696', 'Austria', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(11, 'aff9b7d8-7095-4af0-b27f-fae694b02d6a', 'Azerbaijan', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(12, '290c7fd4-16d4-4fa3-85d4-4cfa2b0a112e', 'Bahamas', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(13, 'c4275bd7-6947-452e-9c16-22694d392a72', 'Bahrain', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(14, '8ba1dbce-dd1e-49ed-9626-6833e8b48ebd', 'Bangladesh', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(15, '9d529e76-2ab8-484b-8d1d-51fe9cb98cbf', 'Barbados', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(16, 'ca3a0043-dfb5-4f53-b0a0-22ec3ce523cc', 'Belarus', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(17, '76ae0cee-8e6d-4d88-835f-641f93fa5127', 'Belgium', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(18, 'f351ebf5-33b1-4fdd-9521-d183f94ed75a', 'Belize', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(19, '23659e6d-9129-4f62-ba50-83f91b34ea13', 'Benin', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(20, '626f3a17-9481-412a-9791-7bdd368da5da', 'Bhutan', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(21, 'c9e4d0a3-e4ef-41ad-9036-36c7216c4365', 'Bolivia', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(22, '2ec76e61-66e1-455b-b88d-169e4347846a', 'Bosnia and Herzegovina', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(23, 'e021f3ea-adb4-4f1c-ba0a-5dac21aa7cca', 'Botswana', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(24, '9eb64a7f-3911-4291-aa9b-1b8801777f48', 'Brazil', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(25, 'cc1588d2-7fc1-4fc6-8b97-1c82c926112d', 'Brunei', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(26, '8af70525-2cff-4812-b7a9-9d1e9fa497b3', 'Bulgaria', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(27, '21b62226-e621-47fd-b4d9-15d289ab29db', 'Burkina Faso', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(28, 'a276a435-9273-423d-ae1a-4315cd741746', 'Burundi', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(29, 'fdfaf525-f8f3-4187-bb2f-3dbc75ae46b6', 'Cambodia', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(30, 'a127d13e-099b-42ec-acfe-61a29357b17f', 'Cameroon', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(31, 'ad3402e5-cbe7-4567-b92a-86cae3ef0dd4', 'Canada', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(32, 'd3fcbbaa-44c7-47b3-b0b6-11a18938a123', 'Cape Verde', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(33, 'e180ae64-e393-43b3-a07e-7dd687e74fc6', 'Central African Republic', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(34, '38eb4c30-08fa-4cd7-ba72-928c3243f606', 'Chad', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(35, '61ef339e-7e27-44ba-8db2-f884b30b6457', 'Chile', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(36, '58187c8f-2158-4efb-9ea9-79cfb4907904', 'China', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(37, '4e8c38af-6c44-4ef6-b692-c8cad6cc1bd9', 'Colombi', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(38, '436727aa-81a9-471c-ab95-1ab0307030cd', 'Comoros', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(39, '19741b29-e9e7-41c7-bd7d-8711e8af5c2d', 'Congo (Brazzaville)', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(40, '624e2f54-5ae1-494a-ad47-752bf02f018c', 'Congo', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(41, 'bbbea94e-0d2c-4b6e-be53-49434f0a2bbc', 'Costa Rica', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(42, 'd234ae52-5a7a-4522-8f1c-f96c4c0e06a1', 'Cote d\'Ivoire', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(43, 'd7fa29b6-c87d-458d-bbbe-d76f4e2086ef', 'Croatia', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(44, 'f3e7b403-d519-4009-afd8-928cfbea8c50', 'Cuba', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(45, 'a02d55f0-2f38-4e49-9382-b52362d98f71', 'Cyprus', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(46, '8e688659-f542-411c-bca9-a264fb172a69', 'Czech Republic', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(47, 'a10e88b4-fadb-49d7-b68e-190a506c0d57', 'Denmark', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(48, '1992cf51-abbb-453a-b2cb-8a87e845a6cd', 'Djibouti', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(49, 'ad7b8d7e-ab45-483f-8476-e41a62dfd943', 'Dominica', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(50, 'e17575e1-d3e1-46be-ad5a-4835da277e1c', 'Dominican Republic', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(51, 'c5da3722-3613-4f5b-b540-7f6922167225', 'East Timor (Timor Timur)', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(52, 'ca3948f8-0b8f-41db-bde9-7bfef443487b', 'Ecuador', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(53, '29d76ba7-24da-4038-bdf6-e50d293c458f', 'Egypt', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(54, 'd63cba04-b92a-4f5a-a314-8af9450378e6', 'El Salvador', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(55, 'c208d2e5-1cf1-4a2e-ae77-1fd4dcc6d797', 'Equatorial Guinea', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(56, 'f7c80da5-8664-44b9-bef0-fe5b41ee0c02', 'Eritrea', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(57, '3cfa0d34-17ac-4c33-922d-dce707d1281f', 'Estonia', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(58, '99592ec1-0dc0-4db8-a220-5ffe5a0bbd01', 'Ethiopia', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(59, '6a078b3e-a62b-436c-81aa-bfd67afed621', 'Fiji', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(60, 'fcb24df0-fbf1-4312-9c94-39c079024e1f', 'Finland', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(61, '5ce08abb-4ddf-439f-b972-99bf4684d5c0', 'France', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(62, 'a6a8dcdc-dfbe-4845-9dd7-039f39fc1dfa', 'Gabon', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(63, '81281a71-bd9e-456e-aefb-8b6e0eda838a', 'Gambia, The', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(64, '0b97cfa6-701d-4d1f-aad3-8b6055cdea0a', 'Georgia', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(65, 'f6d926fc-2f6f-4fd1-af86-98caee7a624a', 'Germany', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(66, 'c0b38737-fa49-4b55-a2d9-9eae19f8658f', 'Ghana', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(67, '6b111be7-e916-4ae5-8777-2636c6dd3047', 'Greece', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(68, 'ef26d57f-5272-4963-9134-b78799a0304d', 'Grenada', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(69, 'dbc8dfed-959b-4818-a83c-9298112937d6', 'Guatemala', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(70, '23bf65ee-748b-46f1-8a40-fd5e21fcea2e', 'Guinea', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(71, '5d34192f-8831-4f54-bcb2-ddebae1e282b', 'Guinea-Bissau', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(72, '170af803-dfc2-4261-a729-a0f87a433b2e', 'Guyana', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(73, 'a316bc27-c358-4739-9ee1-fae6c0f2f644', 'Haiti', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(74, '9efdd469-65cb-43fd-af38-3b7a0e382d9f', 'Honduras', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(75, '6879a977-ade9-4c61-be54-1b17fa6303f2', 'Hungary', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(76, '015301a5-9d47-488f-95b5-319d9b17d501', 'Iceland', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(77, '034fc8ca-3e0c-4676-91a3-5413687a69f8', 'India', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(78, 'ee83350d-f3a3-4062-884b-12327f1fa977', 'Indonesia', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(79, '8760b596-4c9a-4bb1-bb61-6a8a8e1545ab', 'Iran', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(80, '5ccbbd8c-5423-4391-b9b0-416f31ce0fb5', 'Iraq', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(81, '837eb390-3804-47b7-8e3a-3de6165be838', 'Ireland', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(82, 'cc245dc6-a7f3-4515-afb6-01c1e77df928', 'Israel', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(83, 'ed1b96f4-df91-492c-ba1f-9e2dd05da4ea', 'Italy', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(84, '12444673-8593-4d01-8a93-097ee6a268eb', 'Jamaica', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(85, '0e6eb9f1-2c5f-479d-84be-5547e9c1c15d', 'Japan', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(86, '0ebd9851-d86d-41c4-b46f-e0a92a0787f5', 'Jordan', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(87, '04ed7be6-a2b4-48b7-9499-5b8b802273e5', 'Kazakhstan', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(88, '26ba4592-8e0c-4ce4-b169-18f585962c16', 'Kenya', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(89, 'f9df60f7-1ee3-4795-909e-ae6167d76853', 'Kiribati', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(90, '84a26fe8-d853-443d-89fd-7b9a62561ba5', 'Korea, North', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(91, 'd44a207e-f1b9-407a-92ff-f1aaee557cde', 'Korea, South', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(92, '102df1a0-2618-4125-a221-843601dfd4e0', 'Kuwait', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(93, 'a47cc161-949b-4a1d-8a6c-0b571f089df0', 'Kyrgyzstan', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(94, '0a1ecad4-87b6-4601-8ef0-2de8a543872a', 'Laos', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(95, '087abadf-bf4a-410c-8bd5-22126f7e31e7', 'Latvia', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(96, '6a495173-f349-48ac-9bf1-a8a3b22cd308', 'Lebanon', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(97, 'dbb1ad66-8c11-4743-a436-11728329c341', 'Lesotho', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(98, 'a2abeafc-5167-4199-8edd-3c31930053ad', 'Liberia', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(99, '77d741a0-f98d-43c0-88fd-24be11a06360', 'Libya', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(100, '35e54b30-50c7-4082-a2cf-541064f7451c', 'Liechtenstein', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(101, 'cc35b8db-d745-4800-997f-f6ada2447ef5', 'Lithuania', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(102, '2e572f8a-a931-4318-ad92-7347a7d41e07', 'Luxembourg', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(103, '76ccf9df-1a99-44f2-b075-85df9c4a4ba7', 'Macedonia', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(104, 'cec8c2db-ca0a-49f5-b6bc-31f59b892d05', 'Madagascar', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(105, '2a66b624-12d4-4c35-adc3-be890cba8e14', 'Malawi', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(106, '5ae73cb7-b585-4237-be06-a110c9fbc658', 'Malaysia', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(107, 'ee0890fc-33e9-400d-879c-0382b4ebf94e', 'Maldives', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(108, '44c83e17-3334-4973-9b06-4b8424e32168', 'Mali', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(109, 'a0d47b91-c6e4-4ac6-89c1-bd7a53ec0635', 'Malta', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(110, '1b1eba8c-ecac-499f-a232-3d9df141daec', 'Marshall Islands', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(111, '9a4e6e33-6b41-4cd8-8a4f-8447246774ea', 'Mauritania', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(112, 'f59074d1-da9f-42c8-ac69-b9db4c844af6', 'Mauritius', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(113, 'cdb2f569-987d-4e1b-a97d-f421d8db669c', 'Mexico', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(114, 'ffc4aa68-ccf4-4316-940b-fa7779679ca8', 'Micronesia', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(115, '535cd678-873d-42a8-936b-4789da94a605', 'Moldova', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(116, '5f7ddcab-e2c7-4b37-b83e-831a019f1ef9', 'Monaco', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(117, 'a0e13355-13b9-4a29-9a89-5c51c3e1286b', 'Mongolia', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(118, 'aea8ff1e-2110-4ac1-9565-6cffea4a2d36', 'Morocco', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(119, 'dc15a1a1-113f-499c-ac31-46174373a016', 'Mozambique', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(120, '70eaec3c-934c-42db-9c2e-046170716b46', 'Myanmar', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(121, 'e40cb465-2525-4980-8f09-af3d89f36be0', 'Namibia', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(122, '9eadf3a6-fde7-4ccc-9e3a-a5d9e56bd61f', 'Nauru', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(123, '5b4a0557-4f77-4d09-a372-4fd3f6e34b49', 'Nepal', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(124, 'bda51e35-8fc5-4ad1-aab8-428237eedb36', 'Netherlands', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(125, '5534f622-fbb9-456e-b298-3ff73ffa78cf', 'New Zealand', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(126, '9968d001-ac3f-4b14-aff6-ce2b96c031cf', 'Nicaragua', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(127, '6638cb23-c472-4d7f-be77-d9d046328c9e', 'Niger', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(128, '0152752f-7a32-4215-a9a7-8ee7ac8f3958', 'Nigeria', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(129, 'a290a3f6-4485-4f49-ae2d-77d69aeadd7d', 'Norway', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(130, '523ac8e3-742e-4556-8aa8-59f69145193c', 'Oman', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(131, '6f122b10-1163-4b53-97e6-76b6e183f62c', 'Pakistan', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(132, '029764f9-69b5-490c-815e-4323817caf31', 'Palau', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(133, '54c517fd-9785-4b1d-9cb6-48cfa801305b', 'Panama', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(134, 'd85f6e94-26e7-4405-98e7-fd1216120d52', 'Papua New Guinea', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(135, 'd3ece26c-2e25-4f2f-b57a-86496e259acd', 'Paraguay', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(136, '6f91303b-36a8-4f14-98b2-20f24dca2afe', 'Peru', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(137, 'ba07cf9e-34e9-40ee-91d6-fceedfe03a9b', 'Philippines', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(138, 'e7532806-51ce-4cb1-9e76-80bc78f1c7ae', 'Poland', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(139, 'a5c3513d-0f1b-41df-964e-442a3533c001', 'Portugal', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(140, 'c133d526-3eb8-48f5-893a-2576e50c012b', 'Qatar', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(141, '7093f409-af1f-49bf-90e6-e66dca589e96', 'Romania', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(142, '3e3d30e2-f51a-4d12-a290-c52793105a9d', 'Russia', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(143, '746f3f08-1558-47da-bab2-021e8ece795e', 'Rwanda', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(144, '80ab2a9d-0d08-44ae-81ca-25c08823972e', 'Saint Kitts and Nevis', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(145, 'dd416aad-0526-4ed5-bbb9-8bcab2e204bb', 'Saint Lucia', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(146, '0b18800a-b950-4006-8f98-d772e2016f03', 'Saint Vincent', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(147, '2a4e5d49-b066-49df-87eb-8c156c47355a', 'Samoa', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(148, '08054fb9-e992-4fd5-a0ba-226c7125bf77', 'San Marino', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(149, 'd641fb68-5fc4-4fc0-83c7-6c878c4473aa', 'Sao Tome and Principe', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(150, '7732e9ca-008d-4a88-9e75-12233f6c83cb', 'Saudi Arabia', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(151, '6749069d-de34-4838-92e7-251681d71b9d', 'Senegal', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(152, 'f8f90a0e-6b89-49c6-9e10-28da766fc9b1', 'Serbia and Montenegro', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(153, 'ed64dc48-b6d0-4e39-97d8-b5a944781704', 'Seychelles', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(154, '5ba4fc4c-4047-499c-8a22-378d3ca99497', 'Sierra Leone', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(155, 'b5f08287-71ad-473f-99ed-03b80a1ae720', 'Singapore', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(156, '58711e44-4850-4b8e-8133-94514c7c8e43', 'Slovakia', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(157, 'c15fd69c-2bb0-40ec-99d9-629cb6074768', 'Slovenia', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(158, 'de86c278-f0b3-4d45-84ab-9cec9d9be209', 'Solomon Islands', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(159, '8fe4aa15-4e39-4ac1-a839-a30ff047c184', 'Somalia', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(160, 'de3f2f9d-ad79-4556-afd7-b3bedc7c1baf', 'South Africa', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(161, '34403eaa-7d29-45ed-acd9-8ead84bf7025', 'Spain', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(162, 'ac704b44-5da5-4772-8452-ee09bd31a5db', 'Sri Lanka', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(163, 'dd7c6b18-b739-491f-a86f-3f65c853354d', 'Sudan', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(164, '974fe448-68cc-4f77-ac66-22f54dff4f25', 'Suriname', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(165, '23c902e3-215a-427c-86a5-478aeaa84ebb', 'Swaziland', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(166, 'dd9d4254-a0e3-4b91-9dba-b72684c9622c', 'Sweden', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(167, 'c3d374b9-c11d-4c65-b64a-38a9f6b6b082', 'Switzerland', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(168, '0563be1b-deba-4a16-8b30-ed38147e1ada', 'Syria', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(169, '0304e100-e382-43c2-9a48-f52a7ac39610', 'Taiwan', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(170, '9061369f-a83a-4b5b-ab63-68527a605b3c', 'Tajikistan', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(171, 'fa2392dc-b990-4e00-a665-af3babd45664', 'Tanzania', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(172, 'c8045da7-31ee-4486-b87b-8404ec436019', 'Thailand', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(173, '979091fc-012a-433c-ab6b-80b43a3d1a03', 'Togo', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(174, '6f0465cd-5e60-4412-9b8f-c46da777d261', 'Tonga', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(175, '333c8d05-18fd-4044-82b2-dfd6d9bf5a67', 'Trinidad and Tobago', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(176, '8b2cb979-621a-44ea-9591-defffeeaa378', 'Tunisia', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(177, '7f15880e-098b-44c2-92dd-94636341b794', 'Turkey', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(178, 'b32df125-f78d-4e59-9671-6a81c66d6506', 'Turkmenistan', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(179, '11b7cbe7-d50e-4ca8-87d0-d76455dbc5ce', 'Tuvalu', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(180, '95ec8dd5-65b7-411b-af0d-412653300d5b', 'Uganda', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(181, 'd773995e-06e8-482b-a6ad-006d0b9b0da2', 'Ukraine', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(182, '1261dddc-fbfd-4226-aae5-806fb1003879', 'United Arab Emirates', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(183, 'db728883-b40e-452a-a11a-d02865a2ac71', 'United Kingdom', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(184, '26716b8c-3218-4ba1-b84f-06032f5d8536', 'United States', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(185, '3cb3ccec-a942-4277-b560-cda0a90523f5', 'Uruguay', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(186, '5080a918-4985-4e85-a8f4-d26c5b9c5f9b', 'Uzbekistan', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(187, 'ed2f011c-8c60-4a9c-8854-6b191e8f35c2', 'Vanuatu', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(188, '59b8543e-d35c-452c-8ad4-a418bbb88ad8', 'Vatican City', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(189, '17cb8aa6-4f48-4b86-9edc-c9740d60d4ff', 'Venezuela', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(190, 'a6f56eec-e0ee-472b-8193-07d1d232a7cc', 'Vietnam', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(191, 'e245ca03-f33c-4b1b-8546-ac6e7ede7871', 'Yemen', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(192, '11d57872-f664-46db-93ba-1f29387b4571', 'Zambia', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL),
(193, 'f0c0a0b0-26e1-4bef-906f-acee3e9aeae3', 'Zimbabwe', NULL, 1, NULL, NULL, '2022-12-05 20:59:04', '2022-12-05 20:59:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` bigint UNSIGNED NOT NULL,
  `country_id` int NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `country_id`, `title`, `symbol`, `status`, `created_at`, `updated_at`) VALUES
(1, 14, 'Taka', '৳', 1, '2024-05-12 22:35:44', '2024-05-12 22:35:44');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `uuid`, `department_name`, `parent_id`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '37a152ab-ed56-45d3-95bb-a92c03b0d2a9', 'Software Development', NULL, 1, 39, NULL, '2023-04-10 21:00:37', '2023-04-10 21:00:37', NULL),
(2, 'b405e804-7abb-47ae-99a6-0af70289d1d9', 'Electrical', NULL, 1, 39, NULL, '2023-04-10 21:00:47', '2023-04-10 21:00:47', NULL),
(3, '7bfed077-805c-4a4b-a62a-4ebf41b5b0ed', 'Inventory', NULL, 1, 39, NULL, '2023-04-10 21:00:56', '2023-04-10 21:00:56', NULL),
(4, 'f6c34a4d-8f36-4292-89f7-218b6cf4cfef', 'Accounts', NULL, 1, 39, NULL, '2023-04-10 21:01:10', '2023-04-10 21:01:10', NULL),
(5, 'a3d2a120-18fc-47c9-8e27-349d1f7d2f23', 'Dhaka', 2, 1, 39, 39, '2023-05-16 06:09:55', '2023-06-10 22:21:21', NULL),
(6, 'd51f6736-f2bd-4232-9412-9909b3ec0aca', 'fgdf', NULL, 1, 39, NULL, '2023-09-06 23:13:34', '2023-09-06 23:13:38', '2023-09-06 23:13:38'),
(7, '7f85ed87-d206-4ff6-ac62-83de9e44eadd', 'ert', 3, 1, 39, NULL, '2023-09-06 23:13:46', '2023-09-06 23:13:49', '2023-09-06 23:13:49'),
(8, '435f30ec-42b1-4198-9fe1-87aefe5c82cf', 'Bree Gill', NULL, 1, 39, NULL, '2024-04-16 17:02:00', '2024-04-16 17:02:00', NULL),
(9, '1e793d36-3b00-413d-9a62-f39194495a1d', 'Angelica Goff', 4, 0, 39, NULL, '2024-04-16 17:02:15', '2024-04-16 17:02:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `doc_expired_settings`
--

CREATE TABLE `doc_expired_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `primary_expiration_alert` int NOT NULL COMMENT 'Primary Expiration Alert in Days',
  `secondary_expiration_alert` int NOT NULL COMMENT 'Secondary Expiration Alert in Days',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doc_expired_settings`
--

INSERT INTO `doc_expired_settings` (`id`, `primary_expiration_alert`, `secondary_expiration_alert`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2024-04-22 04:28:41', '2024-04-22 04:28:41');

-- --------------------------------------------------------

--
-- Table structure for table `duty_types`
--

CREATE TABLE `duty_types` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `duty_types`
--

INSERT INTO `duty_types` (`id`, `uuid`, `type_name`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '1eb94bcc-7b76-49aa-8b69-36c15a9539a2', 'Full Time', 1, NULL, NULL, NULL, NULL, NULL),
(2, '0a884c35-98ec-4b16-ad3d-485fee89984c', 'Part Time', 1, NULL, NULL, NULL, NULL, NULL),
(3, '70ec85c7-5e14-4d2e-95e7-55ee8f3f30f1', 'Contractual', 1, NULL, NULL, NULL, NULL, NULL),
(4, '2e9b7ddc-f9b8-4c16-a20b-0d0b236566de', 'Other', 1, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `email_configs`
--

CREATE TABLE `email_configs` (
  `id` bigint UNSIGNED NOT NULL,
  `protocol` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `smtp_host` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `smtp_port` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `smtp_user` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `smtp_pass` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mailtype` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isinvoice` tinyint NOT NULL,
  `isservice` tinyint NOT NULL,
  `isquotation` tinyint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_configs`
--

INSERT INTO `email_configs` (`id`, `protocol`, `smtp_host`, `smtp_port`, `smtp_user`, `smtp_pass`, `mailtype`, `isinvoice`, `isservice`, `isquotation`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'smtp', 'sandbox.smtp.mailtrap.io', '2525', '445a7ed487c788', 'c736ad74e7107e', 'html', 1, 1, 1, '2023-05-10 23:51:47', '2023-05-10 23:51:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `employee_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `card_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `middle_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_image` longtext COLLATE utf8mb4_unicode_ci,
  `alternate_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_group_id` int DEFAULT NULL,
  `present_address` text COLLATE utf8mb4_unicode_ci,
  `permanent_address` text COLLATE utf8mb4_unicode_ci,
  `degree_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `university_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cgp` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passing_year` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `working_period` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duties` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supervisor` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT '0',
  `maiden_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip` int DEFAULT NULL,
  `citizenship` int DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `promotion_date` date DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `termination_date` date DEFAULT NULL,
  `termination_reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `national_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `identification_attachment` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nationality` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voluntary_termination` int DEFAULT NULL,
  `rehire_date` date DEFAULT NULL,
  `rate` double DEFAULT NULL,
  `pay_frequency_id` bigint UNSIGNED DEFAULT NULL,
  `duty_type_id` bigint UNSIGNED DEFAULT NULL,
  `gender_id` bigint UNSIGNED DEFAULT NULL,
  `marital_status_id` bigint UNSIGNED DEFAULT NULL,
  `attendance_time_id` int UNSIGNED DEFAULT NULL,
  `employee_type_id` bigint UNSIGNED DEFAULT NULL,
  `contract_start_date` date DEFAULT NULL COMMENT 'if duty type is contractual',
  `contract_end_date` date DEFAULT NULL COMMENT 'if duty type is contractual',
  `position_id` bigint UNSIGNED DEFAULT NULL,
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `sub_department_id` bigint UNSIGNED DEFAULT NULL,
  `branch_id` bigint UNSIGNED DEFAULT NULL,
  `employee_code` bigint UNSIGNED DEFAULT NULL,
  `employee_device_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `highest_educational_qualification` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pay_frequency_text` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hourly_rate` double(8,2) DEFAULT NULL,
  `hourly_rate2` double(8,2) DEFAULT NULL,
  `home_department` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_text` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class_code_desc` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class_acc_date` date DEFAULT NULL,
  `class_status` tinyint(1) NOT NULL DEFAULT '1',
  `is_supervisor` tinyint(1) NOT NULL DEFAULT '0',
  `supervisor_id` bigint UNSIGNED DEFAULT NULL,
  `supervisor_report` text COLLATE utf8mb4_unicode_ci,
  `date_of_birth` date DEFAULT NULL,
  `ethnic_group` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eeo_class_gp` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ssn` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_in_city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `live_in_state` int DEFAULT NULL,
  `home_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `home_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cell_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact_person` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact_relationship` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact_country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact_state` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact_city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact_post_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact_address` longtext COLLATE utf8mb4_unicode_ci,
  `present_address_country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `present_address_state` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `present_address_city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `present_address_post_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `present_address_address` longtext COLLATE utf8mb4_unicode_ci,
  `permanent_address_country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permanent_address_state` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permanent_address_city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permanent_address_post_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permanent_address_address` longtext COLLATE utf8mb4_unicode_ci,
  `skill_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skill_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `certificate_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `certificate_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skill_attachment` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_home_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_work_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alter_emergency_contact` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alter_emergency_home_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alter_emergency_work_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sos` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `monthly_work_hours` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_grade` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `religion` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_of_kids` int DEFAULT NULL,
  `blood_group` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `health_condition` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_disable` tinyint(1) DEFAULT '0',
  `disabilities_desc` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_img_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_img_location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `national_id_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iqama_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passport_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driving_license_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_permit` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `is_left` tinyint(1) DEFAULT '0',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_academic_infos`
--

CREATE TABLE `employee_academic_infos` (
  `id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `exam_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `institute_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `result` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `graduation_year` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `academic_attachment` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_allowence_deductions`
--

CREATE TABLE `employee_allowence_deductions` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `setup_rule_id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `amount` double DEFAULT NULL,
  `percentage` double DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_docs`
--

CREATE TABLE `employee_docs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `doc_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `file_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expiry_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issue_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_returned` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_files`
--

CREATE TABLE `employee_files` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `tin_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gross_salary` double NOT NULL,
  `basic` double NOT NULL,
  `transport` double DEFAULT NULL,
  `house_rent` double DEFAULT NULL,
  `medical` double DEFAULT NULL,
  `other_allowance` double DEFAULT NULL,
  `state_income_tax` double DEFAULT NULL,
  `soc_sec_npf_tax` double DEFAULT NULL,
  `loan_deduct` double DEFAULT NULL,
  `salary_advance` double DEFAULT NULL,
  `lwp` double DEFAULT NULL,
  `pf` double DEFAULT NULL,
  `stamp` double DEFAULT NULL,
  `medical_benefit` double DEFAULT NULL,
  `family_benefit` double DEFAULT NULL,
  `transportation_benefit` double DEFAULT NULL,
  `other_benefit` double DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_other_docs`
--

CREATE TABLE `employee_other_docs` (
  `id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `document_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_attachment` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_expire` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_notify` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_performances`
--

CREATE TABLE `employee_performances` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_teacher` tinyint(1) NOT NULL DEFAULT '0',
  `class_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `performance_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position_of_supervisor` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `review_period` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `note_by` bigint UNSIGNED DEFAULT NULL,
  `score` int DEFAULT NULL,
  `total_score` double DEFAULT NULL,
  `number_of_star` int DEFAULT NULL,
  `employee_comments` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_performance_criterias`
--

CREATE TABLE `employee_performance_criterias` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `performance_type_id` bigint UNSIGNED NOT NULL,
  `evaluation_type_id` bigint UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_performance_criterias`
--

INSERT INTO `employee_performance_criterias` (`id`, `uuid`, `performance_type_id`, `evaluation_type_id`, `title`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'd13fd74d-4db4-49d4-85b6-9f0f1fc24a74', 1, 1, 'Demonstrated Knowledge of duties & Quality of Work', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'ca2b1747-4faa-43b9-a815-9893ba1ec081', 1, 1, 'Timeliness of Delivery', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'b326f848-9815-4be8-a74b-d87d10dbf103', 1, 1, 'Impact of Achievement', NULL, NULL, NULL, NULL, NULL, NULL),
(4, '53c277ba-c1ee-4961-9bd2-5f6486b7f834', 1, 1, 'Overall Achievement of Goals/Objectives', NULL, NULL, NULL, NULL, NULL, NULL),
(5, '04a79ead-1331-4e28-8818-a6c7ff389d98', 1, 1, 'Going beyond the call of Duty', NULL, NULL, NULL, NULL, NULL, NULL),
(6, '28ccb065-885b-433e-9687-220a7eac681b', 2, 1, 'Interpersonal skills & ability to work in a team environment', NULL, NULL, NULL, NULL, NULL, NULL),
(7, '2181b404-1bc3-46d2-b45c-02f579c7ac5f', 2, 1, 'Attendance and Punctuality', NULL, NULL, NULL, NULL, NULL, NULL),
(8, '0c786351-75fa-4635-9355-63b54c9ddfa7', 2, 1, 'Communication Skills', NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'b5702de9-dcb1-4839-9d38-e0be3c9830df', 2, 1, 'Contributing to IIHT Gambia’s mission', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_performance_development_plans`
--

CREATE TABLE `employee_performance_development_plans` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `performance_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recommend_areas` longtext COLLATE utf8mb4_unicode_ci,
  `expected_outcomes` longtext COLLATE utf8mb4_unicode_ci,
  `responsible_person` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_performance_evaluations`
--

CREATE TABLE `employee_performance_evaluations` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `score` int NOT NULL,
  `short_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `evaluation_type_id` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_performance_evaluations`
--

INSERT INTO `employee_performance_evaluations` (`id`, `uuid`, `title`, `score`, `short_code`, `evaluation_type_id`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '600a1ee2-6c84-43ed-89f9-24838c16066e', 'Poor', 0, 'P', 1, NULL, NULL, NULL, NULL, NULL),
(2, '89a3568a-cd4a-4c85-b776-ccdbbe52d45c', 'Need Improvement', 0, 'NI', 1, NULL, NULL, NULL, NULL, NULL),
(3, '39704632-4454-423a-8457-7ca0ff1be37d', 'Good', 0, 'G', 1, NULL, NULL, NULL, NULL, NULL),
(4, '15d1bedc-bd02-4301-b9d9-ff05666a33f3', 'Very Good', 0, 'VG', 1, NULL, NULL, NULL, NULL, NULL),
(5, 'dac59668-941e-46a8-83e3-bd1923dfbc01', 'Excellent', 0, 'E', 1, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_performance_evaluation_types`
--

CREATE TABLE `employee_performance_evaluation_types` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_no` longtext COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_performance_evaluation_types`
--

INSERT INTO `employee_performance_evaluation_types` (`id`, `uuid`, `type_name`, `type_no`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'd936d978-555e-4c1f-9f7b-4b521fcdff44', 'Number', '1', NULL, NULL, NULL, NULL, NULL),
(2, '1ae689c9-2b3f-4cb8-a2d1-637fb0da1366', 'Quality', '2', NULL, NULL, NULL, NULL, NULL),
(3, '6b8b464a-04cb-4bc1-8986-c73926d2495b', 'User Input', '3', NULL, NULL, NULL, NULL, NULL),
(4, '6edb83ad-e868-4aa7-8757-4f11ea2fb53a', 'Checkbox', '4', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_performance_key_goals`
--

CREATE TABLE `employee_performance_key_goals` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `performance_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key_goals` longtext COLLATE utf8mb4_unicode_ci,
  `completion_period` date DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_performance_types`
--

CREATE TABLE `employee_performance_types` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int DEFAULT NULL COMMENT '1=employee, 2=teacher',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_performance_types`
--

INSERT INTO `employee_performance_types` (`id`, `uuid`, `title`, `type`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'fc79c4f0-3028-497a-9589-aeaf82951ee9', 'Assessment of Goals / Objective set during the review period', 1, NULL, NULL, NULL, NULL, NULL),
(2, 'e119a2df-d950-430a-83a5-6b663941e51e', 'Assessment of other performance standards and indicators', 1, NULL, NULL, NULL, NULL, NULL),
(3, 'e75ca689-cf4d-43b5-842a-88c94caa10af', 'Quality Of Work', 1, NULL, NULL, NULL, NULL, NULL),
(4, '76a2ac37-0e40-4c91-a81a-f37617f8adf7', 'Lesson Content', 1, NULL, NULL, NULL, NULL, NULL),
(5, '40d10f0e-b3b4-4c5e-8abd-e4666bda5017', 'Punctuality', 1, NULL, NULL, NULL, NULL, NULL),
(6, 'cde6f5b9-6515-4242-b7c5-b224235b86a4', 'Behavior', 1, NULL, NULL, NULL, NULL, NULL),
(7, '75a92e46-4800-43f1-a536-0449719afd66', 'Extra Services', 1, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_performance_values`
--

CREATE TABLE `employee_performance_values` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `performance_id` bigint UNSIGNED NOT NULL,
  `performance_type_id` bigint UNSIGNED DEFAULT NULL,
  `performance_criteria_id` bigint UNSIGNED DEFAULT NULL,
  `emp_perform_eval` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `score` int DEFAULT NULL,
  `comments` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_salary_types`
--

CREATE TABLE `employee_salary_types` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `setup_rule_id` bigint UNSIGNED NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double DEFAULT NULL,
  `on_gross` tinyint(1) NOT NULL DEFAULT '0',
  `on_basic` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_types`
--

CREATE TABLE `employee_types` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_types`
--

INSERT INTO `employee_types` (`id`, `uuid`, `name`, `details`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '92849fa1-13cf-4233-b79f-8c5fa3f1a844', 'Intern', NULL, 1, NULL, NULL, NULL, NULL, NULL),
(2, '72e23d8f-880c-49a9-bac3-db0b20cad5c3', 'Contractual', NULL, 1, NULL, NULL, NULL, NULL, NULL),
(3, '4edea2a2-c71d-4d08-b71f-65b47ce2550c', 'Full Time', NULL, 1, NULL, NULL, NULL, NULL, NULL),
(4, '0c351cc7-fd2e-45f1-920f-fd8a844f9130', 'Remote', NULL, 1, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `financial_years`
--

CREATE TABLE `financial_years` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `financial_year` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `is_close` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `financial_years`
--

INSERT INTO `financial_years` (`id`, `uuid`, `financial_year`, `start_date`, `end_date`, `status`, `is_close`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'd28859ca-60f8-411b-bec4-32579dfb577d', '2022', '2022-01-01', '2022-12-31', 1, 0, 39, 39, '2023-06-09 22:11:41', '2023-06-10 00:09:12', '2023-06-10 00:09:12'),
(2, '6074299c-f3b3-4aef-b6e9-bf295ef7b28d', '2023', '2023-01-01', '2023-12-31', 1, 0, 39, 39, '2023-06-09 22:11:56', '2023-06-10 00:09:09', '2023-06-10 00:09:09');

-- --------------------------------------------------------

--
-- Table structure for table `genders`
--

CREATE TABLE `genders` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `genders`
--

INSERT INTO `genders` (`id`, `uuid`, `gender_name`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '8a2c7abf-465d-4255-b1e0-92abcc1f7375', 'Male', 1, 1, 1, '2023-03-18 06:13:03', '2023-03-18 06:13:03', NULL),
(2, '8a2c7abf-465d-4255-b1e0-92abcc1f7377', 'Female', 1, 1, 1, '2023-03-18 06:13:03', '2023-03-18 06:13:03', NULL),
(3, '8a2c7abf-465d-4255-b1e0-92abcc1f7375', 'Transgender', 1, 1, 1, '2023-03-18 06:13:03', '2023-03-18 06:13:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

CREATE TABLE `holidays` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `holiday_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_day` int NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `holidays`
--

INSERT INTO `holidays` (`id`, `uuid`, `holiday_name`, `start_date`, `end_date`, `total_day`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '67bd203c-c314-4d4b-a695-000075d62dbf', 'Eid Ul Fitar', '2023-05-09', '2023-05-11', 2, 39, 39, '2023-05-07 20:11:06', '2023-05-07 22:07:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lang`
--

CREATE TABLE `lang` (
  `id` int NOT NULL,
  `name` varchar(64) COLLATE utf8mb3_unicode_ci NOT NULL,
  `value` varchar(64) COLLATE utf8mb3_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `lang`
--

INSERT INTO `lang` (`id`, `name`, `value`) VALUES
(1, 'English', 'en'),
(2, 'Afar', 'aa'),
(3, 'Abkhazian', 'ab'),
(4, 'Afrikaans', 'af'),
(5, 'Amharic', 'am'),
(6, 'Arabic', 'ar'),
(7, 'Assamese', 'as'),
(8, 'Aymara', 'ay'),
(9, 'Azerbaijani', 'az'),
(10, 'Bashkir', 'ba'),
(11, 'Belarusian', 'be'),
(12, 'Bulgarian', 'bg'),
(13, 'Bihari', 'bh'),
(14, 'Bislama', 'bi'),
(15, 'Bengali/Bangla', 'bn'),
(16, 'Tibetan', 'bo'),
(17, 'Breton', 'br'),
(18, 'Catalan', 'ca'),
(19, 'Corsican', 'co'),
(20, 'Czech', 'cs'),
(21, 'Welsh', 'cy'),
(22, 'Danish', 'da'),
(23, 'German', 'de'),
(24, 'Bhutani', 'dz'),
(25, 'Greek', 'el'),
(26, 'Esperanto', 'eo'),
(27, 'Spanish', 'es'),
(28, 'Estonian', 'et'),
(29, 'Basque', 'eu'),
(30, 'Persian', 'fa'),
(31, 'Finnish', 'fi'),
(32, 'Fiji', 'fj'),
(33, 'Faeroese', 'fo'),
(34, 'French', 'fr'),
(35, 'Frisian', 'fy'),
(36, 'Irish', 'ga'),
(37, 'Scots/Gaelic', 'gd'),
(38, 'Galician', 'gl'),
(39, 'Guarani', 'gn'),
(40, 'Gujarati', 'gu'),
(41, 'Hausa', 'ha'),
(42, 'Hindi', 'hi'),
(43, 'Croatian', 'hr'),
(44, 'Hungarian', 'hu'),
(45, 'Armenian', 'hy'),
(46, 'Interlingua', 'ia'),
(47, 'Interlingue', 'ie'),
(48, 'Inupiak', 'ik'),
(49, 'Indonesian', 'in'),
(50, 'Icelandic', 'is'),
(51, 'Italian', 'it'),
(52, 'Hebrew', 'iw'),
(53, 'Japanese', 'ja'),
(54, 'Yiddish', 'ji'),
(55, 'Javanese', 'jw'),
(56, 'Georgian', 'ka'),
(57, 'Kazakh', 'kk'),
(58, 'Greenlandic', 'kl'),
(59, 'Cambodian', 'km'),
(60, 'Kannada', 'kn'),
(61, 'Korean', 'ko'),
(62, 'Kashmiri', 'ks'),
(63, 'Kurdish', 'ku'),
(64, 'Kirghiz', 'ky'),
(65, 'Latin', 'la'),
(66, 'Lingala', 'ln'),
(67, 'Laothian', 'lo'),
(68, 'Lithuanian', 'lt'),
(69, 'Latvian/Lettish', 'lv'),
(70, 'Malagasy', 'mg'),
(71, 'Maori', 'mi'),
(72, 'Macedonian', 'mk'),
(73, 'Malayalam', 'ml'),
(74, 'Mongolian', 'mn'),
(75, 'Moldavian', 'mo'),
(76, 'Marathi', 'mr'),
(77, 'Malay', 'ms'),
(78, 'Maltese', 'mt'),
(79, 'Burmese', 'my'),
(80, 'Nauru', 'na'),
(81, 'Nepali', 'ne'),
(82, 'Dutch', 'nl'),
(83, 'Norwegian', 'no'),
(84, 'Occitan', 'oc'),
(85, '(Afan)/Oromoor/Oriya', 'om'),
(86, 'Punjabi', 'pa'),
(87, 'Polish', 'pl'),
(88, 'Pashto/Pushto', 'ps'),
(89, 'Portuguese', 'pt'),
(90, 'Quechua', 'qu'),
(91, 'Rhaeto-Romance', 'rm'),
(92, 'Kirundi', 'rn'),
(93, 'Romanian', 'ro'),
(94, 'Russian', 'ru'),
(95, 'Kinyarwanda', 'rw'),
(96, 'Sanskrit', 'sa'),
(97, 'Sindhi', 'sd'),
(98, 'Sangro', 'sg'),
(99, 'Serbo-Croatian', 'sh'),
(100, 'Singhalese', 'si'),
(101, 'Slovak', 'sk'),
(102, 'Slovenian', 'sl'),
(103, 'Samoan', 'sm'),
(104, 'Shona', 'sn'),
(105, 'Somali', 'so'),
(106, 'Albanian', 'sq'),
(107, 'Serbian', 'sr'),
(108, 'Siswati', 'ss'),
(109, 'Sesotho', 'st'),
(110, 'Sundanese', 'su'),
(111, 'Swedish', 'sv'),
(112, 'Swahili', 'sw'),
(113, 'Tamil', 'ta'),
(114, 'Telugu', 'te'),
(115, 'Tajik', 'tg'),
(116, 'Thai', 'th'),
(117, 'Tigrinya', 'ti'),
(118, 'Turkmen', 'tk'),
(119, 'Tagalog', 'tl'),
(120, 'Setswana', 'tn'),
(121, 'Tonga', 'to'),
(122, 'Turkish', 'tr'),
(123, 'Tsonga', 'ts'),
(124, 'Tatar', 'tt'),
(125, 'Twi', 'tw'),
(126, 'Ukrainian', 'uk'),
(127, 'Urdu', 'ur'),
(128, 'Uzbek', 'uz'),
(129, 'Vietnamese', 'vi'),
(130, 'Volapuk', 'vo'),
(131, 'Wolof', 'wo'),
(132, 'Xhosa', 'xh'),
(133, 'Yoruba', 'yo'),
(134, 'Chinese', 'zh'),
(135, 'Zulu', 'zu');

-- --------------------------------------------------------

--
-- Table structure for table `langstrings`
--

CREATE TABLE `langstrings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `langstrvals`
--

CREATE TABLE `langstrvals` (
  `id` bigint UNSIGNED NOT NULL,
  `localize_id` bigint UNSIGNED NOT NULL,
  `langstring_id` bigint UNSIGNED NOT NULL,
  `value` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` bigint UNSIGNED NOT NULL,
  `langname` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `langname`, `value`, `created_at`, `updated_at`) VALUES
(1, 'English', 'en', '2022-12-08 00:29:24', '2022-12-08 00:29:24'),
(2, 'Arabic', 'ar', '2022-12-08 00:29:24', '2022-12-08 00:29:24');

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--

CREATE TABLE `leave_types` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `leave_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `leave_days` int NOT NULL,
  `leave_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_types`
--

INSERT INTO `leave_types` (`id`, `uuid`, `leave_type`, `leave_days`, `leave_code`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '634e99b9-cc28-4460-a981-3acf07e26e8a', 'Casual', 10, 'casual', 39, 39, '2023-05-07 20:22:55', '2023-06-21 21:40:46', NULL),
(2, 'c2a32ea9-9273-48a4-83f0-852813bb7063', 'sdfsdf', 32, 'sdfds', 39, NULL, '2023-06-06 22:18:03', '2023-06-06 22:36:03', '2023-06-06 22:36:03'),
(3, 'df08c3ed-825b-45ba-91ef-c7ea222abe3d', 'Sick', 14, 'sick-leave', 39, 39, '2023-06-06 22:46:11', '2023-06-21 21:40:27', NULL),
(4, 'd1579d8e-553a-4d9b-8088-6dfeeed434dd', 'Annual', 12, 'yearly-leave', 39, NULL, '2023-06-21 21:40:12', '2023-06-21 21:40:12', NULL),
(5, '9e07fd0c-3724-40e6-b356-1f135eb9068a', '45', 45, '45', 39, NULL, '2023-09-06 23:16:48', '2023-09-06 23:16:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `leave_type_years`
--

CREATE TABLE `leave_type_years` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `leave_type_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `entitled` int DEFAULT NULL,
  `taken` int NOT NULL DEFAULT '0',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `loan_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `permission_by_id` bigint UNSIGNED NOT NULL,
  `amount` double NOT NULL,
  `interest_rate` double NOT NULL,
  `installment` double NOT NULL,
  `installment_period` int NOT NULL DEFAULT '0' COMMENT 'Number of Installment',
  `installment_cleared` int NOT NULL DEFAULT '0' COMMENT 'Number of Installment Cleard from salary generate',
  `repayment_amount` double NOT NULL,
  `released_amount` double DEFAULT NULL,
  `release` int DEFAULT NULL,
  `approved_date` date NOT NULL,
  `repayment_start_date` date NOT NULL,
  `loan_details` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `manual_attendances`
--

CREATE TABLE `manual_attendances` (
  `id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `time` datetime NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `marital_statuses`
--

CREATE TABLE `marital_statuses` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `marital_statuses`
--

INSERT INTO `marital_statuses` (`id`, `uuid`, `name`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '9c9ec12a-509f-47ee-a5d7-eeddb96c6497', 'Single', 1, NULL, NULL, NULL, NULL, NULL),
(2, '3bb56ae1-3416-49ea-baab-dcc343dd60de', 'Married', 1, NULL, NULL, NULL, NULL, NULL),
(3, 'ff8f2cad-f657-4d33-98cc-6200c0e535c2', 'Divorced', 1, NULL, NULL, NULL, NULL, NULL),
(4, 'fffd50df-2bcd-434e-bcd7-95aeb65673f5', 'Widowed', 1, NULL, NULL, NULL, NULL, NULL),
(5, '22a6a23f-ac37-43db-b891-2789afac558f', 'Other', 1, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender_id` bigint UNSIGNED NOT NULL COMMENT 'User or employee who will send the message',
  `receiver_id` bigint UNSIGNED NOT NULL COMMENT 'User or employee who will send the message',
  `subject` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `datetime` datetime NOT NULL,
  `sender_status` tinyint NOT NULL DEFAULT '0' COMMENT '0=unseen, 1=seen, 2=delete',
  `receiver_status` tinyint NOT NULL DEFAULT '0' COMMENT '0=unseen, 1=seen, 2=delete',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_100000_create_password_resets_table', 1),
(2, '2019_08_19_000000_create_failed_jobs_table', 1),
(3, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(4, '2022_04_06_050120_create_pictures_table', 1),
(5, '2022_04_13_032828_create_applications_table', 1),
(6, '2022_04_13_050141_create_currencies_table', 1),
(7, '2022_04_18_075935_create_email_configs_table', 1),
(8, '2022_08_04_064209_create_salary_advances_table', 1),
(9, '2022_08_06_043012_create_users_table', 1),
(10, '2022_08_06_043013_create_user_types_table', 1),
(11, '2022_08_06_054634_create_password_settings_table', 1),
(12, '2022_08_06_062522_create_departments_table', 1),
(13, '2022_08_06_093438_create_positions_table', 1),
(14, '2022_08_06_101828_create_employees_table', 1),
(15, '2022_08_07_091539_create_bank_infos_table', 1),
(16, '2022_08_07_092430_create_employee_files_table', 1),
(17, '2022_08_08_034517_create_countries_table', 1),
(18, '2022_08_08_052408_create_setup_rules_table', 1),
(19, '2022_08_17_091340_create_genders_table', 1),
(20, '2022_09_01_075105_create_pay_frequencies_table', 1),
(21, '2022_09_13_050806_create_acc_coas_table', 1),
(22, '2022_09_13_051743_create_acc_monthly_balances_table', 1),
(23, '2022_09_13_052219_create_acc_opening_balances_table', 1),
(24, '2022_09_13_052644_create_acc_predefine_accounts_table', 1),
(25, '2022_09_13_054137_create_acc_subcodes_table', 1),
(26, '2022_09_13_054420_create_acc_subtypes_table', 1),
(27, '2022_09_13_054511_create_acc_transactions_table', 1),
(28, '2022_09_13_054527_create_acc_types_table', 1),
(29, '2022_09_13_054632_create_acc_vouchers_table', 1),
(30, '2022_09_13_054708_create_financial_years_table', 1),
(31, '2022_09_24_054816_create_acc_voucher_types_table', 1),
(32, '2022_09_28_050401_create_employee_types', 1),
(33, '2022_09_28_050555_create_duty_types_table', 1),
(34, '2022_09_29_074823_create_banks_table', 1),
(35, '2022_09_29_092925_create_leave_types_table', 1),
(36, '2022_09_29_094055_create_marital_statuses_table', 1),
(37, '2022_09_29_094852_create_week_holidays_table', 1),
(38, '2022_09_29_100125_create_holidays_table', 1),
(39, '2022_09_29_101100_create_apply_leaves_table', 1),
(40, '2022_10_01_083156_create_loans_table', 1),
(41, '2022_10_01_083809_create_leave_type_years_table', 1),
(42, '2022_10_01_151152_create_attendences_table', 1),
(43, '2022_10_02_054940_create_salary_generates_table', 1),
(44, '2022_10_02_055044_create_salary_sheet_generates_table', 1),
(45, '2022_10_02_113809_create_tax_calculations_table', 1),
(46, '2022_10_10_100252_create_permission_tables', 1),
(47, '2022_10_10_102515_create_per_menus_table', 1),
(48, '2022_10_17_105622_create_employee_allowence_deductions_table', 1),
(49, '2022_11_07_055916_create_acc_quarters_table', 1),
(50, '2022_11_10_035218_create_notifications_table', 1),
(51, '2022_12_07_071056_create_languages_table', 1),
(52, '2022_12_07_094945_create_langstrings_table', 1),
(53, '2022_12_07_095044_create_langstrvals_table', 1),
(54, '2023_03_30_045907_create_employee_docs_table', 1),
(55, '2023_04_09_052949_create_manual_attendances_table', 1),
(56, '2023_04_12_065038_create_employee_salary_types_table', 1),
(57, '2023_04_13_102837_create_skill_types_table', 1),
(58, '2023_04_13_102849_create_certificate_types_table', 1),
(59, '2023_04_16_061138_create_employee_academic_infos_table', 1),
(60, '2023_04_16_061151_create_employee_other_docs_table', 1),
(61, '2023_04_18_055042_create_attn_user_infos_table', 1),
(62, '2023_04_18_074832_create_attn_checkin_checkouts_table', 1),
(63, '2023_04_29_104322_create_zkts_table', 1),
(64, '2023_05_07_094405_create_doc_expired_settings_table', 1),
(65, '2023_07_23_103802_create_prefixes_table', 1),
(66, '2023_08_13_104855_create_activity_log_table', 1),
(67, '2023_08_13_104856_add_event_column_to_activity_log_table', 1),
(68, '2023_08_13_104857_add_batch_uuid_column_to_activity_log_table', 1),
(69, '2024_04_18_052245_create_notices_table', 1),
(70, '2024_04_18_065516_add_fields_to_notices', 1),
(71, '2024_04_18_095157_create_awards_table', 1),
(72, '2024_04_20_051717_create_messages_table', 1),
(73, '2024_04_21_065839_create_reward_points_table', 1),
(74, '2024_04_21_075913_create_point_attendences_table', 1),
(75, '2024_04_21_080940_create_point_categories_table', 1),
(76, '2024_04_21_092152_create_point_collaboratives_table', 1),
(77, '2024_04_21_092959_create_point_management_table', 1),
(78, '2024_04_21_093414_create_point_settings_table', 1),
(79, '2024_04_21_101823_create_candidate_information_table', 1),
(80, '2024_04_21_105641_create_candidate_education_table', 1),
(81, '2024_04_21_111232_create_candidate_interviews_table', 1),
(82, '2024_04_21_113310_create_candidate_selections_table', 1),
(83, '2024_04_21_114030_create_candidate_shortlists_table', 1),
(84, '2024_04_21_114903_create_candidate_work_experiences_table', 1),
(85, '2024_04_23_130311_create_project_management_table', 1),
(86, '2024_04_23_184144_create_units_table', 1),
(87, '2024_04_24_044611_update_project_management_and_extend_columns', 1),
(88, '2024_04_24_055844_create_project_clients_table', 1),
(89, '2024_04_24_061014_create_project_employees_table', 1),
(90, '2024_04_24_062703_create_project_sprints_table', 1),
(91, '2024_04_24_063526_create_project_tasks_table', 1),
(92, '2024_04_24_110837_create_procurement_committees_table', 1),
(93, '2024_04_24_135417_create_procurement_vendors_table', 1),
(94, '2024_04_24_170623_create_procurement_requests_table', 1),
(95, '2024_04_24_172924_create_procurement_request_items_table', 1),
(96, '2024_04_25_081633_update_id_for__pm_projects_table', 1),
(97, '2024_04_25_140139_create_procurement_quotations_table', 1),
(98, '2024_04_25_192841_create_procurement_bid_analyses_table', 1),
(99, '2024_04_27_175713_create_procurement_purchase_orders_table', 1),
(100, '2024_04_28_112720_create_procurement_goods_receiveds_table', 1),
(101, '2024_05_05_053538_update_applications_table', 1),
(102, '2024_05_07_100919_add_fields_to_acc_predefine_accounts_table', 1),
(103, '2024_12_07_071126_create_employee_performance_types_table', 1),
(104, '2024_12_07_071454_create_employee_performance_criterias_table', 1),
(105, '2024_12_07_072059_create_employee_performance_evaluations_table', 1),
(106, '2024_12_07_073025_create_employee_performances_table', 1),
(107, '2024_12_07_085005_create_employee_performance_values_table', 1),
(108, '2024_12_08_035201_create_employee_performance_development_plans_table', 1),
(109, '2024_12_08_035231_create_employee_performance_key_goals_table', 1),
(110, '2024_12_26_125534_create_employee_performance_evaluation_types_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` bigint UNSIGNED NOT NULL,
  `notice_descriptiion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `notice_date` date NOT NULL,
  `notice_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notice_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notice_attachment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_settings`
--

CREATE TABLE `password_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `salt` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_length` int NOT NULL,
  `max_lifetime` int NOT NULL,
  `password_complexcity` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_history` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lock_out_duration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `session_idle_logout_time` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pay_frequencies`
--

CREATE TABLE `pay_frequencies` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `frequency_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pay_frequencies`
--

INSERT INTO `pay_frequencies` (`id`, `uuid`, `frequency_name`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '475ecec0-34f0-40dc-8d95-4ac1d67f6cdf', 'Weekly', 1, NULL, NULL, NULL, NULL, NULL),
(2, '79139342-f773-4b52-9f16-dbed282aec41', 'Biweekly', 1, NULL, NULL, NULL, NULL, NULL),
(3, '34980a03-38ab-4f71-9f77-57a5f23c9c6a', 'Monthly', 1, NULL, NULL, NULL, NULL, NULL),
(4, '81a35f64-76f3-4900-b911-9c789d8b675c', 'Yearly', 1, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `per_menu_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `per_menu_id`, `created_at`, `updated_at`) VALUES
(1, 'read_dashboard', 'web', 1, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(2, 'read_human_resource_menu', 'web', 2, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(3, 'create_department', 'web', 3, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(4, 'read_department', 'web', 3, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(5, 'update_department', 'web', 3, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(6, 'delete_department', 'web', 3, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(7, 'create_employee', 'web', 4, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(8, 'read_employee', 'web', 4, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(9, 'update_employee', 'web', 4, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(10, 'delete_employee', 'web', 4, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(11, 'create_employee_status', 'web', 5, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(12, 'read_employee_status', 'web', 5, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(13, 'update_employee_status', 'web', 5, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(14, 'delete_employee_status', 'web', 5, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(15, 'create_payroll', 'web', 6, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(16, 'read_payroll', 'web', 6, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(17, 'update_payroll', 'web', 6, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(18, 'delete_payroll', 'web', 6, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(19, 'create_loan', 'web', 7, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(20, 'read_loan', 'web', 7, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(21, 'update_loan', 'web', 7, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(22, 'delete_loan', 'web', 7, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(23, 'create_bank', 'web', 8, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(24, 'read_bank', 'web', 8, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(25, 'update_bank', 'web', 8, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(26, 'delete_bank', 'web', 8, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(27, 'create_setup_rules', 'web', 9, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(28, 'read_setup_rules', 'web', 9, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(29, 'update_setup_rules', 'web', 9, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(30, 'delete_setup_rules', 'web', 9, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(31, 'create_leave', 'web', 10, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(32, 'read_leave', 'web', 10, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(33, 'update_leave', 'web', 10, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(34, 'delete_leave', 'web', 10, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(35, 'create_attendance', 'web', 11, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(36, 'read_attendance', 'web', 11, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(37, 'update_attendance', 'web', 11, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(38, 'delete_attendance', 'web', 11, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(39, 'attendance_management', 'web', 12, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(40, 'create_award', 'web', 13, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(41, 'read_award', 'web', 13, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(42, 'update_award', 'web', 13, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(43, 'delete_award', 'web', 13, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(44, 'create_id_card', 'web', 14, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(45, 'read_id_card', 'web', 14, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(46, 'update_id_card', 'web', 14, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(47, 'delete_id_card', 'web', 14, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(48, 'create_reports', 'web', 15, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(49, 'read_reports', 'web', 15, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(50, 'update_reports', 'web', 15, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(51, 'create_activity_log', 'web', 16, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(52, 'read_activity_log', 'web', 16, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(53, 'update_activity_log', 'web', 16, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(54, 'delete_activity_log', 'web', 16, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(55, 'create_attendance_report', 'web', 17, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(56, 'read_attendance_report', 'web', 17, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(57, 'update_attendance_report', 'web', 17, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(58, 'delete_attendance_report', 'web', 17, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(59, 'create_attendance_summary', 'web', 18, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(60, 'read_attendance_summary', 'web', 18, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(61, 'update_attendance_summary', 'web', 18, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(62, 'delete_attendance_summary', 'web', 18, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(63, 'create_employee_report', 'web', 19, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(64, 'read_employee_report', 'web', 19, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(65, 'update_employee_report', 'web', 19, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(66, 'delete_employee_report', 'web', 19, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(67, 'create_job_card_report', 'web', 20, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(68, 'read_job_card_report', 'web', 20, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(69, 'update_job_card_report', 'web', 20, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(70, 'delete_job_card_report', 'web', 20, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(71, 'create_contract_renewal_report', 'web', 21, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(72, 'read_contract_renewal_report', 'web', 21, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(73, 'update_contract_renewal_report', 'web', 21, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(74, 'delete_contract_renewal_report', 'web', 21, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(75, 'create_allowance_report', 'web', 22, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(76, 'read_allowance_report', 'web', 22, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(77, 'update_allowance_report', 'web', 22, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(78, 'delete_allowance_report', 'web', 22, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(79, 'create_deduction_report', 'web', 23, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(80, 'read_deduction_report', 'web', 23, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(81, 'update_deduction_report', 'web', 23, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(82, 'delete_deduction_report', 'web', 23, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(83, 'create_leave_report', 'web', 24, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(84, 'read_leave_report', 'web', 24, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(85, 'update_leave_report', 'web', 24, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(86, 'delete_leave_report', 'web', 24, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(87, 'create_payroll_report', 'web', 25, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(88, 'read_payroll_report', 'web', 25, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(89, 'update_payroll_report', 'web', 25, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(90, 'delete_payroll_report', 'web', 25, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(91, 'create_accounts', 'web', 26, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(92, 'read_accounts', 'web', 26, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(93, 'update_accounts', 'web', 26, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(94, 'delete_accounts', 'web', 26, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(95, 'create_financial_year', 'web', 27, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(96, 'read_financial_year', 'web', 27, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(97, 'update_financial_year', 'web', 27, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(98, 'delete_financial_year', 'web', 27, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(99, 'create_opening_balance', 'web', 28, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(100, 'read_opening_balance', 'web', 28, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(101, 'update_opening_balance', 'web', 28, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(102, 'delete_opening_balance', 'web', 28, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(103, 'create_chart_of_accounts', 'web', 29, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(104, 'read_chart_of_accounts', 'web', 29, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(105, 'update_chart_of_accounts', 'web', 29, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(106, 'delete_chart_of_accounts', 'web', 29, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(107, 'read_predefine_accounts', 'web', 30, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(108, 'create_predefine_accounts', 'web', 30, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(109, 'update_predefine_accounts', 'web', 30, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(110, 'delete_predefine_accounts', 'web', 30, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(111, 'create_sub_account', 'web', 31, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(112, 'read_sub_account', 'web', 31, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(113, 'update_sub_account', 'web', 31, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(114, 'delete_sub_account', 'web', 31, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(115, 'create_voucher', 'web', 32, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(116, 'read_voucher', 'web', 32, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(117, 'update_voucher', 'web', 32, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(118, 'delete_voucher', 'web', 32, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(119, 'create_voucher_approval', 'web', 33, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(120, 'read_voucher_approval', 'web', 33, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(121, 'update_voucher_approval', 'web', 33, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(122, 'delete_voucher_approval', 'web', 33, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(123, 'create_account_report', 'web', 34, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(124, 'read_account_report', 'web', 34, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(125, 'update_account_report', 'web', 34, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(126, 'delete_account_report', 'web', 34, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(127, 'create_cash_book', 'web', 35, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(128, 'read_cash_book', 'web', 35, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(129, 'update_cash_book', 'web', 35, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(130, 'delete_cash_book', 'web', 35, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(131, 'create_bank_book', 'web', 36, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(132, 'read_bank_book', 'web', 36, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(133, 'update_bank_book', 'web', 36, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(134, 'delete_bank_book', 'web', 36, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(135, 'create_day_book', 'web', 37, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(136, 'read_day_book', 'web', 37, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(137, 'update_day_book', 'web', 37, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(138, 'delete_day_book', 'web', 37, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(139, 'create_control_ledger', 'web', 38, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(140, 'read_control_ledger', 'web', 38, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(141, 'update_control_ledger', 'web', 38, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(142, 'delete_control_ledger', 'web', 38, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(143, 'create_general_ledger', 'web', 39, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(144, 'read_general_ledger', 'web', 39, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(145, 'update_general_ledger', 'web', 39, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(146, 'delete_general_ledger', 'web', 39, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(147, 'create_sub_ledger', 'web', 40, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(148, 'read_sub_ledger', 'web', 40, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(149, 'update_sub_ledger', 'web', 40, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(150, 'delete_sub_ledger', 'web', 40, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(151, 'create_note_ledger', 'web', 41, '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(152, 'read_note_ledger', 'web', 41, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(153, 'update_note_ledger', 'web', 41, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(154, 'delete_note_ledger', 'web', 41, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(155, 'create_receipt_payment', 'web', 42, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(156, 'read_receipt_payment', 'web', 42, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(157, 'update_receipt_payment', 'web', 42, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(158, 'delete_receipt_payment', 'web', 42, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(159, 'create_trail_balance', 'web', 43, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(160, 'read_trail_balance', 'web', 43, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(161, 'update_trail_balance', 'web', 43, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(162, 'delete_trail_balance', 'web', 43, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(163, 'create_profit_loss', 'web', 44, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(164, 'read_profit_loss', 'web', 44, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(165, 'update_profit_loss', 'web', 44, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(166, 'delete_profit_loss', 'web', 44, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(167, 'create_balance_sheet', 'web', 45, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(168, 'read_balance_sheet', 'web', 45, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(169, 'update_balance_sheet', 'web', 45, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(170, 'delete_balance_sheet', 'web', 45, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(171, 'create_report', 'web', 46, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(172, 'read_report', 'web', 46, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(173, 'update_report', 'web', 46, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(174, 'delete_report', 'web', 46, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(175, 'create_setting', 'web', 47, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(176, 'read_setting', 'web', 47, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(177, 'update_setting', 'web', 47, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(178, 'delete_setting', 'web', 47, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(179, 'create_software_setup', 'web', 48, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(180, 'read_software_setup', 'web', 48, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(181, 'update_software_setup', 'web', 48, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(182, 'delete_software_setup', 'web', 48, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(183, 'create_application', 'web', 49, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(184, 'read_application', 'web', 49, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(185, 'update_application', 'web', 49, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(186, 'delete_application', 'web', 49, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(187, 'create_currency', 'web', 50, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(188, 'read_currency', 'web', 50, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(189, 'update_currency', 'web', 50, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(190, 'delete_currency', 'web', 50, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(191, 'create_mail_setup', 'web', 51, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(192, 'read_mail_setup', 'web', 51, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(193, 'update_mail_setup', 'web', 51, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(194, 'delete_mail_setup', 'web', 51, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(195, 'create_sms_setup', 'web', 52, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(196, 'read_sms_setup', 'web', 52, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(197, 'update_sms_setup', 'web', 52, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(198, 'delete_sms_setup', 'web', 52, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(199, 'create_password_setting', 'web', 53, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(200, 'read_password_setting', 'web', 53, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(201, 'update_password_setting', 'web', 53, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(202, 'delete_password_setting', 'web', 53, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(203, 'create_language', 'web', 54, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(204, 'read_language', 'web', 54, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(205, 'update_language', 'web', 54, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(206, 'delete_language', 'web', 54, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(207, 'create_add_language', 'web', 54, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(208, 'read_add_language', 'web', 54, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(209, 'update_add_language', 'web', 54, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(210, 'delete_add_language', 'web', 54, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(211, 'create_language_list', 'web', 55, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(212, 'read_language_list', 'web', 55, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(213, 'update_language_list', 'web', 55, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(214, 'delete_language_list', 'web', 55, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(215, 'create_language_strings', 'web', 56, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(216, 'read_language_strings', 'web', 56, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(217, 'update_language_strings', 'web', 56, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(218, 'delete_language_strings', 'web', 56, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(219, 'create_user_management', 'web', 57, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(220, 'read_user_management', 'web', 57, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(221, 'update_user_management', 'web', 57, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(222, 'delete_user_management', 'web', 57, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(223, 'create_role_list', 'web', 58, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(224, 'read_role_list', 'web', 58, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(225, 'update_role_list', 'web', 58, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(226, 'delete_role_list', 'web', 58, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(227, 'create_user_list', 'web', 59, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(228, 'read_user_list', 'web', 59, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(229, 'update_user_list', 'web', 59, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(230, 'delete_user_list', 'web', 59, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(231, 'create_inactive_employees_list', 'web', 60, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(232, 'read_inactive_employees_list', 'web', 60, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(233, 'update_inactive_employees_list', 'web', 60, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(234, 'delete_inactive_employees_list', 'web', 60, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(235, 'create_salary_setup', 'web', 61, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(236, 'read_salary_setup', 'web', 61, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(237, 'update_salary_setup', 'web', 61, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(238, 'delete_salary_setup', 'web', 61, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(239, 'create_salary_advance', 'web', 62, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(240, 'read_salary_advance', 'web', 62, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(241, 'update_salary_advance', 'web', 62, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(242, 'delete_salary_advance', 'web', 62, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(243, 'create_salary_generate', 'web', 63, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(244, 'read_salary_generate', 'web', 63, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(245, 'update_salary_generate', 'web', 63, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(246, 'delete_salary_generate', 'web', 63, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(247, 'create_manage_employee_salary', 'web', 64, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(248, 'read_manage_employee_salary', 'web', 64, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(249, 'update_manage_employee_salary', 'web', 64, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(250, 'delete_manage_employee_salary', 'web', 64, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(251, 'create_positions', 'web', 65, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(252, 'read_positions', 'web', 65, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(253, 'update_positions', 'web', 65, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(254, 'delete_positions', 'web', 65, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(255, 'create_weekly_holiday', 'web', 66, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(256, 'read_weekly_holiday', 'web', 66, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(257, 'update_weekly_holiday', 'web', 66, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(258, 'delete_weekly_holiday', 'web', 66, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(259, 'create_holiday', 'web', 67, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(260, 'read_holiday', 'web', 67, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(261, 'update_holiday', 'web', 67, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(262, 'delete_holiday', 'web', 67, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(263, 'create_leave_type', 'web', 68, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(264, 'read_leave_type', 'web', 68, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(265, 'update_leave_type', 'web', 68, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(266, 'delete_leave_type', 'web', 68, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(267, 'create_leave_generate', 'web', 69, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(268, 'read_leave_generate', 'web', 69, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(269, 'update_leave_generate', 'web', 69, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(270, 'delete_leave_generate', 'web', 69, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(271, 'create_leave_approval', 'web', 70, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(272, 'read_leave_approval', 'web', 70, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(273, 'update_leave_approval', 'web', 70, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(274, 'delete_leave_approval', 'web', 70, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(275, 'create_leave_application', 'web', 71, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(276, 'read_leave_application', 'web', 71, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(277, 'update_leave_application', 'web', 71, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(278, 'delete_leave_application', 'web', 71, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(279, 'create_monthly_attendance', 'web', 72, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(280, 'read_monthly_attendance', 'web', 72, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(281, 'update_monthly_attendance', 'web', 72, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(282, 'delete_monthly_attendance', 'web', 72, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(283, 'create_missing_attendance', 'web', 73, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(284, 'read_missing_attendance', 'web', 73, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(285, 'update_missing_attendance', 'web', 73, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(286, 'delete_missing_attendance', 'web', 73, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(287, 'create_quarter', 'web', 74, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(288, 'read_quarter', 'web', 74, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(289, 'update_quarter', 'web', 74, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(290, 'delete_quarter', 'web', 74, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(291, 'create_subtype', 'web', 75, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(292, 'read_subtype', 'web', 75, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(293, 'update_subtype', 'web', 75, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(294, 'delete_subtype', 'web', 75, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(295, 'create_debit_voucher', 'web', 76, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(296, 'read_debit_voucher', 'web', 76, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(297, 'update_debit_voucher', 'web', 76, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(298, 'delete_debit_voucher', 'web', 76, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(299, 'create_debit_voucher_reverse', 'web', 77, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(300, 'create_credit_voucher', 'web', 78, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(301, 'read_credit_voucher', 'web', 78, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(302, 'update_credit_voucher', 'web', 78, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(303, 'delete_credit_voucher', 'web', 78, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(304, 'create_credit_voucher_reverse', 'web', 79, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(305, 'create_contra_voucher', 'web', 80, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(306, 'read_contra_voucher', 'web', 80, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(307, 'update_contra_voucher', 'web', 80, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(308, 'delete_contra_voucher', 'web', 80, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(309, 'create_contra_voucher_reverse', 'web', 81, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(310, 'create_journal_voucher', 'web', 82, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(311, 'read_journal_voucher', 'web', 82, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(312, 'update_journal_voucher', 'web', 82, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(313, 'delete_journal_voucher', 'web', 82, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(314, 'create_journal_voucher_reverse', 'web', 83, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(315, 'create_attendance_details_report', 'web', 84, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(316, 'read_attendance_details_report', 'web', 84, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(317, 'update_attendance_details_report', 'web', 84, '2024-05-12 22:35:46', '2024-05-12 22:35:46'),
(318, 'delete_attendance_details_report', 'web', 84, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(319, 'create_budget_allocation', 'web', 85, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(320, 'read_budget_allocation', 'web', 85, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(321, 'update_budget_allocation', 'web', 85, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(322, 'delete_budget_allocation', 'web', 85, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(323, 'create_budget_request', 'web', 86, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(324, 'read_budget_request', 'web', 86, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(325, 'update_budget_request', 'web', 86, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(326, 'delete_budget_request', 'web', 86, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(327, 'create_budget_allocation_report', 'web', 87, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(328, 'read_budget_allocation_report', 'web', 87, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(329, 'update_budget_allocation_report', 'web', 87, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(330, 'delete_budget_allocation_report', 'web', 87, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(331, 'create_budget_allocation_menu', 'web', 88, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(332, 'read_budget_allocation_menu', 'web', 88, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(333, 'update_budget_allocation_menu', 'web', 88, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(334, 'delete_budget_allocation_menu', 'web', 88, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(335, 'create_factory_reset', 'web', 89, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(336, 'read_factory_reset', 'web', 89, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(337, 'update_factory_reset', 'web', 89, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(338, 'delete_factory_reset', 'web', 89, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(339, 'create_stock_management', 'web', 90, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(340, 'read_stock_management', 'web', 90, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(341, 'update_stock_management', 'web', 90, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(342, 'delete_stock_management', 'web', 90, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(343, 'create_opening_stock', 'web', 91, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(344, 'read_opening_stock', 'web', 91, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(345, 'update_opening_stock', 'web', 91, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(346, 'delete_opening_stock', 'web', 91, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(347, 'create_stock_adjustment', 'web', 92, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(348, 'read_stock_adjustment', 'web', 92, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(349, 'update_stock_adjustment', 'web', 92, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(350, 'delete_stock_adjustment', 'web', 92, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(351, 'create_stock_report', 'web', 93, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(352, 'read_stock_report', 'web', 93, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(353, 'update_stock_report', 'web', 93, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(354, 'delete_stock_report', 'web', 93, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(355, 'create_supplier_wise_sale_profit', 'web', 94, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(356, 'read_supplier_wise_sale_profit', 'web', 94, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(357, 'update_supplier_wise_sale_profit', 'web', 94, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(358, 'delete_supplier_wise_sale_profit', 'web', 94, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(359, 'read_journal_voucher_reverse', 'web', 83, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(360, 'update_journal_voucher_reverse', 'web', 83, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(361, 'delete_journal_voucher_reverse', 'web', 83, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(362, 'read_credit_voucher_reverse', 'web', 79, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(363, 'update_credit_voucher_reverse', 'web', 79, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(364, 'delete_credit_voucher_reverse', 'web', 79, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(365, 'read_debit_voucher_reverse', 'web', 77, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(366, 'update_debit_voucher_reverse', 'web', 77, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(367, 'delete_debit_voucher_reverse', 'web', 77, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(368, 'read_contra_voucher_reverse', 'web', 81, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(369, 'update_contra_voucher_reverse', 'web', 81, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(370, 'delete_contra_voucher_reverse', 'web', 81, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(371, 'create_sub_departments', 'web', 95, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(372, 'read_sub_departments', 'web', 95, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(373, 'update_sub_departments', 'web', 95, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(374, 'delete_sub_departments', 'web', 95, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(375, 'create_loan_disburse_report', 'web', 96, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(376, 'read_loan_disburse_report', 'web', 96, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(377, 'update_loan_disburse_report', 'web', 96, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(378, 'delete_loan_disburse_report', 'web', 96, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(379, 'create_employee_wise_loan', 'web', 97, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(380, 'read_employee_wise_loan', 'web', 97, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(381, 'update_employee_wise_loan', 'web', 97, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(382, 'delete_employee_wise_loan', 'web', 97, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(383, 'create_employee_wise_attendance', 'web', 98, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(384, 'read_employee_wise_attendance', 'web', 98, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(385, 'update_employee_wise_attendance', 'web', 98, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(386, 'delete_employee_wise_attendance', 'web', 98, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(387, 'create_hrm_setup', 'web', 99, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(388, 'read_hrm_setup', 'web', 99, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(389, 'update_hrm_setup', 'web', 99, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(390, 'delete_hrm_setup', 'web', 99, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(391, 'create_backup_and_reset', 'web', 100, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(392, 'read_backup_and_reset', 'web', 100, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(393, 'update_backup_and_reset', 'web', 100, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(394, 'delete_backup_and_reset', 'web', 100, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(395, 'create_notice', 'web', 101, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(396, 'read_notice', 'web', 101, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(397, 'update_notice', 'web', 101, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(398, 'delete_notice', 'web', 101, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(399, 'create_reward_points', 'web', 102, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(400, 'read_reward_points', 'web', 102, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(401, 'update_reward_points', 'web', 102, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(402, 'delete_reward_points', 'web', 102, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(403, 'create_point_settings', 'web', 103, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(404, 'read_point_settings', 'web', 103, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(405, 'update_point_settings', 'web', 103, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(406, 'delete_point_settings', 'web', 103, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(407, 'create_point_categories', 'web', 104, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(408, 'read_point_categories', 'web', 104, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(409, 'update_point_categories', 'web', 104, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(410, 'delete_point_categories', 'web', 104, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(411, 'create_management_points', 'web', 105, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(412, 'read_management_points', 'web', 105, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(413, 'update_management_points', 'web', 105, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(414, 'delete_management_points', 'web', 105, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(415, 'create_collaborative_points', 'web', 106, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(416, 'read_collaborative_points', 'web', 106, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(417, 'update_collaborative_points', 'web', 106, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(418, 'delete_collaborative_points', 'web', 106, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(419, 'create_employee_points', 'web', 107, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(420, 'read_employee_points', 'web', 107, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(421, 'update_employee_points', 'web', 107, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(422, 'delete_employee_points', 'web', 107, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(423, 'create_attendance_points', 'web', 108, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(424, 'read_attendance_points', 'web', 108, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(425, 'update_attendance_points', 'web', 108, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(426, 'delete_attendance_points', 'web', 108, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(427, 'create_recruitment', 'web', 109, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(428, 'read_recruitment', 'web', 109, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(429, 'update_recruitment', 'web', 109, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(430, 'delete_recruitment', 'web', 109, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(431, 'create_candidate_list', 'web', 110, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(432, 'read_candidate_list', 'web', 110, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(433, 'update_candidate_list', 'web', 110, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(434, 'delete_candidate_list', 'web', 110, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(435, 'create_candidate_shortlist', 'web', 111, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(436, 'read_candidate_shortlist', 'web', 111, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(437, 'update_candidate_shortlist', 'web', 111, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(438, 'delete_candidate_shortlist', 'web', 111, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(439, 'create_interview', 'web', 112, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(440, 'read_interview', 'web', 112, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(441, 'update_interview', 'web', 112, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(442, 'delete_interview', 'web', 112, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(443, 'create_candidate_selection', 'web', 113, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(444, 'read_candidate_selection', 'web', 113, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(445, 'update_candidate_selection', 'web', 113, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(446, 'delete_candidate_selection', 'web', 113, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(447, 'create_project_management', 'web', 114, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(448, 'read_project_management', 'web', 114, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(449, 'update_project_management', 'web', 114, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(450, 'delete_project_management', 'web', 114, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(451, 'create_clients', 'web', 115, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(452, 'read_clients', 'web', 115, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(453, 'update_clients', 'web', 115, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(454, 'delete_clients', 'web', 115, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(455, 'create_projects', 'web', 116, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(456, 'read_projects', 'web', 116, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(457, 'update_projects', 'web', 116, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(458, 'delete_projects', 'web', 116, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(459, 'create_task', 'web', 117, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(460, 'read_task', 'web', 117, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(461, 'update_task', 'web', 117, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(462, 'delete_task', 'web', 117, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(463, 'create_sprint', 'web', 118, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(464, 'read_sprint', 'web', 118, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(465, 'update_sprint', 'web', 118, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(466, 'delete_sprint', 'web', 118, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(467, 'create_manage_masks', 'web', 119, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(468, 'read_manage_masks', 'web', 119, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(469, 'update_manage_masks', 'web', 119, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(470, 'delete_manage_masks', 'web', 119, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(471, 'create_project_reports', 'web', 120, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(472, 'read_project_reports', 'web', 120, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(473, 'update_project_reports', 'web', 120, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(474, 'delete_project_reports', 'web', 120, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(475, 'create_project_lists', 'web', 121, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(476, 'read_project_lists', 'web', 121, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(477, 'update_project_lists', 'web', 121, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(478, 'delete_project_lists', 'web', 121, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(479, 'create_team_member', 'web', 122, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(480, 'read_team_member', 'web', 122, '2024-05-12 22:35:47', '2024-05-12 22:35:47'),
(481, 'update_team_member', 'web', 122, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(482, 'delete_team_member', 'web', 122, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(483, 'create_procurement', 'web', 123, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(484, 'read_procurement', 'web', 123, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(485, 'update_procurement', 'web', 123, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(486, 'delete_procurement', 'web', 123, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(487, 'create_request', 'web', 124, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(488, 'read_request', 'web', 124, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(489, 'update_request', 'web', 124, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(490, 'delete_request', 'web', 124, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(491, 'create_quotation', 'web', 125, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(492, 'read_quotation', 'web', 125, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(493, 'update_quotation', 'web', 125, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(494, 'delete_quotation', 'web', 125, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(495, 'create_bid_analysis', 'web', 126, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(496, 'read_bid_analysis', 'web', 126, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(497, 'update_bid_analysis', 'web', 126, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(498, 'delete_bid_analysis', 'web', 126, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(499, 'create_purchase_order', 'web', 127, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(500, 'read_purchase_order', 'web', 127, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(501, 'update_purchase_order', 'web', 127, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(502, 'delete_purchase_order', 'web', 127, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(503, 'create_goods_received', 'web', 128, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(504, 'read_goods_received', 'web', 128, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(505, 'update_goods_received', 'web', 128, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(506, 'delete_goods_received', 'web', 128, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(507, 'create_vendors', 'web', 129, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(508, 'read_vendors', 'web', 129, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(509, 'update_vendors', 'web', 129, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(510, 'delete_vendors', 'web', 129, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(511, 'create_committees', 'web', 130, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(512, 'read_committees', 'web', 130, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(513, 'update_committees', 'web', 130, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(514, 'delete_committees', 'web', 130, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(515, 'create_units', 'web', 131, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(516, 'read_units', 'web', 131, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(517, 'update_units', 'web', 131, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(518, 'delete_units', 'web', 131, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(519, 'create_adhoc_report', 'web', 132, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(520, 'read_adhoc_report', 'web', 132, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(521, 'update_adhoc_report', 'web', 132, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(522, 'delete_adhoc_report', 'web', 132, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(523, 'create_employee_performance', 'web', 133, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(524, 'read_employee_performance', 'web', 133, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(525, 'update_employee_performance', 'web', 133, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(526, 'delete_employee_performance', 'web', 133, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(527, 'create_messages', 'web', 134, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(528, 'read_messages', 'web', 134, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(529, 'update_messages', 'web', 134, '2024-05-12 22:35:48', '2024-05-12 22:35:48'),
(530, 'delete_messages', 'web', 134, '2024-05-12 22:35:48', '2024-05-12 22:35:48');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `per_menus`
--

CREATE TABLE `per_menus` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parentmenu_id` bigint DEFAULT NULL,
  `lable` bigint NOT NULL,
  `menu_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `per_menus`
--

INSERT INTO `per_menus` (`id`, `uuid`, `parentmenu_id`, `lable`, `menu_name`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '', NULL, 0, 'Dashboard', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(2, '', NULL, 0, 'Human Resource', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(3, '', NULL, 0, 'Department', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(4, '', NULL, 0, 'Employee', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(5, '', 4, 0, 'Employee Status', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(6, '', NULL, 0, 'Payroll', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(7, '', NULL, 0, 'Loan', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(8, '', NULL, 0, 'Bank', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(9, '', NULL, 0, 'Setup Rules', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(10, '', NULL, 0, 'Leave', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(11, '', NULL, 0, 'Attendance', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(12, '', 11, 0, 'Manage', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(13, '', NULL, 0, 'Award', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(14, '', NULL, 0, 'Id Card', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(15, '', NULL, 0, 'Reports', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(16, '', NULL, 0, 'Activity Log', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(17, '', NULL, 0, 'Attendance Report', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(18, '', 17, 0, 'Attendance Summary', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(19, '', NULL, 0, 'Employee Report', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(20, '', NULL, 0, 'Job Card Report', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(21, '', NULL, 0, 'Contract Renewal Report', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(22, '', NULL, 0, 'Allowance Report', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(23, '', NULL, 0, 'Deduction Report', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(24, '', NULL, 0, 'Leave Report', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(25, '', NULL, 0, 'Payroll Report', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(26, '', NULL, 0, 'Accounts', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(27, '', NULL, 0, 'Financial Year', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(28, '', NULL, 0, 'Opening Balance', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(29, '', NULL, 0, 'Chart of Accounts', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(30, '', NULL, 0, 'Predefine Accounts', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(31, '', NULL, 0, 'Sub Account', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(32, '', NULL, 0, 'Voucher', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(33, '', NULL, 0, 'Voucher Approval', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(34, '', NULL, 0, 'Account Report', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(35, '', NULL, 0, 'Cash Account', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(36, '', NULL, 0, 'Bank Account', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(37, '', NULL, 0, 'Day Account', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(38, '', NULL, 0, 'Control Ledger', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(39, '', NULL, 0, 'General Ledger', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(40, '', NULL, 0, 'Sub Ledger', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(41, '', NULL, 0, 'Note Ledger', NULL, NULL, '2024-05-12 22:35:45', '2024-05-12 22:35:45', NULL),
(42, '', NULL, 0, 'Receipt Payment', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(43, '', NULL, 0, 'Trail Balance', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(44, '', NULL, 0, 'Profit Loss', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(45, '', NULL, 0, 'Balance Sheet', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(46, '', NULL, 0, 'Report', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(47, '', NULL, 0, 'Setting', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(48, '', NULL, 0, 'Software Setup', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(49, '', NULL, 0, 'Application', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(50, '', NULL, 0, 'Currency', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(51, '', NULL, 0, 'Mail Setup', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(52, '', NULL, 0, 'SMS Setup', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(53, '', NULL, 0, 'Password Setting', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(54, '', NULL, 0, 'Language', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(55, '', NULL, 0, 'Language List', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(56, '', NULL, 0, 'Language Strings', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(57, '', NULL, 0, 'User Management', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(58, '', NULL, 0, 'Role List', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(59, '', NULL, 0, 'User List', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(60, '', NULL, 0, 'Inactive Employees List', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(61, '', NULL, 0, 'Salary Setup', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(62, '', NULL, 0, 'Salary Advance', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(63, '', NULL, 0, 'Salary Generate', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(64, '', NULL, 0, 'Manage Employee Salary', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(65, '', NULL, 0, 'Positions', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(66, '', NULL, 0, 'Weekly Holiday', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(67, '', NULL, 0, 'Holiday', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(68, '', NULL, 0, 'Leave Type', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(69, '', NULL, 0, 'Leave Generate', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(70, '', NULL, 0, 'Leave Approval', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(71, '', NULL, 0, 'Leave Application', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(72, '', NULL, 0, 'Monthly Attendance', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(73, '', NULL, 0, 'Missing Attendance', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(74, '', NULL, 0, 'Quarter', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(75, '', NULL, 0, 'Subtype', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(76, '', NULL, 0, 'Debit Voucher', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(77, '', 76, 0, 'Debit Voucher Reverse', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(78, '', NULL, 0, 'Credit Voucher', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(79, '', 78, 0, 'Credit Voucher Reverse', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(80, '', NULL, 0, 'Contra Voucher', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(81, '', 80, 0, 'Contra Voucher Reverse', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(82, '', NULL, 0, 'Journal Voucher', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(83, '', 82, 0, 'Journal Voucher Reverse', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(84, '', NULL, 0, 'Attendance Details Report', NULL, NULL, '2024-05-12 22:35:46', '2024-05-12 22:35:46', NULL),
(85, '', NULL, 0, 'Budget Allocation', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(86, '', NULL, 0, 'Budget Request', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(87, '', NULL, 0, 'Budget Allocation Report', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(88, '', NULL, 0, 'Budget Allocation Menu', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(89, '', NULL, 0, 'Factory Reset', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(90, '', NULL, 0, 'Stock Management', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(91, '', NULL, 0, 'Opening Stock', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(92, '', NULL, 0, 'Stock Adjustment', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(93, '', NULL, 0, 'Stock Report', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(94, '', NULL, 0, 'Supplier Wise Sale Profit', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(95, '', NULL, 0, 'Sub Departments', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(96, '', NULL, 0, 'Loan Disburse Report', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(97, '', NULL, 0, 'Employee Loan', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(98, '', NULL, 0, 'Employee Attendance', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(99, '', NULL, 0, 'HRM Setup', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(100, '', NULL, 0, 'Backup And Reset', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(101, '', NULL, 0, 'Notice', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(102, '', NULL, 0, 'Reward Points', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(103, '', 102, 0, 'Point Settings', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(104, '', 102, 0, 'Point Categories', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(105, '', 102, 0, 'Management Points', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(106, '', 102, 0, 'Collaborative Points', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(107, '', 102, 0, 'Employee Points', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(108, '', 102, 0, 'Attendance Points', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(109, '', NULL, 0, 'Recruitment', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(110, '', 109, 0, 'Candidate List', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(111, '', 109, 0, 'Candidate Shortlist', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(112, '', 109, 0, 'Interview', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(113, '', 109, 0, 'Candidate Selection', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(114, '', NULL, 0, 'Project Management', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(115, '', 114, 0, 'Clients', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(116, '', 114, 0, 'Projects', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(117, '', 114, 0, 'Task', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(118, '', 114, 0, 'Sprint', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(119, '', 114, 0, 'Manage Tasks', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(120, '', 114, 0, 'Reports', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(121, '', 114, 0, 'Project Lists', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(122, '', 114, 0, 'Team Member', NULL, NULL, '2024-05-12 22:35:47', '2024-05-12 22:35:47', NULL),
(123, '', NULL, 0, 'Procurement', NULL, NULL, '2024-05-12 22:35:48', '2024-05-12 22:35:48', NULL),
(124, '', 123, 0, 'Request', NULL, NULL, '2024-05-12 22:35:48', '2024-05-12 22:35:48', NULL),
(125, '', 123, 0, 'Quotation', NULL, NULL, '2024-05-12 22:35:48', '2024-05-12 22:35:48', NULL),
(126, '', 123, 0, 'Bid Analysis', NULL, NULL, '2024-05-12 22:35:48', '2024-05-12 22:35:48', NULL),
(127, '', 123, 0, 'Purchase Order', NULL, NULL, '2024-05-12 22:35:48', '2024-05-12 22:35:48', NULL),
(128, '', 123, 0, 'Good Received', NULL, NULL, '2024-05-12 22:35:48', '2024-05-12 22:35:48', NULL),
(129, '', 123, 0, 'Vendors', NULL, NULL, '2024-05-12 22:35:48', '2024-05-12 22:35:48', NULL),
(130, '', 123, 0, 'Committees', NULL, NULL, '2024-05-12 22:35:48', '2024-05-12 22:35:48', NULL),
(131, '', 123, 0, 'Units', NULL, NULL, '2024-05-12 22:35:48', '2024-05-12 22:35:48', NULL),
(132, '', NULL, 0, 'Adhoc Report', NULL, NULL, '2024-05-12 22:35:48', '2024-05-12 22:35:48', NULL),
(133, '', NULL, 0, 'Employee Performance', NULL, NULL, '2024-05-12 22:35:48', '2024-05-12 22:35:48', NULL),
(134, '', NULL, 0, 'Messages', NULL, NULL, '2024-05-12 22:35:48', '2024-05-12 22:35:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pictures`
--

CREATE TABLE `pictures` (
  `id` bigint UNSIGNED NOT NULL,
  `imageable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `imageable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mime_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `febicon` text COLLATE utf8mb4_unicode_ci,
  `thumbnail` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '0=Inactive , 1=Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pm_clients`
--

CREATE TABLE `pm_clients` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'This will be used as the client_id, as previously client_id was primary key',
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pm_employee_projects`
--

CREATE TABLE `pm_employee_projects` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pm_projects`
--

CREATE TABLE `pm_projects` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'This will be used as the project_id, as previously project_is was separate column',
  `uuid` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_parent_project_id` int DEFAULT '0' COMMENT 'if create any new version of existing project. it will always remain the first parent id.',
  `second_parent_project_id` int DEFAULT '0' COMMENT 'it will use for backlogs task transfer.',
  `version_no` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '''1''' COMMENT 'It will increment always, after creating new version, otherwise always 1',
  `project_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project_lead` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approximate_tasks` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complete_tasks` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL COMMENT 'when the first sprint is started of any project',
  `project_start_date` date DEFAULT NULL COMMENT 'On project creation, this date will be defined',
  `close_date` date DEFAULT NULL COMMENT 'when project is being closed from project update.',
  `project_duration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `completed_days` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'days passed from start date of the project.',
  `project_summary` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_completed` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '''0''' COMMENT 'can complete forcefully or manually be completed',
  `project_reward_point` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '''0''' COMMENT 'this point will be given to all the employee of this project',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pm_sprints`
--

CREATE TABLE `pm_sprints` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'This will be used as the sprint_id, as previously sprint_id was primary key',
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'under a project',
  `sprint_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'in days',
  `start_date` date DEFAULT NULL,
  `close_date` date DEFAULT NULL,
  `sprint_goal` text COLLATE utf8mb4_unicode_ci,
  `is_finished` int NOT NULL DEFAULT '0',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pm_tasks_list`
--

CREATE TABLE `pm_tasks_list` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'This will be used as the task_id, as previously task_id was primary key',
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'under a project',
  `sprint_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `project_lead` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Reporter of the project',
  `employee_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Team members',
  `priority` int DEFAULT NULL COMMENT 'high = 2 or 1 = medium or low = 0',
  `attachment` text COLLATE utf8mb4_unicode_ci,
  `task_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT '1' COMMENT 'to do =1 , in progress = 2 or done = 3',
  `is_task` int DEFAULT '0' COMMENT 'if 0 remain in backlogs else show in task',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `point_attendances`
--

CREATE TABLE `point_attendances` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `in_time` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `point` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_date` date DEFAULT NULL COMMENT 'attendance date',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `point_categories`
--

CREATE TABLE `point_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `point_category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `point_collaboratives`
--

CREATE TABLE `point_collaboratives` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `point_shared_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Employee shared point',
  `point_shared_with` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Employee received point',
  `reason` text COLLATE utf8mb4_unicode_ci,
  `point` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `point_date` date DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `point_management`
--

CREATE TABLE `point_management` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `point_category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `point` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `point_settings`
--

CREATE TABLE `point_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `general_point` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Maximum limit for collaborative points',
  `attendance_point` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attendance_start` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attendance_end` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `collaborative_start` date DEFAULT NULL,
  `collaborative_end` date DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position_details` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `uuid`, `position_name`, `position_details`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '508bdfd7-9b40-4091-a4f9-b95de1ff6bb4', 'Asst. Officer', NULL, 1, 39, NULL, '2023-04-10 20:08:33', '2023-04-10 20:08:33', NULL),
(2, '8de9c475-5dfe-4b90-8820-bf0583d4e914', 'Asst. Manager', NULL, 1, 39, NULL, '2023-04-10 20:08:50', '2023-04-10 20:08:50', NULL),
(3, 'e68d15aa-640b-4980-9b95-ddef0f3fca56', 'Project Manager', NULL, 1, 39, NULL, '2023-04-10 20:09:04', '2023-04-10 20:09:04', NULL),
(4, 'a799dd6b-fcb9-4be2-95ee-75e33b2d200f', 'edrt', 'ertret', 1, 39, NULL, '2023-09-06 23:14:04', '2023-09-06 23:14:21', '2023-09-06 23:14:21'),
(5, 'ced08b2b-f073-4732-a61a-acb8e275c9ca', 'rtretyertyerty', 'sdrgsdrg', 1, 39, NULL, '2023-09-06 23:14:28', '2023-09-06 23:14:31', '2023-09-06 23:14:31');

-- --------------------------------------------------------

--
-- Table structure for table `prefixes`
--

CREATE TABLE `prefixes` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_requisition_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_received_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_return_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale_quotation_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale_invoice_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale_draft_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale_return_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock_adjustment_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wastage_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_invoice_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transfer_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `procurement_bid_analyses`
--

CREATE TABLE `procurement_bid_analyses` (
  `id` bigint UNSIGNED NOT NULL,
  `quotation_id` bigint UNSIGNED NOT NULL,
  `create_date` date DEFAULT NULL,
  `sba_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` text COLLATE utf8mb4_unicode_ci,
  `attachment` text COLLATE utf8mb4_unicode_ci,
  `total` double NOT NULL DEFAULT '0',
  `pdf_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `procurement_committees`
--

CREATE TABLE `procurement_committees` (
  `id` bigint UNSIGNED NOT NULL,
  `bid_id` bigint UNSIGNED DEFAULT NULL COMMENT 'When selecting in bid analysis',
  `bid_committee_id` bigint UNSIGNED DEFAULT NULL COMMENT 'When selecting in bid analysis',
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature` text COLLATE utf8mb4_unicode_ci,
  `date` date DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `procurement_goods_receiveds`
--

CREATE TABLE `procurement_goods_receiveds` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_order_id` bigint UNSIGNED DEFAULT NULL,
  `acc_coa_id` bigint UNSIGNED DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `vendor_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `invoice_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_quantity` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `grand_total` double DEFAULT NULL,
  `received_by_signature` text COLLATE utf8mb4_unicode_ci,
  `received_by_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `received_by_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voucher_id` bigint UNSIGNED DEFAULT NULL COMMENT 'id from acc_voucher table',
  `pdf_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `procurement_purchase_orders`
--

CREATE TABLE `procurement_purchase_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `goods_received_id` bigint UNSIGNED DEFAULT NULL COMMENT 'After received the goods id will fill here',
  `created_date` date DEFAULT NULL,
  `quotation_id` bigint UNSIGNED DEFAULT NULL,
  `vendor_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'vendor or company',
  `location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `total` double DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `grand_total` double DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `authorizer_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorizer_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorizer_signature` text COLLATE utf8mb4_unicode_ci,
  `authorizer_date` date DEFAULT NULL,
  `pdf_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `procurement_quotations`
--

CREATE TABLE `procurement_quotations` (
  `id` bigint UNSIGNED NOT NULL,
  `request_id` bigint UNSIGNED NOT NULL,
  `bid_analysis_id` bigint UNSIGNED DEFAULT NULL COMMENT 'After using this quote in Bid, the bid id will fill here',
  `purchase_order_id` bigint UNSIGNED DEFAULT NULL COMMENT 'After using this quote in purchase order, the purchase id will fill here',
  `company_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'vendor named as company',
  `vendor_id` bigint UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pin_or_equivalent` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expected_delivery_date` date DEFAULT NULL,
  `delivery_place` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature` text COLLATE utf8mb4_unicode_ci,
  `total` double NOT NULL DEFAULT '0',
  `pdf_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `procurement_requests`
--

CREATE TABLE `procurement_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `serial` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `department_id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `request_reason` text COLLATE utf8mb4_unicode_ci,
  `expected_start_date` date DEFAULT NULL,
  `expected_end_date` date DEFAULT NULL,
  `is_approve` tinyint NOT NULL DEFAULT '0' COMMENT 'Check request is approved or not',
  `approval_reason` text COLLATE utf8mb4_unicode_ci COMMENT 'Reason for approval',
  `is_quoted` tinyint NOT NULL DEFAULT '0' COMMENT '0= not quoted , 1 = quoted',
  `pdf_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `procurement_request_items`
--

CREATE TABLE `procurement_request_items` (
  `id` bigint UNSIGNED NOT NULL,
  `request_id` bigint UNSIGNED NOT NULL COMMENT 'id of request, quotation, bid analysis, purchase order, or goods received form',
  `item_type` int NOT NULL DEFAULT '1' COMMENT 'type 1 = request, 2 = quote, 3 = bid, 4 = purchase order, 5 == goods received',
  `company` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `material_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_id` bigint UNSIGNED NOT NULL,
  `quantity` double NOT NULL DEFAULT '0',
  `unit_price` double NOT NULL DEFAULT '0',
  `total_price` double NOT NULL DEFAULT '0',
  `choosing_reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `procurement_vendors`
--

CREATE TABLE `procurement_vendors` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` int DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip` int DEFAULT NULL,
  `previous_balance` double DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reward_points`
--

CREATE TABLE `reward_points` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'employee id',
  `attendance` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'attendance points',
  `management` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'management points',
  `collaborative` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'collaborative points',
  `total` bigint UNSIGNED DEFAULT NULL,
  `date` date DEFAULT NULL COMMENT 'pointing date',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'web', '2024-05-12 22:35:45', '2024-05-12 22:35:45'),
(2, 'Employee', 'web', '2024-05-12 22:35:45', '2024-05-12 22:35:45');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
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
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(62, 1),
(63, 1),
(64, 1),
(65, 1),
(66, 1),
(67, 1),
(68, 1),
(69, 1),
(70, 1),
(71, 1),
(72, 1),
(73, 1),
(74, 1),
(75, 1),
(76, 1),
(77, 1),
(78, 1),
(79, 1),
(80, 1),
(81, 1),
(82, 1),
(83, 1),
(84, 1),
(85, 1),
(86, 1),
(87, 1),
(88, 1),
(89, 1),
(90, 1),
(91, 1),
(92, 1),
(93, 1),
(94, 1),
(95, 1),
(96, 1),
(97, 1),
(98, 1),
(99, 1),
(100, 1),
(101, 1),
(102, 1),
(103, 1),
(104, 1),
(105, 1),
(106, 1),
(107, 1),
(108, 1),
(109, 1),
(110, 1),
(111, 1),
(112, 1),
(113, 1),
(114, 1),
(115, 1),
(116, 1),
(117, 1),
(118, 1),
(119, 1),
(120, 1),
(121, 1),
(122, 1),
(123, 1),
(124, 1),
(125, 1),
(126, 1),
(127, 1),
(128, 1),
(129, 1),
(130, 1),
(131, 1),
(132, 1),
(133, 1),
(134, 1),
(135, 1),
(136, 1),
(137, 1),
(138, 1),
(139, 1),
(140, 1),
(141, 1),
(142, 1),
(143, 1),
(144, 1),
(145, 1),
(146, 1),
(147, 1),
(148, 1),
(149, 1),
(150, 1),
(151, 1),
(152, 1),
(153, 1),
(154, 1),
(155, 1),
(156, 1),
(157, 1),
(158, 1),
(159, 1),
(160, 1),
(161, 1),
(162, 1),
(163, 1),
(164, 1),
(165, 1),
(166, 1),
(167, 1),
(168, 1),
(169, 1),
(170, 1),
(171, 1),
(172, 1),
(173, 1),
(174, 1),
(175, 1),
(176, 1),
(177, 1),
(178, 1),
(179, 1),
(180, 1),
(181, 1),
(182, 1),
(183, 1),
(184, 1),
(185, 1),
(186, 1),
(187, 1),
(188, 1),
(189, 1),
(190, 1),
(191, 1),
(192, 1),
(193, 1),
(194, 1),
(195, 1),
(196, 1),
(197, 1),
(198, 1),
(199, 1),
(200, 1),
(201, 1),
(202, 1),
(203, 1),
(204, 1),
(205, 1),
(206, 1),
(207, 1),
(208, 1),
(209, 1),
(210, 1),
(211, 1),
(212, 1),
(213, 1),
(214, 1),
(215, 1),
(216, 1),
(217, 1),
(218, 1),
(219, 1),
(220, 1),
(221, 1),
(222, 1),
(223, 1),
(224, 1),
(225, 1),
(226, 1),
(227, 1),
(228, 1),
(229, 1),
(230, 1),
(231, 1),
(232, 1),
(233, 1),
(234, 1),
(235, 1),
(236, 1),
(237, 1),
(238, 1),
(239, 1),
(240, 1),
(241, 1),
(242, 1),
(243, 1),
(244, 1),
(245, 1),
(246, 1),
(247, 1),
(248, 1),
(249, 1),
(250, 1),
(251, 1),
(252, 1),
(253, 1),
(254, 1),
(255, 1),
(256, 1),
(257, 1),
(258, 1),
(259, 1),
(260, 1),
(261, 1),
(262, 1),
(263, 1),
(264, 1),
(265, 1),
(266, 1),
(267, 1),
(268, 1),
(269, 1),
(270, 1),
(271, 1),
(272, 1),
(273, 1),
(274, 1),
(275, 1),
(276, 1),
(277, 1),
(278, 1),
(279, 1),
(280, 1),
(281, 1),
(282, 1),
(283, 1),
(284, 1),
(285, 1),
(286, 1),
(287, 1),
(288, 1),
(289, 1),
(290, 1),
(291, 1),
(292, 1),
(293, 1),
(294, 1),
(295, 1),
(296, 1),
(297, 1),
(298, 1),
(299, 1),
(300, 1),
(301, 1),
(302, 1),
(303, 1),
(304, 1),
(305, 1),
(306, 1),
(307, 1),
(308, 1),
(309, 1),
(310, 1),
(311, 1),
(312, 1),
(313, 1),
(314, 1),
(315, 1),
(316, 1),
(317, 1),
(318, 1),
(319, 1),
(320, 1),
(321, 1),
(322, 1),
(323, 1),
(324, 1),
(325, 1),
(326, 1),
(327, 1),
(328, 1),
(329, 1),
(330, 1),
(331, 1),
(332, 1),
(333, 1),
(334, 1),
(335, 1),
(336, 1),
(337, 1),
(338, 1),
(339, 1),
(340, 1),
(341, 1),
(342, 1),
(343, 1),
(344, 1),
(345, 1),
(346, 1),
(347, 1),
(348, 1),
(349, 1),
(350, 1),
(351, 1),
(352, 1),
(353, 1),
(354, 1),
(355, 1),
(356, 1),
(357, 1),
(358, 1),
(359, 1),
(360, 1),
(361, 1),
(362, 1),
(363, 1),
(364, 1),
(365, 1),
(366, 1),
(367, 1),
(368, 1),
(369, 1),
(370, 1),
(371, 1),
(372, 1),
(373, 1),
(374, 1),
(375, 1),
(376, 1),
(377, 1),
(378, 1),
(379, 1),
(380, 1),
(381, 1),
(382, 1),
(383, 1),
(384, 1),
(385, 1),
(386, 1),
(387, 1),
(388, 1),
(389, 1),
(390, 1),
(391, 1),
(392, 1),
(393, 1),
(394, 1),
(395, 1),
(396, 1),
(397, 1),
(398, 1),
(399, 1),
(400, 1),
(401, 1),
(402, 1),
(403, 1),
(404, 1),
(405, 1),
(406, 1),
(407, 1),
(408, 1),
(409, 1),
(410, 1),
(411, 1),
(412, 1),
(413, 1),
(414, 1),
(415, 1),
(416, 1),
(417, 1),
(418, 1),
(419, 1),
(420, 1),
(421, 1),
(422, 1),
(423, 1),
(424, 1),
(425, 1),
(426, 1),
(427, 1),
(428, 1),
(429, 1),
(430, 1),
(431, 1),
(432, 1),
(433, 1),
(434, 1),
(435, 1),
(436, 1),
(437, 1),
(438, 1),
(439, 1),
(440, 1),
(441, 1),
(442, 1),
(443, 1),
(444, 1),
(445, 1),
(446, 1),
(447, 1),
(448, 1),
(449, 1),
(450, 1),
(451, 1),
(452, 1),
(453, 1),
(454, 1),
(455, 1),
(456, 1),
(457, 1),
(458, 1),
(459, 1),
(460, 1),
(461, 1),
(462, 1),
(463, 1),
(464, 1),
(465, 1),
(466, 1),
(467, 1),
(468, 1),
(469, 1),
(470, 1),
(471, 1),
(472, 1),
(473, 1),
(474, 1),
(475, 1),
(476, 1),
(477, 1),
(478, 1),
(479, 1),
(480, 1),
(481, 1),
(482, 1),
(483, 1),
(484, 1),
(485, 1),
(486, 1),
(487, 1),
(488, 1),
(489, 1),
(490, 1),
(491, 1),
(492, 1),
(493, 1),
(494, 1),
(495, 1),
(496, 1),
(497, 1),
(498, 1),
(499, 1),
(500, 1),
(501, 1),
(502, 1),
(503, 1),
(504, 1),
(505, 1),
(506, 1),
(507, 1),
(508, 1),
(509, 1),
(510, 1),
(511, 1),
(512, 1),
(513, 1),
(514, 1),
(515, 1),
(516, 1),
(517, 1),
(518, 1),
(519, 1),
(520, 1),
(521, 1),
(522, 1),
(523, 1),
(524, 1),
(525, 1),
(526, 1),
(527, 1),
(528, 1),
(529, 1),
(530, 1);

-- --------------------------------------------------------

--
-- Table structure for table `salary_advances`
--

CREATE TABLE `salary_advances` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `salary_month` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double(8,2) NOT NULL,
  `release_amount` double(8,2) DEFAULT '0.00',
  `paid` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salary_generates`
--

CREATE TABLE `salary_generates` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `loan_id` bigint UNSIGNED DEFAULT NULL,
  `salary_advanced_id` bigint UNSIGNED DEFAULT NULL,
  `salary_month_year` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tin_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_attendance` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_count` double(15,2) NOT NULL,
  `attendance_allowance` double(15,2) DEFAULT NULL,
  `gross` double(15,2) NOT NULL,
  `basic` double(15,2) NOT NULL,
  `transport` double(15,2) DEFAULT NULL,
  `total_allowance` double(15,2) DEFAULT NULL,
  `total_deduction` double(15,2) DEFAULT NULL,
  `gross_salary` double(15,2) NOT NULL,
  `income_tax` double(15,2) DEFAULT NULL,
  `soc_sec_npf_tax` double(15,2) DEFAULT NULL,
  `employer_contribution` double(15,2) DEFAULT NULL,
  `icf_amount` double(15,2) DEFAULT NULL,
  `loan_deduct` double(15,2) DEFAULT NULL,
  `salary_advance` double(15,2) DEFAULT NULL,
  `leave_without_pay` double(15,2) DEFAULT NULL,
  `provident_fund` double(15,2) DEFAULT NULL,
  `stamp` double(15,2) DEFAULT NULL,
  `net_salary` double(15,2) NOT NULL,
  `medical_benefit` double(15,2) DEFAULT NULL,
  `family_benefit` double(15,2) DEFAULT NULL,
  `transportation_benefit` double(15,2) DEFAULT NULL,
  `other_benefit` double(15,2) DEFAULT NULL,
  `normal_working_hrs_month` double DEFAULT NULL,
  `actual_working_hrs_month` double DEFAULT NULL,
  `hourly_rate_basic` double(15,2) DEFAULT NULL,
  `hourly_rate_trasport_allowance` double(15,2) DEFAULT NULL,
  `basic_salary_pro_rated` double(15,2) DEFAULT NULL,
  `transport_allowance_pro_rated` double(15,2) DEFAULT NULL,
  `basic_transport_allowance` double(15,2) DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salary_sheet_generates`
--

CREATE TABLE `salary_sheet_generates` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `generate_date` datetime NOT NULL,
  `generate_by_id` bigint UNSIGNED DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_date` datetime DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `setup_rules`
--

CREATE TABLE `setup_rules` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `is_percent` tinyint(1) NOT NULL DEFAULT '0',
  `on_gross` tinyint(1) NOT NULL DEFAULT '0',
  `on_basic` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `setup_rules`
--

INSERT INTO `setup_rules` (`id`, `uuid`, `name`, `type`, `amount`, `start_time`, `end_time`, `is_percent`, `on_gross`, `on_basic`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'fe26526e-045e-4698-b043-e2bf76324949', 'Basic', 'basic', NULL, NULL, NULL, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
(2, '91d24cd0-eaa7-4615-b1c0-79148a370400', 'Allowance', 'allowance', NULL, NULL, NULL, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
(3, '9021595d-2402-484b-bc32-7bd33d2a068c', 'Deduction', 'deduction', NULL, NULL, NULL, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
(4, '4fdbbce2-db84-4607-825a-0f2b564d2212', 'Bonus', 'bonus', NULL, NULL, NULL, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
(5, 'c97d4b4c-9e32-494f-b908-b33da5aae3d3', 'Regular Working days', 'time', NULL, '09:00:00', '18:00:00', 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
(6, '3781622c-51b4-4d88-b691-5934f282e65f', 'Winter', 'time', NULL, '10:00:00', '19:00:00', 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `skill_types`
--

CREATE TABLE `skill_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `skill_types`
--

INSERT INTO `skill_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(2, 'Non-Technical', '2023-04-12 22:56:32', '2023-04-12 22:56:32'),
(50, 'Non Technical', '2023-04-17 17:56:48', '2023-04-17 17:56:48'),
(51, 'PHP', '2023-04-25 21:02:19', '2023-04-25 21:02:19'),
(52, 'Programing', '2023-04-25 23:33:29', '2023-04-25 23:33:29');

-- --------------------------------------------------------

--
-- Table structure for table `tax_calculations`
--

CREATE TABLE `tax_calculations` (
  `id` bigint UNSIGNED NOT NULL,
  `min` double NOT NULL,
  `max` double NOT NULL,
  `add_amount` double DEFAULT NULL,
  `tax_percent` double NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint UNSIGNED NOT NULL,
  `unit` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_type_id` bigint UNSIGNED NOT NULL,
  `full_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attempt` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recovery_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `token_id` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_reset_token` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

CREATE TABLE `user_types` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_type_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_types`
--

INSERT INTO `user_types` (`id`, `uuid`, `user_type_title`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '', 'Admin', 1, NULL, NULL, '2024-05-12 22:35:48', '2024-05-12 22:35:48', NULL),
(2, '', 'Employee', 1, NULL, NULL, '2024-05-12 22:35:48', '2024-05-12 22:35:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `week_holidays`
--

CREATE TABLE `week_holidays` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dayname` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `week_holidays`
--

INSERT INTO `week_holidays` (`id`, `uuid`, `dayname`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '2a9f09ae-41y8-4a49-83db-e3cf4f12k14d', 'saturday,sunday', 39, 39, '2022-09-28 21:51:48', '2022-09-28 21:51:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `zkts`
--

CREATE TABLE `zkts` (
  `id` bigint UNSIGNED NOT NULL,
  `device_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `zkts`
--

INSERT INTO `zkts` (`id`, `device_id`, `ip_address`, `status`, `created_at`, `updated_at`) VALUES
(1, '1', '192.168.1.94', 1, '2023-06-18 21:24:54', '2023-06-18 21:24:54'),
(2, 'Et incididunt atque', 'In ut quam est non', 0, '2024-04-16 16:50:06', '2024-04-16 16:50:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acc_coas`
--
ALTER TABLE `acc_coas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_monthly_balances`
--
ALTER TABLE `acc_monthly_balances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_opening_balances`
--
ALTER TABLE `acc_opening_balances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_predefine_accounts`
--
ALTER TABLE `acc_predefine_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_quarters`
--
ALTER TABLE `acc_quarters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_subcodes`
--
ALTER TABLE `acc_subcodes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_subtypes`
--
ALTER TABLE `acc_subtypes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_transactions`
--
ALTER TABLE `acc_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_types`
--
ALTER TABLE `acc_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_vouchers`
--
ALTER TABLE `acc_vouchers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_voucher_types`
--
ALTER TABLE `acc_voucher_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject` (`subject_type`,`subject_id`),
  ADD KEY `causer` (`causer_type`,`causer_id`),
  ADD KEY `activity_log_log_name_index` (`log_name`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `apply_leaves`
--
ALTER TABLE `apply_leaves`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appsettings`
--
ALTER TABLE `appsettings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attn_checkin_checkouts`
--
ALTER TABLE `attn_checkin_checkouts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attn_user_infos`
--
ALTER TABLE `attn_user_infos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `awards`
--
ALTER TABLE `awards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_infos`
--
ALTER TABLE `bank_infos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `candidate_education`
--
ALTER TABLE `candidate_education`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `candidate_information`
--
ALTER TABLE `candidate_information`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `candidate_interviews`
--
ALTER TABLE `candidate_interviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `candidate_selections`
--
ALTER TABLE `candidate_selections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `candidate_shortlists`
--
ALTER TABLE `candidate_shortlists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `candidate_work_experiences`
--
ALTER TABLE `candidate_work_experiences`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `certificate_types`
--
ALTER TABLE `certificate_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doc_expired_settings`
--
ALTER TABLE `doc_expired_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `duty_types`
--
ALTER TABLE `duty_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_configs`
--
ALTER TABLE `email_configs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_academic_infos`
--
ALTER TABLE `employee_academic_infos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_allowence_deductions`
--
ALTER TABLE `employee_allowence_deductions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_docs`
--
ALTER TABLE `employee_docs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_files`
--
ALTER TABLE `employee_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_other_docs`
--
ALTER TABLE `employee_other_docs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_performances`
--
ALTER TABLE `employee_performances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_performances_employee_id_index` (`employee_id`);

--
-- Indexes for table `employee_performance_criterias`
--
ALTER TABLE `employee_performance_criterias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_performance_development_plans`
--
ALTER TABLE `employee_performance_development_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_performance_evaluations`
--
ALTER TABLE `employee_performance_evaluations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_performance_evaluation_types`
--
ALTER TABLE `employee_performance_evaluation_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_performance_key_goals`
--
ALTER TABLE `employee_performance_key_goals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_performance_types`
--
ALTER TABLE `employee_performance_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_performance_values`
--
ALTER TABLE `employee_performance_values`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_salary_types`
--
ALTER TABLE `employee_salary_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_types`
--
ALTER TABLE `employee_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `financial_years`
--
ALTER TABLE `financial_years`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `genders`
--
ALTER TABLE `genders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `langstrings`
--
ALTER TABLE `langstrings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `langstrvals`
--
ALTER TABLE `langstrvals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_types`
--
ALTER TABLE `leave_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_type_years`
--
ALTER TABLE `leave_type_years`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manual_attendances`
--
ALTER TABLE `manual_attendances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `marital_statuses`
--
ALTER TABLE `marital_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `password_settings`
--
ALTER TABLE `password_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pay_frequencies`
--
ALTER TABLE `pay_frequencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `per_menus`
--
ALTER TABLE `per_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pictures`
--
ALTER TABLE `pictures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pictures_imageable_type_imageable_id_index` (`imageable_type`,`imageable_id`);

--
-- Indexes for table `pm_clients`
--
ALTER TABLE `pm_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_employee_projects`
--
ALTER TABLE `pm_employee_projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_projects`
--
ALTER TABLE `pm_projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_sprints`
--
ALTER TABLE `pm_sprints`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_tasks_list`
--
ALTER TABLE `pm_tasks_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `point_attendances`
--
ALTER TABLE `point_attendances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `point_categories`
--
ALTER TABLE `point_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `point_collaboratives`
--
ALTER TABLE `point_collaboratives`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `point_management`
--
ALTER TABLE `point_management`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `point_settings`
--
ALTER TABLE `point_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prefixes`
--
ALTER TABLE `prefixes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `procurement_bid_analyses`
--
ALTER TABLE `procurement_bid_analyses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `procurement_committees`
--
ALTER TABLE `procurement_committees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `procurement_goods_receiveds`
--
ALTER TABLE `procurement_goods_receiveds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `procurement_purchase_orders`
--
ALTER TABLE `procurement_purchase_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `procurement_quotations`
--
ALTER TABLE `procurement_quotations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `procurement_requests`
--
ALTER TABLE `procurement_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `procurement_request_items`
--
ALTER TABLE `procurement_request_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `procurement_vendors`
--
ALTER TABLE `procurement_vendors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reward_points`
--
ALTER TABLE `reward_points`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `salary_advances`
--
ALTER TABLE `salary_advances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salary_generates`
--
ALTER TABLE `salary_generates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salary_sheet_generates`
--
ALTER TABLE `salary_sheet_generates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `setup_rules`
--
ALTER TABLE `setup_rules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skill_types`
--
ALTER TABLE `skill_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tax_calculations`
--
ALTER TABLE `tax_calculations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_contact_no_unique` (`contact_no`);

--
-- Indexes for table `user_types`
--
ALTER TABLE `user_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `week_holidays`
--
ALTER TABLE `week_holidays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zkts`
--
ALTER TABLE `zkts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acc_coas`
--
ALTER TABLE `acc_coas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `acc_monthly_balances`
--
ALTER TABLE `acc_monthly_balances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_opening_balances`
--
ALTER TABLE `acc_opening_balances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_predefine_accounts`
--
ALTER TABLE `acc_predefine_accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `acc_quarters`
--
ALTER TABLE `acc_quarters`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_subcodes`
--
ALTER TABLE `acc_subcodes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_subtypes`
--
ALTER TABLE `acc_subtypes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `acc_transactions`
--
ALTER TABLE `acc_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_types`
--
ALTER TABLE `acc_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_vouchers`
--
ALTER TABLE `acc_vouchers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_voucher_types`
--
ALTER TABLE `acc_voucher_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `apply_leaves`
--
ALTER TABLE `apply_leaves`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appsettings`
--
ALTER TABLE `appsettings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attn_checkin_checkouts`
--
ALTER TABLE `attn_checkin_checkouts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attn_user_infos`
--
ALTER TABLE `attn_user_infos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `awards`
--
ALTER TABLE `awards`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bank_infos`
--
ALTER TABLE `bank_infos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `candidate_education`
--
ALTER TABLE `candidate_education`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidate_information`
--
ALTER TABLE `candidate_information`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidate_interviews`
--
ALTER TABLE `candidate_interviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidate_selections`
--
ALTER TABLE `candidate_selections`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidate_shortlists`
--
ALTER TABLE `candidate_shortlists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidate_work_experiences`
--
ALTER TABLE `candidate_work_experiences`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `certificate_types`
--
ALTER TABLE `certificate_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=194;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `doc_expired_settings`
--
ALTER TABLE `doc_expired_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `duty_types`
--
ALTER TABLE `duty_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `email_configs`
--
ALTER TABLE `email_configs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_academic_infos`
--
ALTER TABLE `employee_academic_infos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_allowence_deductions`
--
ALTER TABLE `employee_allowence_deductions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_docs`
--
ALTER TABLE `employee_docs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_files`
--
ALTER TABLE `employee_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_other_docs`
--
ALTER TABLE `employee_other_docs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_performances`
--
ALTER TABLE `employee_performances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_performance_criterias`
--
ALTER TABLE `employee_performance_criterias`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `employee_performance_development_plans`
--
ALTER TABLE `employee_performance_development_plans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_performance_evaluations`
--
ALTER TABLE `employee_performance_evaluations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employee_performance_evaluation_types`
--
ALTER TABLE `employee_performance_evaluation_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employee_performance_key_goals`
--
ALTER TABLE `employee_performance_key_goals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_performance_types`
--
ALTER TABLE `employee_performance_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `employee_performance_values`
--
ALTER TABLE `employee_performance_values`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_salary_types`
--
ALTER TABLE `employee_salary_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_types`
--
ALTER TABLE `employee_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `financial_years`
--
ALTER TABLE `financial_years`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `genders`
--
ALTER TABLE `genders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `langstrings`
--
ALTER TABLE `langstrings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `langstrvals`
--
ALTER TABLE `langstrvals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `leave_type_years`
--
ALTER TABLE `leave_type_years`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manual_attendances`
--
ALTER TABLE `manual_attendances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `marital_statuses`
--
ALTER TABLE `marital_statuses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_settings`
--
ALTER TABLE `password_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pay_frequencies`
--
ALTER TABLE `pay_frequencies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=531;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `per_menus`
--
ALTER TABLE `per_menus`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `pictures`
--
ALTER TABLE `pictures`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_clients`
--
ALTER TABLE `pm_clients`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'This will be used as the client_id, as previously client_id was primary key';

--
-- AUTO_INCREMENT for table `pm_employee_projects`
--
ALTER TABLE `pm_employee_projects`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_projects`
--
ALTER TABLE `pm_projects`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'This will be used as the project_id, as previously project_is was separate column';

--
-- AUTO_INCREMENT for table `pm_sprints`
--
ALTER TABLE `pm_sprints`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'This will be used as the sprint_id, as previously sprint_id was primary key';

--
-- AUTO_INCREMENT for table `pm_tasks_list`
--
ALTER TABLE `pm_tasks_list`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'This will be used as the task_id, as previously task_id was primary key';

--
-- AUTO_INCREMENT for table `point_attendances`
--
ALTER TABLE `point_attendances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `point_categories`
--
ALTER TABLE `point_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `point_collaboratives`
--
ALTER TABLE `point_collaboratives`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `point_management`
--
ALTER TABLE `point_management`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `point_settings`
--
ALTER TABLE `point_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `prefixes`
--
ALTER TABLE `prefixes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `procurement_bid_analyses`
--
ALTER TABLE `procurement_bid_analyses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `procurement_committees`
--
ALTER TABLE `procurement_committees`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `procurement_goods_receiveds`
--
ALTER TABLE `procurement_goods_receiveds`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `procurement_purchase_orders`
--
ALTER TABLE `procurement_purchase_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `procurement_quotations`
--
ALTER TABLE `procurement_quotations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `procurement_requests`
--
ALTER TABLE `procurement_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `procurement_request_items`
--
ALTER TABLE `procurement_request_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `procurement_vendors`
--
ALTER TABLE `procurement_vendors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reward_points`
--
ALTER TABLE `reward_points`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `salary_advances`
--
ALTER TABLE `salary_advances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salary_generates`
--
ALTER TABLE `salary_generates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salary_sheet_generates`
--
ALTER TABLE `salary_sheet_generates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `setup_rules`
--
ALTER TABLE `setup_rules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `skill_types`
--
ALTER TABLE `skill_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `tax_calculations`
--
ALTER TABLE `tax_calculations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_types`
--
ALTER TABLE `user_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `week_holidays`
--
ALTER TABLE `week_holidays`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `zkts`
--
ALTER TABLE `zkts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
