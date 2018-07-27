<!-- List of vendor: BEGIN -->
<script src="{{ skin_asset('js/libsuggest.js') }}"></script>
<!-- List of vendor: END -->

<!-- Hidden SUGGEST div -->
<div id="suggestWindow" class="suggestWindow d-none">
	<table id="suggestBlock" cellspacing="0" cellpadding="0" width="100%"></table>
	<a href="#" align="right" id="suggestClose">@lang('articles.close')</a>
</div>
<form name="DATA_tmp_storage" action="" id="DATA_tmp_storage"><input type="hidden" name="area" value="" /></form>

<script>

	// Global variable: ID of current active input area
	var currentInputAreaID = 'content';
	var form = document.getElementById('form_action');

	function preview()
	{

		if (form.content.value == '' || form.title.value == '') {
			$.notify({message: '@lang('articles.msge_preview')'},{type: 'danger'});
			return false;
		}

		form['mod'].value = "preview";
		form.target = "_blank";
		form.submit();

		form['mod'].value = "news";
		form.target = "_self";

		return true;
	}

	// HotKeys to this page
	document.onkeydown = function(e) {
		e = e || event;

		if (e.ctrlKey && e.keyCode == 'S'.charCodeAt(0)) {
			form.submit();
			return false;
		}
	}
	-->

	var searchDouble = function() {
		if ($.trim($('input[name=title]').val()).length < 4)
			return $.notify({message: '@lang('articles.msge_title')'},{type: 'danger'});
		var url = '{{-- admin_url --}}/rpc.php';
		var method = 'admin.news.double';
		var params = {'token': '{{-- token --}}','title': $('input[name=title]').val(),'mode': 'add',};
		$.reqJSON(url, method, params, function(json) {
			$('#searchDouble').html('');
			if (json.info) {
				$.notify({message:json.info},{type: 'info'});
			} else {
				var txt = '<ul class="alert alert-info list-unstyled alert-dismissible"><button type="button" class="close" data-dismiss="alert" >&times;</button>';
				$.each(json.data,function(index, value) {
					txt += '<li>#' +value.id+ ' &#9;&#9;<a href="'+value.url+'" target="_blank" class="alert-link">'+value.title +'</a></li>';
				});
				$('#searchDouble').html(txt+'</ul>');
			}
		});
	};
</script>
