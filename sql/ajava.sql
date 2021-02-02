-- phpMyAdmin SQL Dump
-- version 4.6.6deb1+deb.cihar.com~xenial.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 01.06.2018 klo 01:25
-- Palvelimen versio: 5.7.22-0ubuntu0.16.04.1
-- PHP Version: 7.0.30-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `muotinurkka`
--

-- --------------------------------------------------------

--
-- Rakenne taululle `asiakas`
--

CREATE TABLE `asiakas` (
  `id` int(11) NOT NULL,
  `etunimi` varchar(30) CHARACTER SET utf8 NOT NULL,
  `sukunimi` varchar(30) CHARACTER SET utf8 NOT NULL,
  `sposti` text CHARACTER SET utf8,
  `puhnum` text CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Rakenne taululle `kategoria`
--

CREATE TABLE `kategoria` (
  `id` int(11) NOT NULL,
  `nimi` varchar(20) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Vedos taulusta `kategoria`
--

INSERT INTO `kategoria` (`id`, `nimi`) VALUES
(1, 'parturi'),
(2, 'tyhjäkategoria');

-- --------------------------------------------------------

--
-- Rakenne taululle `kayttajat`
--

CREATE TABLE `kayttajat` (
  `id` int(11) NOT NULL,
  `kayttajatunnus` varchar(50) NOT NULL,
  `salasana` text NOT NULL,
  `sposti` text NOT NULL,
  `role` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Vedos taulusta `kayttajat`
--

INSERT INTO `kayttajat` (`id`, `kayttajatunnus`, `salasana`, `sposti`, `role`) VALUES
(1, 'lprmn', '$2y$10$9nCEm9RR4Pi/wsSZoKvlWeGC2hUcDOXAJ8uQLmaPfXowqSDX8cbiG', 'forms.opastus@gmail.com', 'normal'),
(2, 'ajava', '$2y$10$92xJFg4idt1JjPnR4YeD8.Qld1zitclTbD3czb8UuSEma6U5F8LQ.', 'forms.opastus@gmail.com', 'admin');

-- --------------------------------------------------------

--
-- Rakenne taululle `palvelu`
--

CREATE TABLE `palvelu` (
  `id` int(11) NOT NULL,
  `nimi` varchar(50) CHARACTER SET utf8 NOT NULL,
  `kategoria` int(11) NOT NULL,
  `kesto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Vedos taulusta `palvelu`
--

INSERT INTO `palvelu` (`id`, `nimi`, `kategoria`, `kesto`) VALUES
(2, 'hiustenleikkuu', 1, 1800);

-- --------------------------------------------------------

--
-- Rakenne taululle `saatavilla`
--

CREATE TABLE `saatavilla` (
  `id` int(11) NOT NULL,
  `palvelu` int(11) NOT NULL,
  `tyontekija` int(11) NOT NULL,
  `toimipiste` int(11) NOT NULL,
  `paivamaara` date NOT NULL,
  `aika` time NOT NULL,
  `loppuu` time NOT NULL,
  `aikalukujono` int(11) NOT NULL,
  `varattu` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Vedos taulusta `saatavilla`
--

INSERT INTO `saatavilla` (`id`, `palvelu`, `tyontekija`, `toimipiste`, `paivamaara`, `aika`, `loppuu`, `aikalukujono`, `varattu`) VALUES
(7, 2, 1, 1, '2018-06-29', '16:00:00', '16:30:00', 1530277200, 'Ei'),
(28, 2, 1, 1, '2018-06-29', '16:30:00', '17:00:00', 1530279000, 'Ei');

-- --------------------------------------------------------

--
-- Rakenne taululle `toimipiste`
--

CREATE TABLE `toimipiste` (
  `id` int(11) NOT NULL,
  `nimi` varchar(50) NOT NULL,
  `osoite` varchar(50) CHARACTER SET utf8 NOT NULL,
  `puhnum` text NOT NULL,
  `postinumero` int(5) UNSIGNED ZEROFILL NOT NULL,
  `postitoimipaikka` varchar(50) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Vedos taulusta `toimipiste`
--

INSERT INTO `toimipiste` (`id`, `nimi`, `osoite`, `puhnum`, `postinumero`, `postitoimipaikka`) VALUES
(1, 'nimi1', 'osoite1', '+358 44 123 4567', 53900, 'kaupunki1'),
(2, 'nimi2', 'osoite2', '(050-443-80-13)', 01234, 'kaupunki2');

-- --------------------------------------------------------

--
-- Rakenne taululle `tyontekija`
--

CREATE TABLE `tyontekija` (
  `id` int(11) NOT NULL,
  `nimi` varchar(20) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Vedos taulusta `tyontekija`
--

INSERT INTO `tyontekija` (`id`, `nimi`) VALUES
(1, 'tyontekija1'),
(4, 'tyontekija2');

-- --------------------------------------------------------

--
-- Rakenne taululle `varaus`
--

CREATE TABLE `varaus` (
  `id` int(11) NOT NULL,
  `asiakas` int(11) NOT NULL,
  `palvelu` int(11) NOT NULL,
  `tyontekija` int(11) NOT NULL,
  `toimipiste` int(11) NOT NULL,
  `saatavillaID` int(11) NOT NULL,
  `paivamaara` date NOT NULL,
  `aika` time NOT NULL,
  `aikalukujono` int(11) NOT NULL,
  `lisatiedot` text CHARACTER SET utf8
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `asiakas`
--
ALTER TABLE `asiakas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategoria`
--
ALTER TABLE `kategoria`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kayttajat`
--
ALTER TABLE `kayttajat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `palvelu`
--
ALTER TABLE `palvelu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategoria` (`kategoria`);

--
-- Indexes for table `saatavilla`
--
ALTER TABLE `saatavilla`
  ADD PRIMARY KEY (`id`),
  ADD KEY `palvelu` (`palvelu`),
  ADD KEY `tyontekija` (`tyontekija`),
  ADD KEY `toimipiste` (`toimipiste`);

--
-- Indexes for table `toimipiste`
--
ALTER TABLE `toimipiste`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tyontekija`
--
ALTER TABLE `tyontekija`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `varaus`
--
ALTER TABLE `varaus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `palvelu` (`palvelu`),
  ADD KEY `tyontekija` (`tyontekija`),
  ADD KEY `toimipiste` (`toimipiste`),
  ADD KEY `asiakas` (`asiakas`),
  ADD KEY `saatavillaID` (`saatavillaID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `asiakas`
--
ALTER TABLE `asiakas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `kategoria`
--
ALTER TABLE `kategoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `kayttajat`
--
ALTER TABLE `kayttajat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `palvelu`
--
ALTER TABLE `palvelu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `saatavilla`
--
ALTER TABLE `saatavilla`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `toimipiste`
--
ALTER TABLE `toimipiste`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tyontekija`
--
ALTER TABLE `tyontekija`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `varaus`
--
ALTER TABLE `varaus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Rajoitteet vedostauluille
--

--
-- Rajoitteet taululle `palvelu`
--
ALTER TABLE `palvelu`
  ADD CONSTRAINT `palvelu_ibfk_1` FOREIGN KEY (`kategoria`) REFERENCES `kategoria` (`id`) ON UPDATE CASCADE;

--
-- Rajoitteet taululle `saatavilla`
--
ALTER TABLE `saatavilla`
  ADD CONSTRAINT `saatavilla_ibfk_1` FOREIGN KEY (`palvelu`) REFERENCES `palvelu` (`id`),
  ADD CONSTRAINT `saatavilla_ibfk_2` FOREIGN KEY (`tyontekija`) REFERENCES `tyontekija` (`id`),
  ADD CONSTRAINT `saatavilla_ibfk_3` FOREIGN KEY (`toimipiste`) REFERENCES `toimipiste` (`id`);

--
-- Rajoitteet taululle `varaus`
--
ALTER TABLE `varaus`
  ADD CONSTRAINT `varaus_ibfk_2` FOREIGN KEY (`palvelu`) REFERENCES `palvelu` (`id`),
  ADD CONSTRAINT `varaus_ibfk_3` FOREIGN KEY (`toimipiste`) REFERENCES `toimipiste` (`id`),
  ADD CONSTRAINT `varaus_ibfk_4` FOREIGN KEY (`tyontekija`) REFERENCES `tyontekija` (`id`),
  ADD CONSTRAINT `varaus_ibfk_5` FOREIGN KEY (`asiakas`) REFERENCES `asiakas` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `varaus_ibfk_6` FOREIGN KEY (`saatavillaID`) REFERENCES `saatavilla` (`id`);

DELIMITER $$
--
-- Tapahtumat
--
CREATE DEFINER=`ajava`@`%` EVENT `poistavanhat` ON SCHEDULE EVERY 5 MINUTE STARTS '2018-05-20 22:32:50' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
DELETE FROM varaus WHERE varaus.paivamaara < NOW();
DELETE FROM asiakas WHERE asiakas.id NOT IN (SELECT asiakas FROM varaus);
DELETE FROM saatavilla WHERE saatavilla.paivamaara < NOW();
END$$

DELIMITER ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
