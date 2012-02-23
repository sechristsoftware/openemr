<?php
// Copyright (C) 2008 Phyaura, LLC <info@phyaura.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

function getRelayHealthLink( $dest ) {
      switch( trim($dest) )
      {
        case "inbox":
		
	  return "<div id='rhmsgcount'><a   onclick=\"return loadFrame2('','RTop','jump_to_RelayHealth.php?dest=inbox')\"  href=''  class='rhesuites' id='rh_link'>". xl('e-messages') .'<span id="unreadMessage"></span></a><script>$(document).ready(function(){$("#unreadMessage").load("../jump_to_RelayHealth.php?dest=CountMessages");});</script></div>';
          break;
      case "renewals":
            return "<a href='' onclick=\"return loadFrame2('','RTop','jump_to_RelayHealth.php?dest=renewals')\"   class='rhesuites' id='rh_link'>". xl('e-renewals') .'</a>';
        break;
      case "escripts":
            return "<a href='' onclick=\"return loadFrame2('','RTop','jump_to_RelayHealth.php?dest=escripts')\" class='rhesuites' id='rh_link'>". xl('e-scripts') .'</a>';
        break;
      case "home":
        return "<a href='' onclick=\"return loadFrame2('','RTop','jump_to_RelayHealth.php?dest=home')\" class='navitem' id='h_link'>". xl('E-suites') .'</a>';
        break;
		case "summary":
        return "<a href='' onclick=\"return loadFrame2('','RTop','jump_to_RelayHealth.php?dest=summary')\"  class='rhesuites' id='rh_link'>". xl('Summary') .'</a>';
        break;
      default:
        return "<a href='' onclick=\"return loadFrame2('','RTop','jump_to_RelayHealth.php?dest=home')\" class='navitem' id='h_link'>". xl('E-suites') .'</a>';
      }
}
?>
