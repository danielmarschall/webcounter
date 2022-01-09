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
-- Tabellenstruktur für Tabelle `counter_reloadsperre`
--

CREATE TABLE `counter_reloadsperre` (
  `id` int(11) NOT NULL,
  `fk_counter` int(11) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `tsLastVisit` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `counter_reloadsperre`
--
ALTER TABLE `counter_reloadsperre`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_counter` (`fk_counter`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `counter_reloadsperre`
--
ALTER TABLE `counter_reloadsperre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `counter_reloadsperre`
--
ALTER TABLE `counter_reloadsperre`
  ADD CONSTRAINT `counter_reloadsperre_ibfk_1` FOREIGN KEY (`fk_counter`) REFERENCES `counter_visitors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
