var loadBook = function(book){
	var bookid="#"+book;
	$(bookid).turn({
		width:922,
		height:550,
		elevation:50,
		gradients:true,
		autoCenter:true
	});

	$(bookid + "-viewport").hide();

	$(bookid + "-show").on("click", function(){

		$(bookid + "-viewport").show(300);
		// $("body").css({"overflow":"hidden"});

		$(document).on("keydown",function(e){
			switch(e.which){
				case 37:
					$(bookid).turn("previous");
					console.log("left key pressed");
					break;
				case 39:
					$(bookid).turn("next");
					console.log("right key pressed");
					break;
				case 27:
					$(bookid + "-viewport").hide(300);
					$(document).off("keydown",function(e){ });
					$(document).unbind("keydown");
					$("body").css({"overflow": "auto"});
					break;

			}
			e.preventDefault();
		});
	});

	$(bookid + "-hide").on("click", function(){

		$(bookid + "-viewport").hide(300);
		$("body").css({"overflow": "auto"});
		$(document).off("keydown",function(e){ });
		$(document).unbind("keydown");
	});

};


$(document).ready(function(){
	loadBook("fixemup");
	loadBook("engineerofthefuture");
	loadBook("techyhunt");
	loadBook("junkyardwars");
	loadBook("paperpresentation");
	loadBook("waterrocketry");
	loadBook("sanrachana");
	loadBook("paperplane");
	loadBook("selfpropellingvehicle");
	loadBook("cadmodelling");
	loadBook("mcquiz");

	loadBook("automobile");
	loadBook("3dprinting");
	loadBook("ornithopter");


});
