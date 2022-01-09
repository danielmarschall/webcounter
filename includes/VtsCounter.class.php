<?php

/*
 * PHP Counter mit Reloadsperre, Textdatenbank und Graphic-Libary (without Error Images)
 * (C)Copyright 2010 - 2019 Daniel Marschall
 * Revision: 2019-02-18
 */

class VtsCounter {
	private $pdo = null;

	function __construct($pdo) {
		$this->pdo = $pdo;
	}

	public function clearReloadSperre($minutes) {
		if ($minutes < 1) return;
		$statement = $this->pdo->prepare("DELETE FROM counter_reloadsperre WHERE tsLastVisit < (UTC_TIMESTAMP() - INTERVAL $minutes MINUTE)");
		pdox_execute($statement);

		# Alte Counter / Fake Counter / SQL Injection Tests löschen
		$statement = $this->pdo->prepare("DELETE FROM counter_visitors WHERE tsLastVisit < (UTC_TIMESTAMP() - INTERVAL 1 YEAR)");
		pdox_execute($statement);
	}

	public function getIDfromIDStr($idstr) {
		$statement = $this->pdo->prepare("SELECT id FROM counter_visitors WHERE idstr = ?");
		pdox_execute($statement, array($idstr));
		$numrows = $statement->rowCount();
		$id = -1;
		if ($numrows == 0) {
			$statement = $this->pdo->prepare("INSERT INTO counter_visitors (idstr, tsCreated) VALUES (?, UTC_TIMESTAMP())");
			pdox_execute($statement, array($idstr));
			$id = $this->pdo->lastInsertId();
		} else {
			assert($numrows == 1);
			$row = $statement->fetch();
			$id = $row['id'];
		}
		assert($id > 0);
		return $id;
	}

	public function visitCount($counter_id, $ip) {
		$statement = $this->pdo->prepare("SELECT * FROM counter_reloadsperre WHERE fk_counter = ? AND ip = ?");
		pdox_execute($statement, array($counter_id, $ip));
		$numrows = $statement->rowCount();
		if ($numrows == 0) {
			$statement = $this->pdo->prepare("INSERT INTO counter_reloadsperre (fk_counter, ip, tsLastVisit) VALUES (?, ?, UTC_TIMESTAMP())");
			pdox_execute($statement, array($counter_id, $ip));

			$statement = $this->pdo->prepare("UPDATE counter_visitors SET counter = counter + 1, tsLastVisit = UTC_TIMESTAMP() WHERE id = ?");
			pdox_execute($statement, array($counter_id));
		} else {
			assert($numrows == 1);
			$row = $statement->fetch();
			$sperre_id = $row['id'];
			$statement = $this->pdo->prepare("UPDATE counter_reloadsperre SET tsLastVisit = UTC_TIMESTAMP() WHERE id = ?");
			pdox_execute($statement, array($sperre_id));
		}
	}

	public function getCounterInfo($counter_id) {
		$statement = $this->pdo->prepare("SELECT counter, tsCreated FROM counter_visitors WHERE id = ?");
		pdox_execute($statement, array($counter_id));
		$numrows = $statement->rowCount();
		assert($numrows == 1);
		$row = $statement->fetch();
		$out = new VtsCounterInfo();
		$out->visitors = $row['counter'];
		$out->created = $row['tsCreated'];
		return $out;
	}

}
