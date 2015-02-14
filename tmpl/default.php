<?php

/**
 * @version     1.0.0
 * @package     mod_eiko_slider
 * @copyright   Copyright (C) 2015 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <webmaster@feuerwehr-veenhusen.de> - http://einsatzkomponente.de
 */


defined('_JEXEC') or die;


$document =	JFactory::getDocument();
$document->addStyleSheet('modules/mod_eiko_slider/assets/css/jquery.bxslider.css');
$document->addScript('modules/mod_eiko_slider/assets/jquery/jquery.bxslider.js'); 

if ($params->get('jquery','1')) : 
JHtml::_('jquery.framework',false);
endif;

$zufall 			= rand(1,100000);
$moduleclass_sfx 	= $params->get('moduleclass_sfx');
$mymenuitem 		= $params->get('mymenuitem'); // Menü-Eintrag
$orga 				= $params->get('orga');

 ?>
<style>
ul.bxslider<?php echo $zufall;?> > li:before {
    content:none;
    padding: 0;
    margin: 0;
}
.module ul a, .module_menu ul a {
    content:none;
    padding: 0 !important;
    margin: 0;
}

ul.bxslider<?php echo $zufall;?> > li {
    padding-left: 10px;
	padding-top:10px;
    margin:0;
}

ul.bxslider<?php echo $zufall;?> img {
    border-radius: 10px;
    padding: 0;
    margin: 0;
    border: 0;
}

</style>
<style type="text/css"><?php echo $params->get('css');?></style>

<?php
if ($orga == '-- alle anzeigen --') :
		// Funktion : letze x Einsatzdaten laden
		$query = 'SELECT r.id,r.image as foto,rd.marker,r.address,r.summary,r.auswahlorga,r.desc,r.date1,r.data1,r.counter,r.alerting,r.presse,re.image,rd.list_icon,r.auswahlorga,r.state FROM #__eiko_einsatzberichte r JOIN #__eiko_einsatzarten rd ON r.data1 = rd.title LEFT JOIN #__eiko_alarmierungsarten re ON re.id = r.alerting WHERE r.state = "1" and rd.state = "1" and re.state = "1" and r.image <> "" ORDER BY r.date1 DESC LIMIT '.$params->get('count').' ' ;
		$db	= JFactory::getDBO();
		$db->setQuery( $query );
		$result = $db->loadObjectList();
		$reports = $result;
else:
		// Funktion : letze x Einsatzdaten laden
		$query = 'SELECT r.id,r.image as foto,rd.marker,r.address,r.summary,r.auswahlorga,r.desc,r.date1,r.data1,r.counter,r.alerting,r.presse,re.image,rd.list_icon,r.auswahlorga,r.state FROM #__eiko_einsatzberichte r JOIN #__eiko_einsatzarten rd ON r.data1 = rd.title LEFT JOIN #__eiko_alarmierungsarten re ON re.id = r.alerting WHERE FIND_IN_SET("'.$orga.'", r.auswahlorga) and r.state = "1" and rd.state = "1" and re.state = "1" and r.image <> "" ORDER BY r.date1 DESC LIMIT '.$params->get('count').' ' ;
		$db	= JFactory::getDBO();
		$db->setQuery( $query );
		$result = $db->loadObjectList();
		$reports = $result; 
endif;


$counter = count($reports);  
?>

<div class="eiko_last_slider <?php echo $moduleclass_sfx ?>"<?php if ($params->get('backgroundimage')) : ?> style="background-image:url(<?php echo $params->get('backgroundimage');?>)"<?php endif;?>>



<ul class="bxslider<?php echo $zufall;?>">
	<?php
	$a = 0;
	while($a < $counter)
	{
	$curTime = strtotime($reports[$a]->date1); 
	$reports[$a]->desc = (strlen($reports[$a]->desc) > $params->get('char_desc','100')) ? substr($reports[$a]->desc,0,strrpos(substr($reports[$a]->desc,0,$params->get('char_desc','100')+1),' ')).' ...' : $reports[$a]->desc;
	$reports[$a]->summary = (strlen($reports[$a]->summary) > $params->get('char_summary','30')) ? substr($reports[$a]->summary,0,strrpos(substr($reports[$a]->summary,0,$params->get('char_summary','30')+1),' ')).' ...' : $reports[$a]->summary;
	?>  
   

	<li>
	<a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&Itemid='.$mymenuitem.'&view=einsatzbericht&id=' . (int)$reports[$a]->id); ?>">
	<img src="<?php echo $reports[$a]->foto;?>"  title="<?php echo date('d.m.Y ', $curTime).'um '.date('H:i', $curTime).' Uhr';?> -- <?php echo $reports[$a]->summary;?>" alt="<?php echo date('d.m.Y ', $curTime).'um '.date('H:i', $curTime).' Uhr';?> - <?php echo $reports[$a]->summary;?>"/> 
	</a>
	</li>
  
	<?php $a++; } ?>
</ul> 
 
</div>

 <script type="text/javascript">   
jQuery(document).ready(function () {
        jQuery('.bxslider<?php echo $zufall;?>').bxSlider({
            mode: '<?php echo $params->get('direction','vertical');?>',
            captions: <?php echo $params->get('caption','true');?>,
            pager: true,
            controls: <?php echo $params->get('controls','false');?>,
            auto: <?php echo $params->get('auto','true');?>,
            minSlides: <?php echo $params->get('num_of_images','3');?>,
            maxSlides: <?php echo $params->get('num_of_images','3');?>,
            moveSlides: <?php echo $params->get('moveSlides','1');?>,
            slideMargin: 5,
            infiniteLoop: true,
			slideWidth: <?php echo $params->get('slideWidth','800');?>,
			preloadImages: 'visible'
        });
    });
	
</script>


