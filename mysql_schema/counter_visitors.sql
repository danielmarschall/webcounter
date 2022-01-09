-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 09. Jan 2022 um 01:11
-- Server-Version: 10.3.31-MariaDB-0+deb10u1-log
-- PHP-Version: 7.3.31-1~deb10u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `daniel-marschall`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `counter_visitors`
--

CREATE TABLE `counter_visitors` (
  `id` int(11) NOT NULL,
  `idstr` varchar(255) NOT NULL,
  `counter` int(11) NOT NULL DEFAULT 0,
  `tsCreated` timestamp NULL DEFAULT NULL,
  `tsLastVisit` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `counter_visitors`
--
ALTER TABLE `counter_visitors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idstr` (`idstr`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `counter_visitors`
--
ALTER TABLE `counter_visitors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
