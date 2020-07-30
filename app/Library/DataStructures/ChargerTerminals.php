<?php

namespace App\Library\DataStructures;

class ChargerTerminals
{
  private $chargerId;
  private $terminalId;
  private $report;

  public static function instance(): self
  {
    return new self;
  }

  function setChargerId( $chargerId ): self
  {
    $this -> chargerId = $chargerId;
    return $this;
  }

  function getChargerId()
  {
    return $this -> chargerId;
  }

  function setTerminalId( $terminalId ): self
  {
    $this -> terminalId = $terminalId;
    return $this;
  }

  function getTerminalId()
  {
    return $this -> terminalId;
  }

  function setReport( $report ): self
  {
    $this -> report = $report;
    return $this;
  }
  
  function getReport()
  {
    return $this -> report;
  }
}