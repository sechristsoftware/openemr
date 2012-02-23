<?php
	/*******************************************************************/
	// Copyright (C) 2008-2011 Phyaura, LLC <www.phyaura.com> <info at phyaura.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This file was generated from https://api.integration.relayhealth.com/SSI/SingleSignIn.svc?wsdl using create_types.php

class RelayHealthHeader {
  // string
  public $ApplicationName;
  // string
  public $ApplicationPassword;
  // string
  public $PartnerName;
}

class InteropServiceFault {
  // string
  public $Message;
  // string
  public $UserMessage;
}

class StartC2C {
  // string
  public $PartnerUserId;
  // string
  public $PracticeId;
  // string
  public $UrlReq;
  // string
  public $UrlText;
}

class StartC2CResponse {
  // string
  public $Url;
}
class StartPrescription {
  // string
  public $partnerUserId;
  public $identifierType;   // mrn
  public $patientId; // pubpid
  public $assigningAuthority;  // guid set by vei for the provider (ssi_relayhelath or provider id) 
}

class StartPrescriptionResponse {
  // string
  public $Url;
}


class CodedInteropServiceFault {
  // string
  public $ErrorMessage;
  // string
  public $MajorFault;
  // string
  public $MinorFault;
}

class ViewInbox {
  // string
  public $partnerUserId;
}

class ViewInboxResponse {
  // string
  public $Url;
}

class ViewWelcome {
  // string
  public $partnerUserId;
}

class ViewWelcomeResponse {
  // string
  public $Url;
}

class ViewMessage {
  // string
  public $messageId;
  // string
  public $partnerUserId;
}

class ViewMessageResponse {
  // string
  public $Url;
}
class ViewRenewals {
  // string
  public $partnerUserId;
}
class CountMessages {
  // string
  public $UserIds;
}
class ViewRenewalsResponse {
  // string
  public $Url;
}

class MessageNotFoundFault {
  // short
  public $Code;
}

class ComposePatientMessage {
  // string
  public $partnerUserId;
  // string
  public $patientId;
  // string
  public $practiceId;
  // string
  public $providerId;
}

class ComposePatientMessageResponse {
  // string
  public $Url;
}

class PatientNotOnlineFault {
}

class NoRelationshipBetweenProviderAndPatientFault {
}

class ProviderNotOnlineFault {
}

class UserNotActiveInPracticeFault {
}

class UserNotFoundFault {
  // short
  public $Code;
}

class InvalidFormatFault {
}

class SecurityViolationFault {
}

class PartnerAccessViolationFault {
}

class NoRelationshipBetweenProviderAndPracticeFault {
}

class PracticeNotFoundFault {
}

$classmap = array(
  "RelayHealthHeader" => "RelayHealthHeader",
  "InteropServiceFault" => "InteropServiceFault",
  "StartC2C" => "StartC2C",
  "StartC2CResponse" => "StartC2CResponse",
  "StartPrescription" => "StartPrescription",
  "StartPrescriptionResponse" => "StartPrescriptionResponse",
  "CodedInteropServiceFault" => "CodedInteropServiceFault",
  "ViewInbox" => "ViewInbox",
  "CountMessages"=>"CountMessages",
  "ViewInboxResponse" => "ViewInboxResponse",
  "ViewWelcome" => "ViewWelcome",
  "ViewWelcomeResponse" => "ViewWelcomeResponse",
  "ViewMessage" => "ViewMessage",
  "ViewMessageResponse" => "ViewMessageResponse",
  "ViewRenewals" => "ViewRenewals",
  "ViewRenewalsResponse" => "ViewRenewalsResponse",
  "MessageNotFoundFault" => "MessageNotFoundFault",
  "ComposePatientMessage" => "ComposePatientMessage",
  "ComposePatientMessageResponse" => "ComposePatientMessageResponse",
  "PatientNotOnlineFault" => "PatientNotOnlineFault",
  "NoRelationshipBetweenProviderAndPatientFault" => "NoRelationshipBetweenProviderAndPatientFault",
  "ProviderNotOnlineFault" => "ProviderNotOnlineFault",
  "UserNotActiveInPracticeFault" => "UserNotActiveInPracticeFault",
  "UserNotFoundFault" => "UserNotFoundFault",
  "InvalidFormatFault" => "InvalidFormatFault",
  "SecurityViolationFault" => "SecurityViolationFault",
  "PartnerAccessViolationFault" => "PartnerAccessViolationFault",
  "NoRelationshipBetweenProviderAndPracticeFault" => "NoRelationshipBetweenProviderAndPracticeFault",
  "PracticeNotFoundFault" => "PracticeNotFoundFault"
);


class patientSummary {
  // string
  public $partnerUserId;
  public $identifierType;   // mrn
  public $patientId; // pubpid
  public $assigningAuthority;  // guid set by vei for the provider (ssi_relayhelath or provider id) 
}

?>
