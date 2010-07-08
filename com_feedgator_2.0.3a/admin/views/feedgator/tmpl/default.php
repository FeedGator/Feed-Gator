<?php	

jimport('joomla.utilities.date');


$ordering = ($this->lists['order'] == 'section_name' || $this->lists['order'] == 'cc.name');
JHTML::_('behavior.tooltip');

		?>
		<style type="text/css">
						<!--
						@import url("components/com_feedgator/css/styles.css");
						-->
    		</style>
		<?php
		$xajax = new xajax('index.php?option=com_feedgator&no_html=1');
		$xajax->registerExternalFunction('importfeed', JPATH_COMPONENT.DS.'admin.feedgator.php');
		$xajax->registerExternalFunction('importall', JPATH_COMPONENT.DS.'admin.feedgator.php');
		$xajax->printJavascript( JURI::base().'components/com_feedgator/inc' );
		$xajax->errorHandlerOn();

		?>

		<script language="javascript" type="text/javascript">
		//<![CDATA[
		var waiting; // global to hold setInterval handle
		function closeMsgArea() {
			var msgarea = document.getElementById('fgmsgarea');
			msgarea.style.display = 'none';
		}

		function resetMsgArea() {
			var msgarea = document.getElementById('fgmsgarea');
			clearInterval(waiting);
			msgarea.innerHTML = '';
			msgarea.style.color = '#111111';
			msgarea.style.fontWeight = 'normal';
			msgarea.className = '';
		}

		function importall() {
			var really = confirm("Are you sure you want to Import All RSS feeds?");
			if (really) {
				var msgarea=document.getElementById('fgmsgarea');
				msgarea.style.color = '#88aa22';
				msgarea.style.fontWeight = 'bold';
				msgarea.innerHTML='Processing...';
				msgarea.style.display='block';
				msgarea.className='waiting';
				//waiting=setInterval('waitProcessing()',1200);
				xajax_importall(xajax.getFormValues('adminForm'));
			}
		}

		function importfeed() {
			if (document.adminForm.boxchecked.value == 0)
			{
				alert('Please make a selection from the list to import');
			} else {
				var msgarea=document.getElementById('fgmsgarea');
				msgarea.style.color = '#88aa22';
				msgarea.style.fontWeight = 'bold';
				msgarea.innerHTML='Processing...';
				msgarea.style.display='block';
				msgarea.className='waiting';
				//					waiting=setInterval('waitProcessing()',1200);
				xajax_importfeed(xajax.getFormValues('adminForm'));
			}
		}

		function waitProcessing() {
			var msgarea = document.getElementById('fgmsgarea');
			var bar = document.getElementById('progressbar');
			if (!bar) {
				bar = document.createElement('span');
				bar.setAttribute('id', 'progressbar');
				bar.style.height = '20px';
				bar.style.width = '15px';
				bar.style.backgroundColor = '#88aa22';
				bar.style.color = '#ffffff';
				bar.style.paddingBottom = '2px';
				msgarea.appendChild(bar);
			} else {
				bar.innerHTML += '|&nbsp;';
			}
		}
		//]]>
		</script>
		<div id="fgmsgarea"></div>
		
    		<form action="index2.php" method="post" name="adminForm" id="adminForm">

			<table>
				<tr>
					<td width="100%">
						<?php echo JText::_( 'Filter' ); ?>:
						<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" title="<?php echo JText::_( 'Filter by title or enter article ID' );?>"/>
						<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
						<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_sectionid').value='-1';this.form.getElementById('catid').value='0';this.form.getElementById('filter_authorid').value='0';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
					</td>
				</tr>
			</table>
	    	<table class="adminlist" cellspacing="1">
			<thead>
			<tr>
     			<th width="5">
						<?php echo JText::_( 'Num' ); ?>
					</th>
        			<th width="5">
						<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->rows ); ?>);" />
					</th>
        			<th class="title">
						<?php echo JHTML::_('grid.sort',   'Title', 'f.title', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
					</th>
				<th width="1%" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort',   'Enabled', 'f.published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
					</th>
					<th nowrap="nowrap" width="1%">
						<?php echo JHTML::_('grid.sort',   'Front Page', 'f.front_page', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
					</th>
        			<th  class="title" width="25%" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort',   'Feed', 'feed', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
					</th>
		        	<th class="title" width="8%" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort',   'Section', 's.title', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
					</th>
					<th  class="title" width="8%" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort',   'Category', 'cc.name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
					</th>
        			<th align="center" width="10">
						<?php echo JHTML::_('grid.sort',   'Last Run', 'f.created', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
						<th width="1%" class="title">
						<?php echo JHTML::_('grid.sort',   'ID', 'f.id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
			<tr>
				<td colspan="10">
					<?php echo $this->page->getListFooter(); ?>
				</td>
			</tr>
			</tfoot>
			<tbody>
      		<?php

      		$k = 0;
      		$user	=& JFactory::getUser();

      		for ($i=0, $n=count( $this->rows ); $i < $n; $i++) {


      			$row = &$this->rows[$i];

      			$link = 'index2.php?option=com_feedgator&task=edit&cid[]='.$row->id;
      			$checked 	= JHTML::_('grid.checkedout',   $row, $i );
      			$published 	= JHTML::_('grid.published', $row, $i );
      			echo "<tr class='row$k'>";
      	?>
					<td>
						<?php echo $this->page->getRowOffset( $i ); ?>
					</td>
		<td align="center">
						<?php echo $checked; ?>
					</td>
					<td>
					<?php
					if (  JTable::isCheckedOut($user->get ('id'), $row->checked_out ) ) {
						echo $row->title;
					} else {
							?>
							<a href="<?php echo JRoute::_( $link ); ?>">
								<?php echo htmlspecialchars($row->title, ENT_QUOTES); ?></a>
							<?php
					}
						?>
						</td>
      	
        <td width="2%" align="center">
        	<?php echo $published;?>
       </td>
<?php
$ftask = $row->front_page ? 'front_no' : 'front_yes';

      	?>
        <td width="2%" align="center">
        <a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo ( $row->front_page ) ? 'front_no' : 'front_yes' ;?>')" title="<?php echo ( $row->front_page ) ? JText::_( 'Yes' ) : JText::_( 'No' );?>">
							<img src="images/<?php echo ( $row->front_page ) ? 'tick.png' : 'disabled.png' ;?>" width="16" height="16" border="0" alt="<?php echo ( $row->front_page ) ? JText::_( 'Yes' ) : JText::_( 'No' );?>" /></a>
        </td>
      	<?php 
      	echo "<td align='left'>$row->feed</td>";

      	echo "<td align='left'>$row->section_name</td>";

      	echo "<td align='left'>$row->cat_name</td>";
		echo "<td align='left'>$row->last_run</td>";	?>
		<td>
						<?php echo $row->id; ?>
					</td>
    	</tr>
    	<?php    $k = 1 - $k; } ?>
    
  	</tbody>
			</table>
		<input type="hidden" name="option" value="com_feedgator" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="redirect" value="" />
		<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>
  	</form>