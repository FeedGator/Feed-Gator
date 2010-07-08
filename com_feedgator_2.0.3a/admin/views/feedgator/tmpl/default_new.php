<?php defined('_JEXEC') or die('Restricted access'); ?>




		<script language="javascript" type="text/javascript">

		<!--

		var sectioncategories = new Array;

		<?php

		$i = 0;

		foreach ($this->sectioncategories as $k=>$items) {

			foreach ($items as $v) {

				echo "sectioncategories[".$i++."] = new Array( '$k','".addslashes( $v->id )."','".addslashes( $v->title )."' );\n\t\t";

			}

		}

		?>



    function submitbutton(pressbutton) {

      var form = document.adminForm;

      if (pressbutton == 'cancel') {

        submitform( pressbutton );

        return;

      }





      // do field validation

      if (form.feed.value == "") {



        alert( "You must at least enter a feed." );



      } else if (form.title.value == "") {



        alert( "You must enter a title." );



      } else if (form.sectionid.value == "") {



        alert( "You must enter a section" );



      } else if (form.catid.value == "") {



        alert( "You must enter a category" );



			} else {



        submitform( pressbutton );



      }



    }



		-->



    </script>




   <form action="index2.php" method="post" name="adminForm" id="adminForm">



    <table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">

      <tr>

        <td valign="top" align="right"><?php echo JText::_( 'Title' ); ?>:</td>

        <td>

          <input class="inputbox" type="text" name="title" value="<?php echo $this->feed->title; ?>" size="50" maxlength="100" />

        </td>

      </tr>



      <tr>

        <td valign="top" align="right"><?php echo JText::_( 'Feed URL' ); ?>:</td>

        <td>

          <input class="inputbox" type="text"  name="feed" value="<?php echo $this->feed->feed;?>" size="50" maxlength="512" />

        </td>

      </tr>



      <tr>

	<td valign="top" align="right"><?php echo JText::_( 'Section' ); ?>:</td>

	  <td>

	    <?php echo $this->lists['sectionid']; ?>

	</td>

	</tr>



	<tr>

	  <td valign="top" align="right"><?php echo JText::_( 'Category' ); ?>:</td>

	    <td>

		<?php echo $this->lists['catid']; ?>

	   </td>

      </tr>

			



      <tr>

        <td valign="top" align="right"><?php echo JText::_( 'Trim into (#chars)' ); ?>:</td>

        <td>

          <input class="inputbox" type="text"  name="trim_to" value="<?php echo $this->feed->trim_to;?>" size="5" maxlength="10" />

        </td>

      </tr>



      <tr>

        <td valign="top" align="right"><?php echo JText::_( 'Default Author' ); ?>:</td>

        <td>

          <input class="inputbox" type="text"  name="default_author" value="<?php echo $this->feed->default_author;?>" size="15" maxlength="50" />

        </td>

      </tr>



      <tr>

        <td valign="top" align="right"><?php echo JText::_( 'Enabled' ); ?>:</td>

        <td>

          <?php echo JHTML::_('select.booleanlist', 'published', '', $this->feed->published); ?>

        </td>

      </tr>



      <tr>

        <td valign="top" align="right"><?php echo JText::_( 'Front page' ); ?>:</td>

        <td>

          <?php echo JHTML::_('select.booleanlist', 'front_page', '', $this->feed->front_page); ?>

        </td>

      </tr>



      <tr>

        <td valign="top" align="right"><?php echo JText::_( 'Create Short Link' ); ?>:</td>

        <td>

          <?php echo JHTML::_('select.booleanlist', 'shortlink', '', $this->feed->shortlink);?>

        </td>

      </tr>



      <tr>

        <td valign="top" align="right"><?php echo JText::_( 'Only Intro Text' ); ?>:</td>

        <td>

          <?php echo JHTML::_('select.booleanlist', 'onlyintro', '', $this->feed->onlyintro); ?></td>

      </tr>

    </table>



    <input type="hidden" name="cid" value="<?php echo $this->feed->id; ?>" />

    <input type="hidden" name="option" value="com_feedgator" />

    <input type="hidden" name="task" value="" />

    <?php echo JHTML::_( 'form.token' ); ?>

    </form>