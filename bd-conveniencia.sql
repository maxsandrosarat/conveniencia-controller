INSERT INTO `categorias` (`id`, `nome`, `ativo`, `created_at`, `updated_at`) VALUES
	(1, 'Bebidas Ñ Alcoólicas', 1, '2021-10-20 17:28:26', '2021-10-20 17:28:26'),
	(2, 'Bebidas Alcoólicas', 1, '2021-10-20 17:28:35', '2021-10-20 17:28:35'),
	(3, 'Alimentos', 1, '2021-10-20 17:28:45', '2021-10-26 08:41:28');

INSERT INTO `pagamento_formas` (`id`, `descricao`, `juros`, `tipo_juros`, `valor_juros`, `ativo`, `created_at`, `updated_at`) VALUES
	(1, 'Cartão Débito', 0, NULL, 0.00, 1, '2021-10-26 10:56:48', '2021-10-26 11:30:00'),
	(2, 'Cartão Crédito', 1, 'porc', 0.30, 1, '2021-10-26 10:57:54', '2021-10-26 11:18:45'),
	(3, 'Dinheiro', 0, NULL, 0.00, 1, '2021-10-26 11:19:25', '2021-10-26 11:19:25'),
	(4, 'Pix', 0, NULL, 0.00, 1, '2021-10-26 11:19:32', '2021-10-26 11:19:32'),
	(5, 'Fiado', 1, 'fixo', 5.00, 1, '2021-10-26 11:19:32', '2021-10-26 11:19:32');

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `ativo`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Max Admin', 'msaratribeiro@gmail.com', NULL, '$2y$10$XIIfuo/GTe7o5bWPBQvRCuhrKK5bs0yWoUVajsXyuDhXIujdddQ5e', 1, NULL, '2021-10-20 13:06:05', '2021-10-20 13:06:05');
