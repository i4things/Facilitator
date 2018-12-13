# Facilitator
API for Facilitator ( entities which runs their own IoT management system using i4things server and tools )

Example using PHP ( we provdie API's for any other server side scripst also - feel free to send a request to info@i4things.com if you require Facilitator API for different language )

<?php

$facilitatorid = '<fill your failitator ID here>';
$facilitatorkey = '<fill your facilitator key here>';

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

$ret = i4things_create_node($accountid, $accountkey, TRUE,'MY N 1');
$nodeid = '';
$nodekey = '';
$nodename = 'MY N 1';
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

?>


