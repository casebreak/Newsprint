-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2016 at 05:00 PM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 7.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `newsprint`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(10) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(60) NOT NULL,
  `fname` varchar(30) NOT NULL,
  `lname` varchar(40) NOT NULL,
  `street` varchar(40) NOT NULL,
  `city` varchar(40) NOT NULL,
  `state` char(2) NOT NULL,
  `zip` int(5) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `cardNum` varchar(20) NOT NULL,
  `expMonth` varchar(2) NOT NULL,
  `expYear` varchar(4) NOT NULL,
  `adminFlag` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `username`, `password`, `email`, `fname`, `lname`, `street`, `city`, `state`, `zip`, `phone`, `cardNum`, `expMonth`, `expYear`, `adminFlag`) VALUES
(32, 'ttocsnacnud', 'cef44b46f16ae8ecf664df4266ffdbf9', 'ttocsnacnud@gmail.com', 'Scott', 'Duncan', '1357 Magnolia Ave E', 'St Paul', 'MN', 55106, '19529940011', '3216549879879871', '09', '2019', 1),
(38, 'julian_chatterton', '5f4dcc3b5aa765d61d8327deb882cf99', 'hoof@nocookies.com', 'Julian', 'Chatterton', '1234 Doorknob Blvd.', 'Grantsburg', 'WI', 55489, '6514891185', '1234123412311158', '02', '2019', 0),
(39, 'dantheman', '865ec4dfdfe2963df6667b2ee5f50ab1', 'dan@dan.com', 'Dan', 'Theman', '1234 Five Lane', 'St Paul', 'MN', 55106, '9522221113', '1234123412341234', '02', '2019', 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderNum` int(10) NOT NULL,
  `orderDate` int(11) NOT NULL,
  `customer` varchar(50) NOT NULL,
  `products_InOrder` text NOT NULL,
  `totalPrice` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderNum`, `orderDate`, `customer`, `products_InOrder`, `totalPrice`, `status`) VALUES
(45, 1461424605, 'ttocsnacnud', '{"88":{"ID":"88","Name":"Batman - Court of Owls Vol. 1 TPB","Price":"15.99","Quantity":"1","Available":"33"}}', '15.99', 'Completed'),
(46, 1461448113, 'ttocsnacnud', '{"79":{"ID":"79","Name":"Ultimate Spider-Man #1","Price":"190.99","Quantity":"1","Available":"22"}}', '190.99', 'Pending'),
(48, 1461608719, 'julian_chatterton', '{"86":{"ID":"86","Name":"The Goon #1","Price":"72.49","Quantity":"2","Available":"13"},"88":{"ID":"88","Name":"Batman - Court of Owls Vol. 1 TPB","Price":"15.99","Quantity":"1","Available":"32"}}', '160.97', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) NOT NULL,
  `name` varchar(60) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` mediumtext NOT NULL,
  `cond` varchar(30) NOT NULL,
  `iname` varchar(40) NOT NULL,
  `filename` varchar(40) NOT NULL,
  `qty` int(11) NOT NULL,
  `category` text NOT NULL,
  `dateModified` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `cond`, `iname`, `filename`, `qty`, `category`, `dateModified`) VALUES
(79, 'Ultimate Spider-Man #1', '190.99', 'Features a revamped, more modern Spider-Man from 2000. Said to be pivotal in bringing comics ''back from the dead'' in the mid to late 90''s.        ', 'Excellent', 'Ultimate Spider-Man #1', 'ultimateSpiderMan_no1.jpg', 21, 'Spiderman, Used, Classic', 1461694924),
(80, 'Detective Comics #27', '389999.90', 'This issue of Detective Comics from 1939 introduced the "caped crusader" Batman.        ', 'Good', 'Detective Comics #27', 'detectiveComics_no27.jpg', 2, 'Batman, Used, Classic', 1461605464),
(81, 'Cerebus the Aardvark', '629.89', 'This is the longest graphic novel ever published. Creator drawn by Dave Sim back in 1977.    ', 'Excellent', 'Cerebus the Aardvark', 'cerebus.jpg', 3, 'TPB, Used, Classic', 1461605496),
(82, 'Zap Comix #1', '5699.99', 'Zap is likely the most well-known underground comics of all time. Released to the streets of San Francisco 1968, the cover shows Mr. Natural driving his little car, with a warning that this comic is "for adult intellectuals only."    ', 'Good', 'Zap Comix #1', 'zap_no1.jpg', 2, 'Graphic Novel, Used, GN', 1461606376),
(83, 'Amazing Spider-Man #129', '512.65', 'A new, dark and gritty superhero is out to get Spider-Man in 1974: the Punisher.        ', 'Excellent', 'Amazing Spider-Man #129', 'amazingSpiderMan_no129.jpg', 2, 'Spiderman, Used, Classic', 1461606338),
(84, 'Detective Comics #140', '5541.99', 'The first appearance of Edward Nigma aka The Riddler, who''d turn into one of Batman''s most interesting arch enemies.        ', 'Good', 'Detective Comics #140', 'detectiveComics_no140.jpg', 3, 'Used, Batman', 1461436716),
(85, 'Fantastic Four #1', '33000.00', 'The first appearance of the Fantastic Four from 1961, created by Stan Lee & Jack Kirby.    ', 'Excellent', 'Fantastic Four #1', 'fantasticFour_no1.jpg', 2, 'FF, Classic, Used', 1461606461),
(86, 'The Goon #1', '72.49', 'The Goon is a comic book series created by Eric Powell in 1999. The story is about the adventures of the Goon, a muscle-bound brawler with a paranormal slant.                                ', 'Excellent', 'The Goon #1', 'theGoon_no1.jpg', 11, 'Used, Goon', 1461606479),
(87, 'Amazing Fantasy #15', '41000.99', 'First appearance of Spider-Man in this Stan Lee inspired original.    ', 'Fair', 'Amazing Fantasy #15', 'amazingFantasy_no15.jpg', 3, 'Spiderman, Used, Classic', 1461606574),
(88, 'Batman - Court of Owls Vol. 1 TPB', '15.99', 'Following his ground-breaking, critically acclaimed run on Detective Comics, writer Scott Snyder (American Vampire) alongside artist Greg Capullo (Spawn) begins a new era of The Dark Knight as with the relaunch of Batman.', 'New', 'Court of Owls Vol. 1', 'courtofOwls.jpg', 31, 'Batman, TPB, New', 1461606594),
(92, 'asdf', '12.99', 'sdfadgasdf', 'New', 'asdasd', 'bsError.jpg', 2, 'Batman', 1462809388);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderNum`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderNum` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
