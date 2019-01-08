# Facilitator
API for Facilitator ( entities which runs their own IoT management system using i4things server and tools )

Example using PHP ( we provdie API's for any other server side scripst also - feel free to send a request to info@i4things.com if you require Facilitator API for different language )
```

require_once("i4things.php");

$ret = i4things_create_facilitator('XXXXXXXXXX','MY F');
$facilitatorid = '';
$facilitatorkey = '';
$facilitatorname = 'MY F';
if ($ret === NULL)
{
    echo "CREATE FACILITATOR ERROR";
}
else
{
    $facilitatorid = $ret[0];
    $facilitatorkey = $ret[1];

    echo  "FACILITATOR:".$facilitatorid." ".$facilitatorkey." ".$facilitatorname."\n";
}

$ret = i4things_create_account($facilitatorid, $facilitatorkey,'MY A');
$accountid = '';
$accountkey = '';
$accountname = 'MY A';
if ($ret === NULL)
{
    echo "CREATE ACCOUNT ERROR";
}
else
{
    $accountid = $ret[0];
    $accountkey = $ret[1];

    echo "ACCOUNT:".$accountid." ".$accountkey." ".$accountname."\n";
}

$ret = i4things_create_node($accountid, $accountkey, TRUE,'MY N');
$nodeid = '';
$nodekey = '';
$nodename = 'MY N';
if ($ret === NULL)
{
    echo "CREATE NODE ERROR";
}
else
{
    $nodeid = $ret[0];
    $nodekey = $ret[1];

    echo "NODE:".$nodeid." ".$nodekey." ".$nodename."\n";
}

$ret = i4things_create_gateway($accountid, $accountkey, 51.438939, -0.21863, TRUE, 'MY G');
$gatewayid = '';
$gatewaykey = '';
$gatewayname = 'MY G';
if ($ret === NULL)
{
    echo "CREATE GATEWAY ERROR";
}
else
{
    $gatewayid = $ret[0];
    $gatewaykey = $ret[1];

    echo "GATEWAY:".$gatewayid." ".$gatewaykey." ".$gatewayname."\n";
}

$ret = i4things_get_facilitator('XXXXXXXXXXXX');
if ($ret === NULL)
{
    echo "GET FACILITATOR ERROR\n";
}
else
{
    echo "FACILITATORS[\n";
    foreach ($ret as $facilitator) {
        foreach ($facilitator as $detail) {
            echo $detail.",";
        }
        echo "\n";
    }
    echo "]\n";
}

$ret = i4things_get_facilitator_details('XXXXXXXXXXXXXX',$facilitatorid);
if ($ret === NULL)
{
    echo "GET FACILITATOR DETAILS ERROR\n";
}
else
{
    echo "FACILITATOR DETAILS: ";
    foreach ($ret as $detail) {
        echo $detail.",";
        }
    echo "\n";
}

$ret = i4things_get_account($facilitatorid, $facilitatorkey);
if ($ret === NULL)
{
    echo "GET ACCOUNT ERROR\n";
}
else
{
    echo "ACCOUNTS[\n";
    foreach ($ret as $account) {
        foreach ($account as $detail) {
            echo $detail.",";
        }
        echo "\n";
    }
    echo "]\n";
}

$ret = i4things_get_account_details($facilitatorid, $facilitatorkey, $accountid);
if ($ret === NULL)
{
    echo "GET ACCOUNT DETAILS ERROR\n";
}
else
{
    echo "ACCOUNT DETAILS: ";
    foreach ($ret as $detail) {
        echo $detail.",";
        }
    echo "\n";
}

$ret = i4things_get_gateway($accountid, $accountkey);
if ($ret === NULL)
{
    echo "GET GATEWAY ERROR\n";
}
else
{
    echo "GATEWAY[\n";
    foreach ($ret as $gateway) {
        foreach ($gateway as $detail) {
            echo $detail.",";
        }
        echo "\n";
    }
    echo "]\n";
}

$ret = i4things_get_gateway_details($accountid, $accountkey, $gatewayid);
if ($ret === NULL)
{
    echo "GET GATEWAY DETAILS ERROR\n";
}
else
{
    echo "GATEWAY DETAILS: ";
    foreach ($ret as $detail) {
        echo $detail.",";
        }
    echo "\n";
}

$ret = i4things_get_node($accountid, $accountkey);
if ($ret === NULL)
{
    echo "GET NODE ERROR\n";
}
else
{
    echo "NODE[\n";
    foreach ($ret as $node) {
        foreach ($node as $detail) {
            echo $detail.",";
        }
        echo "\n";
    }
    echo "]\n";
}

$ret = i4things_get_node_details($accountid, $accountkey, $nodeid);
if ($ret === NULL)
{
    echo "GET NODE DETAILS ERROR\n";
}
else
{
    echo "NODE DETAILS: ";
    foreach ($ret as $detail) {
        echo $detail.",";
        }
    echo "\n";
}


// example return:
//var gatewayId = "4100";
//var gatewayDayLabels = [1546973755096,1546973695100,1546973635187,1546973575081,1546973515082,1546973455170,1546973395163,1546973335158,1546973275266,1546973215146,1546973155140,1546973095092,1546973035078,1546972975123,1546972915117,1546972855119,1546972795108,1546972735069,1546972675195,1546972615194,1546972555082,1546972495076,1546972435172,1546972375167,1546972315152,1546972255160,1546972195070,1546972135147,1546972075258,1546972015132,1546971955130,1546971895123,1546971835061,1546971775114,1546971715102,1546971655200,1546971595093,1546971535086,1546971474466,1546971474466,1546971438802,1546971378010,1546971378009];
//var gatewayDayHumidity = [79,53,58,51,62,43,75,43,59,57,52,58,74,41,75,43,59,75,74,45,58,46,44,43,76,78,52,78,54,72,73,44,43,52,48,46,47,55,51,59,47,69,50];
//var gatewayDayTemp = [16.7,18,10.7,16.7,21.1,15.1,21.1,12.9,19.2,15.8,17,22.7,20.8,19.5,22,10.7,17,18.3,10.1,17,14.5,21.7,12.3,17.6,16.7,17.6,14.2,20.2,22,11.1,16.4,13.6,20.8,19.8,15.8,19.8,21.7,20.5,16.4,17.3,13.3,11.1,16.7];
//var gatewayHistLabels = [];
//var gatewayHistHumidity = [];
//var gatewayHistTemp = [];

$ret = i4things_get_gateway_data($accountid, $accountkey, $gatewayid);
if ($ret === NULL)
{
  echo "GET GATEWAY DATA ERROR\n";
}
else
{
  echo "GATEWAY DATA: ";
  
  echo $ret;
    
  echo "\n";
}


// return gateway data or error
// example return
//var deviceId = 1;
//var deviceDayLabels = [1546973675123,1546973555083,1546973435100,1546973315075,1546973195128,1546973075076,1546972955156,1546972835147,1546972715072,1546972595126,1546972475066,1546972355113,1546972235090,1546972115063,1546971995164,1546971875155,1546971790090];
//var deviceDayRssi = [68,72,68,68,72,73,82,82,82,82,82,83,81,82,82,78,80];
//var deviceDayLat = [51.438939,51.438939,51.438939,51.438939,51.438939,51.438939,51.438939,51.438939,51.438939,51.438939,51.438939,51.438939,51.438939,51.438939,51.438939,51.438939,51.438939];
//var deviceDayLon = [-0.21863,-0.21863,-0.21863,-0.21863,-0.21863,-0.21863,-0.21863,-0.21863,-0.21863,-0.21863,-0.21863,-0.21863,-0.21863,-0.21863,-0.21863,-0.21863,-0.21863];


$ret = i4things_get_node_data($accountid, $accountkey, $nodeid);
if ($ret === NULL)
{
  echo "GET NODE ERROR\n";
}
else
{
  echo "NODE DATA: ";
  
  echo $ret;
    
  echo "\n";
}

$ret = i4things_delete_gateway($accountid, $accountkey, $gatewayid);
if ($ret === NULL)
{
    echo "DELETE GATEWAY ERROR\n";
}
else
{

    echo "GATEWAY: DELETE SUCCESS\n";
}

$ret = i4things_delete_node($accountid, $accountkey, $nodeid);
if ($ret === NULL)
{
    echo "DELETE NODE ERROR\n";
}
else
{

    echo "NODE: DELETE SUCCESS\n";
}

$ret = i4things_delete_account($facilitatorid, $facilitatorkey, $accountid);
if ($ret === NULL)
{
    echo "DELETE ACCOUNT ERROR\n";
}
else
{
    echo "ACCOUNT: DELETE SUCCESS\n";
}

$ret = i4things_delete_facilitator('XXXXXXXXXXXXXXXX',$facilitatorid);
if ($ret === NULL)
{
    echo "DELETE FACILITATOR ERROR\n";
}
else
{

    echo "FACILITATOR: DELETE SUCCESS\n";
}

```

