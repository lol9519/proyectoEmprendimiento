<?php
/**
 * @version    $Id$
 * @package    JSN Extension Framework 2
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Get Joomla document object.
$doc = JFactory::getDocument();

// @formatter:off
?>
<!DOCTYPE html>
<html lang="<?php echo $doc->language; ?>" dir="<?php echo $doc->direction; ?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<?php
	// Load and render document head.
	$head = $doc->loadRenderer('head');

	echo $head->render('');
	?>
	<style type="text/css">
		.searchtools-container-filters {
			position: fixed;
			height: calc(100% - 10px);
			overflow-y: auto;
		}
	</style>
</head>
<body class="jsn-bootstrap4 pt-0">
	<form method="post" name="adminForm" id="adminForm" class="container-fluid form-select-module" action="<?php
		echo $this->baseUrl . (($callback = $this->input->getString('callback', null)) ? "&callback={$callback}" : '');
	?>">
		<div class="row searchtools-module-row">
			<div class="col-3 searchtools-module-container-filters">
				<div class="searchtools-module-field-filter">
					<i class="fa fa-search" aria-hidden="true" onclick="document.getElementById('adminForm').submit();"></i>
					<input type="text" name="search" class="form-control" value="<?php
						echo $this->filter_search;
					?>" placeholder="<?php
						echo JText::_( 'JSN_EXTFW_SEARCH_FOR' );
					?>" />
				</div>
				<div class="searchtools-module-field-filter">
					<?php $this->renderStatusOptions('state', $this->filter_state); ?>
				</div>
				<div class="searchtools-module-field-filter">
					<?php $this->renderArticleCategoryOptions('category', $this->filter_category); ?>
				</div>
				<div class="searchtools-module-field-filter">
					<?php $this->renderAccessOptions('access', $this->filter_access); ?>
				</div>
				<div class="searchtools-module-field-filter">
					<?php $this->renderArticleAuthorOptions('uauthor', $this->filter_author); ?>
				</div>
				<div class="searchtools-module-field-filter">
					<?php $this->renderLanguageOptions('language', $this->filter_language); ?>
				</div>
				<div class="d-flex justify-content-end">
					<button type="submit" class="btn btn-default apply-filters">
						<?php echo JText::_( 'JSN_EXTFW_APPLY' ); ?>
					</button>
					<button type="reset" class="btn btn-default clear-filters">
						<?php echo JText::_( 'JSN_EXTFW_CLEAR' ); ?>
					</button>
				</div>
			</div>

			<div class="col-9 ml-auto px-0 searchtools-module-results">
				<table class="table">
					<thead>
						<tr>
							<th class="col-md-1">
								<?php echo JText::_('JSTATUS'); ?>
							</th>
							<th class="col-md-3 align-left">
								<?php echo JText::_('JGLOBAL_TITLE'); ?>
							</th>
							<th class="col-md-2">
								<?php echo JText::_('JCATEGORY'); ?>
							</th>
							<th class="col-md-2">
								<?php echo JText::_('JGRID_HEADING_ACCESS'); ?>
							</th>
							<th class="col-md-2">
								<?php echo JText::_('JGRID_HEADING_LANGUAGE'); ?>
							</th>
							<th class="col-md-1">
								<?php echo JText::_('JGRID_HEADING_ID'); ?>
							</th>
							<th class="col-md-1">
								<?php echo JText::_('JSN_EXTFW_ACTION'); ?>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($this->items as $i => $item) : ?>
						<tr class="row<?php echo $i % 2; ?>">
							<td class="center">
								<?php if ($item->state == 1) : ?>
								<i class="fa fa-check-circle-o"></i>
								<?php elseif ($item->state == 0) : ?>
								<i class="fa fa-times-circle-o"></i>
								<?php elseif ($item->state == 2) : ?>
								<i class="fa fa-archive"></i>
								<?php elseif ($item->state == -2) : ?>
								<i class="fa fa-trash"></i>
								<?php endif; ?>
							</td>
							<td class="align-left">
								<a href="javascript: void(0);" class="select-article" data-id="<?php echo $item->id; ?>">
									<?php echo $item->title; ?>
								</a>
							</td>
							<td class="center">
								<span class="label label-info">
									<?php echo $item->category; ?>
								</span>
							</td>
							<td class="center">
								<?php echo $item->access_level; ?>
							</td>
							<td class="center">
								<?php
								if ($item->language == '')
								{
									echo JText::_('JDEFAULT');
								}
								elseif ($item->language == '*')
								{
									echo JText::alt('JALL', 'language');
								}
								else
								{
									echo $item->language_title ? JHtml::_('image', 'mod_languages/' . $item->language_image . '.gif', $item->language_title, array('title' => $item->language_title), true) . '&nbsp;' . $item->language_title : JText::_('JUNDEFINED');
								}
								?>
							</td>
							<td class="center">
								<?php echo (int) $item->id; ?>
							</td>
							<td class="center">
								<a href="index.php?option=com_content&task=article.edit&id=<?php echo $item->id;?>" target="_blank" rel="noopener noreferrer">
									<i class="fa fa-edit"></i>
								</a>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
					<?php if ($this->total > $this->limit) : ?>
					<tfoot>
						<tr>
							<td colspan="7" align="center">
								<?php echo (new JPagination($this->total, $this->start, $this->limit))->getPaginationLinks(); ?>
							</td>
						</tr>
					</tfoot>
					<?php endif; ?>
				</table>
			</div>
		</div>
	</form>
	<script type="text/javascript">
		jQuery(function($) {
			$('#adminForm button[type="submit"]').click(function() {
				$('#adminForm').find('input[name="limitstart"]').val(0);
				$('#adminForm').submit();
			});

			$('#adminForm button[type="reset"]').click(function() {
				$('#adminForm').find('input, select').val('');
				$('#adminForm').submit();
			});

			<?php if ($callback) : ?>
			$('.select-article').click(function() {
				if (typeof window.parent['<?php echo $callback; ?>'] == 'function') {
					window.parent['<?php echo $callback; ?>']( $(this).attr('data-id'), $.trim( $(this).text() ) );
				}
			});
			<?php endif; ?>
		});
	</script>
</body>
</html>
