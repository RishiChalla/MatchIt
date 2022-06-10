$(document).ready(function() {
	$("#newTerm").click(function() {
		var count = $(".game").children().length + 1;
		$(".game").append('<div class="term"><div class="row"><div class="col-md-6"><br><textarea name="term'+count+'" class="form-control" style="height:100px;min-height:100px;max-height:100px;" placeholder="Term"></textarea></div><div class="col-md-6"><br><textarea name="definition'+count+'" class="form-control" style="height:100px;min-height:100px;max-height:100px;" placeholder="Definition"></textarea></div></div><br><button type="button" class="removeTerm btn btn-block btn-danger">Remove Term</button></div>');
		setHandler();
	});
	setHandler();
});

function setHandler() {
	$(".removeTerm").click(function() {
		$(this).parent().remove();
		var count = 0;
		$(".game").children().each(function() {
			count += 1;
			var count2 = 0;
			$(this).find('textarea').each(function() {
				count2 += 1;
				if (count2 == 1) {
					$(this).attr('name', 'term'+count);
				}
				else {
					$(this).attr('name', 'definition'+count);
				}
			});
		});
	});
}