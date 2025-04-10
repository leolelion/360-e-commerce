-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mar. 08 avr. 2025 à 00:16
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ShopCoDB`
--

-- --------------------------------------------------------

--
-- Structure de la table `Cart`
--

CREATE TABLE `Cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Categories`
--

CREATE TABLE `Categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Categories`
--

INSERT INTO `Categories` (`category_id`, `category_name`, `description`) VALUES
(1, 'Eggs', 'Fresh eggs from local farms'),
(2, 'Milk', 'Dairy milk and milk alternatives'),
(3, 'Baked Goods', 'Fresh bread, cakes, and pastries'),
(4, 'Flowers', 'Fresh flowers and bouquets'),
(5, 'Wine', 'Selection of fine wines'),
(6, 'Beer', 'Craft and imported beers'),
(7, 'Chicken', 'Fresh and frozen chicken products'),
(8, 'Beef', 'Quality beef cuts and products'),
(9, 'Home Goods', 'Household and cleaning supplies'),
(10, 'Health', 'Health and personal care products'),
(11, 'Other', 'Miscellaneous products'),
(12, 'Misc.', 'Various other items');

-- --------------------------------------------------------

--
-- Structure de la table `OrderItems`
--

CREATE TABLE `OrderItems` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `OrderItems`
--

INSERT INTO `OrderItems` (`order_item_id`, `order_id`, `product_id`, `quantity`, `unit_price`) VALUES
(1, 1, 1, 2, 4.99),
(2, 1, 3, 1, 3.99),
(3, 2, NULL, 1, 8.99),
(4, 3, 1, 3, 4.99),
(5, 3, 3, 2, 3.99);

-- --------------------------------------------------------

--
-- Structure de la table `Orders`
--

CREATE TABLE `Orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `order_status` enum('Pending','Shipped','Delivered','Cancelled') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Orders`
--

INSERT INTO `Orders` (`order_id`, `user_id`, `total_price`, `order_status`, `created_at`) VALUES
(1, NULL, 12.98, 'Pending', '2025-03-29 01:33:04'),
(2, 2, 8.99, 'Shipped', '2025-03-29 01:33:04'),
(3, 3, 18.97, 'Delivered', '2025-03-29 01:33:04');

-- --------------------------------------------------------

--
-- Structure de la table `Payments`
--

CREATE TABLE `Payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_method` enum('Credit Card','PayPal','Bank Transfer') DEFAULT NULL,
  `payment_status` enum('Pending','Completed','Failed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Products`
--

CREATE TABLE `Products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Products`
--

INSERT INTO `Products` (`product_id`, `name`, `description`, `price`, `stock_quantity`, `category_id`, `vendor_id`, `image_url`, `created_at`) VALUES
(1, 'Large Eggs', 'Farm fresh large eggs, dozen', 4.99, 100, 1, 1, 'assets/images/eggs.jpg', '2025-03-29 00:57:23'),
(2, 'Organic Eggs', 'Organic free-range eggs', 6.49, 75, 1, 2, 'assets/images/eggs.jpg', '2025-03-29 00:57:23'),
(3, 'Whole Wheat Bread', 'Freshly baked whole wheat bread', 3.99, 50, 3, 3, 'assets/images/bread.jpg', '2025-03-29 00:57:23'),
(4, 'Chocolate Cake', 'Rich chocolate cake, serves 8', 24.99, 20, 3, 3, 'assets/images/cake.png', '2025-03-29 00:57:23'),
(5, 'Baby Spinach', 'Fresh organic baby spinach', 3.49, 40, 11, 2, 'assets/images/spinach.jpg', '2025-03-29 00:57:23'),
(6, 'Raspberries', 'Fresh raspberries, 6oz package', 4.99, 30, 11, 2, 'assets/images/raspberries.jpg', '2025-03-29 00:57:23'),
(7, 'Blueberries (FRZ)', 'Frozen wild blueberries, 16oz', 5.99, 45, 11, 2, 'assets/images/blueberries.jpeg', '2025-03-29 00:57:23'),
(8, 'Whole Milk', 'Fresh whole milk, gallon', 3.79, 60, 2, 1, 'assets/images/milk.png', '2025-03-29 00:57:23'),
(9, 'Almond Milk', 'Unsweetened almond milk, half gallon', 3.29, 35, 2, 1, 'assets/images/milk.png', '2025-03-29 00:57:23'),
(10, 'Boneless Chicken Breast', 'Fresh boneless skinless chicken breast', 8.99, 25, 7, 4, 'assets/images/chicken.png', '2025-03-29 00:57:23'),
(11, 'Ground Beef', '80/20 ground beef, 1lb package', 5.99, 30, 8, 4, 'assets/images/beef.png', '2025-03-29 00:57:23');

