--
-- Table structure for table `tblproduct`
--

CREATE TABLE `tblproduct` (
  `id` int(8) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `image` text NOT NULL,
  `price` double(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblproduct`
--

INSERT INTO `tblproduct` (`id`, `name`, `code`, `image`, `price`) VALUES
(1, 'FinePix Pro2 3D Camera', '3DcAM01', 'camera.jpg', 15000000),
(2, 'EXP Portable Hard Drive', 'USB02', 'external-hard-drive.jpg', 800000),
(3, 'Luxury Ultra thin Wrist Watch', 'wristWear03', 'watch.jpg', 300000),
(4, 'XP 1155 Intel Core Laptop', 'LPN45', 'laptop.jpg', 8000000),
(5, 'iPhone 12 Pro', 'ip12p', 'ip12p.jpg', 15000000),
(6, 'iPhone 12 Max', 'ip12m', 'ip12m.jpg', 800000),
(7, 'Macbook Pro md103', 'mbpmd103', 'mbpmd103.jpg', 300000),
(8, 'Macbook Pro md101', 'mbpmd101', 'mbpmd101.jpg', 8000000),
(9, 'Macbook Air 2012', 'mba2012', 'mba2012.jpg', 15000000),
(10, 'Macbook Pro 2018', 'mbp2018', 'mbp2018.jpg', 800000),
(11, 'Macbook Air 2019', 'mba2019', 'mba2019.jpg', 150000000),
(12, 'Macbook Pro 2020', 'mbp2020', 'mbp2020.jpg', 8000000),
(13, 'Macbook Air 2020', 'mba2020', 'mba2020.jpg', 3000000),
(14, 'Macbook Pro 2010', 'mbp2010', 'mbp2010.jpg', 80000000),
(15, 'Macbook Air 2010', 'mba2010', 'mba2010.jpg', 150000000),
(16, 'Macbook Pro 2011', 'mbp2011', 'mbp2011.jpg', 8000000),
(17, 'Macbook Air 2011', 'mba2011', 'mba2011.jpg', 3000000),
(18, 'Macbook Pro 2016', 'mbp2016', 'mbp2016.jpg', 80000000),
(19, 'Macbook Pro 2017', 'mbb2017', 'mbb2017.jpg', 150000000),
(20, 'Macbook Air 2016', 'mba2016', 'mba2016.jpg', 8000000);

--
-- Indexes for table `tblproduct`
--
ALTER TABLE `tblproduct`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_code` (`code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblproduct`
--
ALTER TABLE `tblproduct`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;