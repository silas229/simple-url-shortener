$(document).ready(function () {
	$("#email").change(function () {
		$(this).parent().addClass("is-loading").find(".icon.is-right").remove();
		$.ajax({
			url: "../api/v1/checkEmail",
			data: $("#email").val(),
			method: "POST"
		}).done(function (data) {
			if (data == 1) {
				$("#email")
					.addClass("is-success").removeClass("is-danger")
					.parent().parent().find("p.help").addClass("is-success").removeClass("is-danger").html("This e-mail address is available!")
					.parent().find(".control").removeClass("is-loading").append('<span class="icon is-small is-right"><i class="fas fa-check has-text-success" aria-hidden="true"></i></span>');
			} else if (data == 0) {
				$("#email")
					.addClass("is-danger").removeClass("is-success")
					.parent().parent().find("p.help").addClass("is-danger").removeClass("is-success").html("This e-mail address is not available!")
					.parent().find(".control").removeClass("is-loading").append('<span class="icon is-small is-right"><i class="fas fa-exclamation-triangle has-text-danger" aria-hidden="true"></i></span>');
			}
		});
	});
	$("#password2").change(function () {
		if ($(this).val() !== $("#password").val()) {
			$(this)
				.addClass("is-danger")
				.parent().parent().find("p.help").addClass("is-danger").html("The passwords are not equal")
				.parent().find(".control").append('<span class="icon is-small is-right"><i class="fas fa-exclamation-triangle has-text-danger" aria-hidden="true"></i></span>');
		} else {
			$(this)
				.removeClass("is-danger")
				.parent().parent().find("p.help").removeClass("is-danger").html("")
				.parent().find(".icon.is-right").remove();
		
		}
	});
});