-- --------------------------------------------------------

--
-- Structure de la table `Reviews`
--

CREATE TABLE `Reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Reviews`
--

INSERT INTO `Reviews` (`review_id`, `user_id`, `product_id`, `rating`, `comment`, `created_at`) VALUES
(1, 5, 1, 5, '444', '2025-03-30 03:08:46'),
(2, 5, 1, 4, 'great eggs!!', '2025-03-30 03:09:33'),
(3, 5, 1, 3, 'guyhvkjvblk', '2025-03-31 18:07:46');

-- --------------------------------------------------------

--
-- Structure de la table `Users`
--

CREATE TABLE `Users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Users`
--

INSERT INTO `Users` (`user_id`, `first_name`, `last_name`, `email`, `password`, `phone`, `address`, `profile_picture`, `role`, `created_at`) VALUES
(2, 'Jane', 'Smith', 'jane.smith@example.com', 'hashedpassword456', '555-5678', '456 Oak Avenue', 'profile2.jpg', 'user', '2025-03-29 01:33:04'),
(3, 'Admin', 'User', 'admin@example.com', 'adminpassword', '555-9999', '789 Maple Road', 'profile3.jpg', 'admin', '2025-03-29 01:33:04'),
(5, 'leo', 'leo', 'leo@leo.leo', '$2y$10$hdOF/jPL6IJbDkQWzpAfgOZRmIUme/4yxNw3EB3ZfZUKkGVMffcFy', NULL, NULL, NULL, 'user', '2025-03-29 03:17:08');

-- --------------------------------------------------------

--
-- Structure de la table `Vendors`
--

CREATE TABLE `Vendors` (
  `vendor_id` int(11) NOT NULL,
  `vendor_name` varchar(255) NOT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Vendors`
--

INSERT INTO `Vendors` (`vendor_id`, `vendor_name`, `contact_email`, `contact_phone`, `address`) VALUES
(1, 'Sunny Farms', 'contact@sunnyfarms.com', '123-456-7890', '123 Farm Road, Sunnyville, AB'),
(2, 'Green Valley Organics', 'info@greenvalleyorganics.com', '987-654-3210', '456 Organic Lane, Green Valley, BC'),
(3, 'Baker’s Delight', 'support@bakersdelight.com', '555-789-1234', '789 Bakery Street, Bakersfield, CA'),
(4, 'Farm Fresh Meats', 'sales@farmfreshmeats.com', '222-333-4444', '101 Meatpacking Ave, Freshville, TX');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Cart`
--
ALTER TABLE `Cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Index pour la table `Categories`
--
ALTER TABLE `Categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Index pour la table `OrderItems`
--
ALTER TABLE `OrderItems`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Index pour la table `Orders`
--
ALTER TABLE `Orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `orders_ibfk_1` (`user_id`);

--
-- Index pour la table `Payments`
--
ALTER TABLE `Payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `Products`
--
ALTER TABLE `Products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `vendor_id` (`vendor_id`);

--
-- Index pour la table `Reviews`
--
ALTER TABLE `Reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Index pour la table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `Vendors`
--
ALTER TABLE `Vendors`
  ADD PRIMARY KEY (`vendor_id`),
  ADD UNIQUE KEY `vendor_name` (`vendor_name`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Cart`
--
ALTER TABLE `Cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `Categories`
--
ALTER TABLE `Categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `OrderItems`
--
ALTER TABLE `OrderItems`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `Orders`
--
ALTER TABLE `Orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `Payments`
--
ALTER TABLE `Payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Products`
--
ALTER TABLE `Products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `Reviews`
--
ALTER TABLE `Reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `Vendors`
--
ALTER TABLE `Vendors`
  MODIFY `vendor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Cart`
--
ALTER TABLE `Cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `Products` (`product_id`);

--
-- Contraintes pour la table `OrderItems`
--
ALTER TABLE `OrderItems`
  ADD CONSTRAINT `orderitems_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `Orders` (`order_id`),
  ADD CONSTRAINT `orderitems_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `Products` (`product_id`);

--
-- Contraintes pour la table `Orders`
--
ALTER TABLE `Orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `Payments`
--
ALTER TABLE `Payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `Orders` (`order_id`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

--
-- Contraintes pour la table `Products`
--
ALTER TABLE `Products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `Categories` (`category_id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`vendor_id`) REFERENCES `Vendors` (`vendor_id`);

--
-- Contraintes pour la table `Reviews`
--
ALTER TABLE `Reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `Products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
