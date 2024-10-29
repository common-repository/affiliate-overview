<?php 
function printAffilinetOverview()
{  
  define ("WSDL_LOGON", "https://api.affili.net/V2.0/Logon.svc?wsdl");
  define ("WSDL",  "https://api.affili.net/V2.0/AccountService.svc?wsdl");
  
  $SOAP_LOGON = new SoapClient(WSDL_LOGON);
 

  $Token      = $SOAP_LOGON->Logon(array(
               'Username'  => get_option("affilinetPubID"),
               'Password'  => get_option("affilinetPubWsPw"),
               'WebServiceType' => 'Publisher'
               ));
  
  $SOAP_REQUEST = new SoapClient(WSDL);
  $req = $SOAP_REQUEST->GetPublisherSummary($Token);
 
  $thisMonthConfirmed = $req->CurrentMonth->Confirmed;
  $thisMonthOpen = $req->CurrentMonth->Open;  
  $thisMonthCancelled = $req->CurrentMonth->Cancelled;  
  $otherMonthConfirmed = $req->PreviousMonths->Confirmed;
  $otherMonthOpen = $req->PreviousMonths->Open;  
  $otherMonthCancelled = $req->PreviousMonths->Cancelled;  
  $lastPaymentDate = $req->Payments->LastPayment;
  $totalPayment = $req->Payments->TotalPayment;
  $partnerShipsActive = $req->Partnerships->PartnershipsActive;
  $partnerShipsWaiting = $req->Partnerships->PartnershipsWaiting;
  
?>
 <table style="width:100%;border:dotted gray 1px;" cellspacing="1">
   <tr class="header">
     <th colspan="2">Kontostand</th>
     <th colspan="2">Umsatz der Vormonate</th>
     <th>Auszahlungen</th>
     <th colspan="2">Partnerschaften</th>
   </tr>
   <tr class="gerade">
     <td class="rightgray">Best&auml;tigt</td>
     <td class="rightgray bold confirmed"><?=money($thisMonthConfirmed);?></td>
     <td class="rightgray">Best&auml;tigt</td>
     <td class="rightgray bold confirmed"><?=money($otherMonthConfirmed);?></td>
     <td class="rightgray bold other"><?=gerDate($lastPaymentDate);?></td>
     <td class="rightgray">Aktive:</td>
     <td class="bold other center"><?=$partnerShipsActive?></td>
   </tr>
   <tr class="ungerade">
     <td class="rightgray">Offen</td>
     <td class="rightgray bold open"><?=money($thisMonthOpen);?></td>
     <td class="rightgray">Offen</td>
     <td class="rightgray bold open"><?=money($otherMonthOpen);?></td>
     <td class="rightgray bold other"><?=money($totalPayment);?></td>
     <td class="rightgray">Wartend:</td>
     <td class="bold other center"><?=$partnerShipsWaiting?></td>
   </tr>
   <tr class="gerade">
     <td class="rightgray">Storniert</td>
     <td class="rightgray bold canceled"><?=money($thisMonthCancelled);?></td>
     <td class="rightgray">Storniert</td>
     <td class="rightgray bold canceled"><?=money($otherMonthCancelled);?></td>
     <td>&nbsp;</td>
     <td colspan="2">&nbsp;</td>
   </tr>
   <tr class="ungerade rightgray">
     <td colspan="7"><a href="http://publisher.affili.net" target="_blank" title="publisher.affili.net"><b>&raquo; zum affilinet Publisher-Login</b></td>
   </tr>
 </table>
<?php
 
} 
?>