<?php
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
AdminAuthentication();

$subcityval = $_GET['subcityval'];
$subcityval = str_replace("@","&",$subcityval);

$id = $_GET['id'];
$cityid = $_GET['cityid'];
$sel_id = $_GET['id'];
$ins = 0;
$c = 0;
$deletesubcity	=	$_GET['deletesubcity'];
$cid			=	$_GET['cid'];
if($deletesubcity != '')
{
		$qry	=	"DELETE FROM ".SUBURB." WHERE SUBURB_ID = '".$deletesubcity."'";
		$res	=	mysql_query($qry);
		if($res)
		{
			
			$selqry = "SELECT SUBURB_ID,LABEL FROM ".SUBURB."  WHERE CITY_ID = '".$cid."' ORDER BY LABEL";
			$ressel = mysql_query($selqry);
			?>
			<select name="suburbId" id = "suburbId" class="suburbId" onchange="dispcity(this.value,1);" STYLE="width: 150px">
			<option value =''>Select Suburb</option>
			<?php
				while($data	=	mysql_fetch_array($ressel))
				{
				?>
				<option  value ='<?php echo $data['SUBURB_ID']; ?>'><?php echo $data['LABEL']; ?></option>
				<?php
				}
				?>
			</select>

<?php
		}
}

else
{
	if($subcityval!='' && $id!='')
	{
		$seldata = "UPDATE ".SUBURB." SET LABEL = '".trim($subcityval)."' WHERE SUBURB_ID='".$id."'";
		$resdata = mysql_query($seldata);
		$c = mysql_affected_rows();
	}

	$seldata = "SELECT LABEL FROM ".SUBURB." WHERE LABEL = '".trim($subcityval)."' AND CITY_ID='".$cityid."'";
	$resdata = mysql_query($seldata);
	$ins = mysql_num_rows($resdata);

	if($c==0 && $ins==0)
	{	
		$qry = "INSERT INTO ".SUBURB." (LABEL,CITY_ID,ACTIVE) value('".$subcityval."','".$cityid."','1')";
		$res = mysql_query($qry);
		$ctid = mysql_insert_id();
		$sel_id = $ctid;
	}


	$selqry = "SELECT SUBURB_ID,LABEL FROM ".SUBURB." WHERE CITY_ID='".$cityid."' ORDER BY LABEL";
	$ressel = mysql_query($selqry);
	?>
	<select name="suburbId" id = "suburbId" class="suburbId" onchange="dispsubcity(this.value,1);" STYLE="width: 150px">
	<option value =''>Select Suburb</option>
	<?php
		while($data	=	mysql_fetch_array($ressel))
		{
		?>
		<option  value ='<?php echo $data['SUBURB_ID']; ?>' <?php if( $data['SUBURB_ID'] == $sel_id ) echo "selected='selected'"; ?>><?php echo $data['LABEL']; ?></option>
		<?php
		}
		?>
	</select>
<?php
}
?>