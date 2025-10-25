-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 09-Jun-2025 às 21:52
-- Versão do servidor: 10.4.22-MariaDB
-- versão do PHP: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bodybliss`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `email_accounts`
--

CREATE TABLE `email_accounts` (
  `id` int(11) NOT NULL,
  `accountName` varchar(100) DEFAULT NULL,
  `smtpServer` varchar(255) DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `useSSL` tinyint(1) DEFAULT NULL,
  `timeout` int(11) DEFAULT NULL,
  `loginName` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `displayName` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `email_accounts`
--

INSERT INTO `email_accounts` (`id`, `accountName`, `smtpServer`, `port`, `useSSL`, `timeout`, `loginName`, `password`, `email`, `displayName`) VALUES
(1, 'Bodybliss', 'smtp.gmail.com', 465, 1, 30, 'bodybliss.company.business@gmail.com', 'jhya mspg kzll abow', 'bodybliss.company.business@gmail.com', 'BodyBliss');

-- --------------------------------------------------------

--
-- Estrutura da tabela `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category`, `image`) VALUES
(1, 'Bubble Cube Candle', 'Description of Product 1.', '12.99', 'velas', 'bubble_cube.webp'),
(2, 'Christmas Tree Candle', 'Description of Product 2.', '9.50', 'velas', 'christmas_tree.webp'),
(3, 'Massage Oil Candle', 'Description of Product 3.', '11.25', 'velas', 'massage_oil_candle.webp'),
(4, 'Hot Stone Massage', 'Description of Product 1.', '20.00', 'massagens', 'hot_stone_massage.webp'),
(5, 'Aromatherapy Candle Massage', 'Description of Product 2.', '35.00', 'massagens', 'aromatherapy_candle_massage.jpg'),
(6, 'Deep Relaxation Massage', 'Description of Product 3.', '40.00', 'massagens', 'deep_relaxation_massage.webp'),
(7, 'Incense Holder', 'Description of Product 1.', '15.00', 'jesmonite', 'incense_holder.jpg'),
(8, 'Candle Holder', 'Description of Product 2.', '13.50', 'jesmonite', 'jesmonite_suporte.jpg'),
(9, 'Decoration', 'Description of Product 3.', '17.75', 'jesmonite', 'decoration_jesmonite.webp');

-- --------------------------------------------------------

--
-- Estrutura da tabela `repository`
--

CREATE TABLE `repository` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `repository`
--

INSERT INTO `repository` (`id`, `user_id`, `file_name`, `upload_date`) VALUES
(1, 3, 'r3_681265e98dff3.jpg', '2025-04-30 18:03:21'),
(3, 12, 'r12_684322d080feb.jpg', '2025-06-06 17:18:08');

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `role` enum('client','vip','admin') NOT NULL DEFAULT 'client',
  `country` enum('Portugal','Brasil','Espanha','França','Alemanha') DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT 'nullprofile.jpg',
  `is_verified` tinyint(1) DEFAULT 0,
  `verify_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `gender`, `role`, `country`, `phone`, `profile_pic`, `is_verified`, `verify_token`, `created_at`) VALUES
(3, 'admin', 'admin@gmail.com', '$2y$10$SDo9tUwGibhFlLFDFV2EK.6mxgb6V.R.Z4EgV.OoWob9v9XBUiQdO', '', 'admin', 'Portugal', NULL, 'p3.jpg', 1, NULL, '2025-04-15 17:31:57'),
(12, 'vip', 'vip@gmail.com', '$2y$10$B2j0bHNqyRuJjG1KnI7GWORkkzknbzONJ0OK8xf9EIZeepcg7sbPO', '', 'vip', 'Portugal', NULL, 'p12.jpeg', 1, 'efb85ef58da610e7f74e47c0ba0fbb23', '2025-05-26 21:19:43'),
(42, 'Chico Gomes', 'chicogomes16.fg@gmail.com', '$2y$10$3SzyBmxjMRg67Lb/x4zOA.k6vVAwbEt3ub1mFWiTc.aQhvYp6gqr6', '', 'client', 'Portugal', '+351970007854', 'p42.jpg', 1, NULL, '2025-06-08 14:37:51');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Índices para tabela `email_accounts`
--
ALTER TABLE `email_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Índices para tabela `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `repository`
--
ALTER TABLE `repository`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT de tabela `email_accounts`
--
ALTER TABLE `email_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de tabela `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT de tabela `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `repository`
--
ALTER TABLE `repository`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `repository`
--
ALTER TABLE `repository`
  ADD CONSTRAINT `repository_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
