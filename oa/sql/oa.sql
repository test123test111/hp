
--
-- 表的结构 `boardroom`
--

CREATE TABLE IF NOT EXISTS `boardroom` (
  `id` bigint(20) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `floor` varchar(20) NOT NULL DEFAULT '',
  `people_num` int(10) unsigned NOT NULL DEFAULT '0',
  `updated_time` int(10) unsigned NOT NULL DEFAULT '0',
  `created_time` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `boardroom_record`
--

CREATE TABLE IF NOT EXISTS `boardroom_record` (
  `id` bigint(20) NOT NULL,
  `room_id` bigint(20) unsigned NOT NULL,
  `room_name` varchar(100) NOT NULL DEFAULT '',
  `floor` varchar(10) NOT NULL DEFAULT '',
  `user_name` varchar(100) NOT NULL DEFAULT '',
  `user_id` varchar(100) NOT NULL DEFAULT '',
  `has_sign` tinyint(1) NOT NULL DEFAULT '0',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0',
  `created_time` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `boardroom`
--
ALTER TABLE `boardroom`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boardroom_record`
--
ALTER TABLE `boardroom_record`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `boardroom`
--
ALTER TABLE `boardroom`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `boardroom_record`
--
ALTER TABLE `boardroom_record`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
