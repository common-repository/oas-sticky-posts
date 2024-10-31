// http://onlineassociates.ae/
// JqueryUI Drag and Drop functionality not working properly as it conflicts with the wordpress built-in JqueryUI (legacy version)

jQuery(document).ready( function($) {

	if('#oawp-favourite-posts-widget')
	{
			$("#oawp-favourite-posts-widget").sortable({
				items: "li",
				update: function(event, ui) {
					ListReArrange(ui.item);
				}
			});
		
		function ListReArrange(elem)
		{
			var CompleteString = $("#oawp-favourite-posts-widget").sortable('toArray');
			$('#oawp-favourite-posts-widget-order').val(CompleteString);
		}
	}
	
	
	if('#table-favourite-posts-list')
	{
		$("#table-favourite-posts-list").tablesorter({sorter: false, widgets: ['zebra']});
		
		$("#table-favourite-posts-list tr").mouseover(function() {
			$(this).addClass("over");
		}).mouseout(function() {
			$(this).removeClass("over");
		});
	}
	
	//$("#table-favourite-posts-list tr:even").addClass("alternate");
	
	
});	