var validate = false;
$(document).ready(function () {
playAnimation();	
    popup();

});




function fnvalidation() {
    if (errors == 0) {
        $("#btnthanks").click();
        setTimeout(function () {
             window.location = "https://www.khelplayrummy.com/";
        }, 3000);
    }

}


function popup() {
    /*  Start Script Custom Popup */
    $.fn.expose = function (options) {
        var $modal = $(this), $trigger = $("a[rel=" + this.selector + "]");

        $modal.on("expose:open", function () {
            $modal.addClass("is-visible");
            $modal.trigger("expose:opened");
        });

        $modal.on("expose:close", function () {
            $modal.removeClass("is-visible");
            $modal.trigger("expose:closed");
        });

        $trigger.on("click", function (e) {
            $modal.trigger("expose:open");
        });

        $modal.add($modal.find(".close")).on("click", function (e) {
            if (e.target !== this)
                return;
            $modal.trigger("expose:close");
        });
        return;
    }

    $("#atc").expose();

    $(".cancel").on("click", function (e) {
        $(this).trigger("expose:close");
    })
    /*  End Script Custom Popup */;

    /*  Start Script Custom Popup Thank you */
    $.fn.thankyou = function (options) {
        var $modal = $(this), $trigger = $("a[rel=" + this.selector + "]");

        $modal.on("expose:open", function () {
            $modal.addClass("is-visible");
            $modal.trigger("expose:opened");
        });

        $modal.on("expose:close", function () {
            $modal.removeClass("is-visible");
            $modal.trigger("expose:closed");
        });

        $trigger.on("click", function (e) {
            $modal.trigger("expose:open");
        });

        $modal.add($modal.find(".close")).on("click", function (e) {
            if (e.target !== this)
                return;
            //    $modal.trigger("expose:close");
        });
        return;
    }

    $("#thankmsg").thankyou();
    /*  End Script Custom Popup */;
}



$(window).load(function(e) {
    resizeDiv()	
	
});
	var sHeight;
	var sWidth;
	var timer = 500;
	function resizeDiv(){
			sHeight = $('.sliderPgIn').height()
			sWidth = $('.sliderPgIn').width()	
			var thisWidth = $(this).width()	
			$(".autoSliser,.slideDiv").css({"width": sWidth + "px","height": sHeight + "px"})	
			$(".slideDiv").css("left", sWidth + "px")
			$(".slideDiv.active").css("left", "0px")
		}	
	
	$(function(){		
			
			$('.sliderPgIn').append('<div class="pagination"></div>')
			var slideDivLenth = $('.slideDiv').length
			
			for(i=0; i<slideDivLenth; i++){
				$('.pagination').append('<div class="pageNo"></div>');
			}
			
			$('.pagination').children().each(function(o) {
				var pageNoNo = o + 1
				$(this).html(pageNoNo)
                $(this).click(function() {
					if($(this).hasClass("pageNoActive") || $(this).hasClass("pageNoAct"))
					{
						console.log("if")
					}else{
						stpoAnimation();
						$(".pageNo").removeClass("pageNoActive").addClass("pageNoAct")
						$(this).addClass("pageNoActive")
						$(".slideDiv.active").removeClass("active").animate({left: -sWidth + "px"}, timer, function(){$(this).css("left",sWidth + "px")})
						$(".onSlid" + pageNoNo).addClass("active").css("left",sWidth + "px").animate({left:"0px"}, timer, playAnimation)
						console.log("else")
					}
					//console.log(sWidth)			
                });
            });	
			$(".pageNo:first").addClass("pageNoActive")		
	})	
	
	function stpoAnimation(){
		clearInterval(myAnimation)						
		}
		
	function activePageLink(){
		playAnimation()
		}
		
	var myAnimation;	
	function playAnimation(){
			$(".pageNo").removeClass("pageNoAct")
			 myAnimation = setInterval (animateSlider, 5000)						
		}
		/* ========================= Slider Left slide animation  ============================================	*/
	function animateSlider(){			
			if($(".slideDiv.active").is(":last-child")){
					$(".pageNo.pageNoActive").removeClass("pageNoActive")
					$(".pageNo:first").addClass("pageNoActive")
					$(".slideDiv.active").removeClass("active").animate({left: -sWidth + "px"}, timer, function(){$(this).css("left",sWidth + "px")})
					$(".slideDiv:first").addClass("active").css("left",sWidth + "px").animate({left:"0px"}, timer)
					//console.log("if")	
			} else {
					$(".pageNo.pageNoActive").removeClass("pageNoActive").next().addClass("pageNoActive")
					$(".slideDiv.active").removeClass("active").next().addClass("active").css("left",sWidth + "px").animate({left:"0px"}, timer)
					$(".slideDiv.active").prev().animate({left: -sWidth + "px"}, timer, function(){$(this).css("left",sWidth + "px")})			
					//console.log("else")
				}				
		}
		
$(document).ready(function(e) {
	
	var resiF;
	$(window).on('load resize', function () {
		clearTimeout(resiF)
		resiF = setTimeout(responsiveSlider,50)
	});
});

function responsiveSlider(){
		var mainDiv = $(".slidBox")
		mainDiv.each(function(index, element) {
			var thisM = $(this)
			var wrapDiv = thisM.find(".slideDivWrap")
			var thDiv = thisM.find(".thDiv")
			var slideDiv = thisM.find(".slideDiv1")
			
			
			var div_W = wrapDiv.width()			
			var div_L = thDiv.length
			var slideDiv_L = div_W * div_L
						
			slideDiv.css({"width": slideDiv_L + "px","left":"0"})
			thDiv.css({"width": div_W + "px"})
			$(".btn.prev").addClass("disable")
			   
        });
	}
	
	$(function(){
			var mainDiv = $(".slidBox")				
			mainDiv.append('<div class="btn prev"></div><div class="btn next"></div>')			
				$(".btn").click(function(e) {
                    var thisBtn = $(this);
					var thisParent = thisBtn.parents(".slidBox")					
					var thDiv = thisParent.find(".thDiv")
					var AslideDiv = thisParent.find(".slideDiv1")
					var btnNext = thisParent.find(".next")
					var btnPrev = thisParent.find(".prev")
					
					var divW = thDiv.width();
					var divL = thDiv.length
					var mWidth = (divW * divL) 
					var AslideDivW = AslideDiv.width()- divW
					
					if($(this).hasClass("next")){
							if(AslideDiv.css("left") === -AslideDivW + "px"){
							}else{
									if(thisBtn.hasClass("active") || thisBtn.hasClass("disable")){
									}else{										
										thisBtn.addClass("active")
										AslideDiv.animate({left: '-=' +divW + "px"},function(){ 
											thisBtn.removeClass("active");
											checkEnd()
										 })
									}
								
							}
					}else{
							if(AslideDiv.css("left") === "0px"){
							}else{
									if(thisBtn.hasClass("active") || thisBtn.hasClass("disable")){
								
									}else{
										thisBtn.addClass("active")
										AslideDiv.animate({left: '+=' +divW + "px"},function(){ 
											thisBtn.removeClass("active");	
											checkEnd()									
										 })
									}
							}
					}
					
					function checkEnd(){							
							if(AslideDiv.css("left") === -AslideDivW + "px"){
								btnNext.addClass("disable")									
							}else{
								btnNext.removeClass("disable")							
							}							
							if(AslideDiv.css("left") === "0px"){
								btnPrev.addClass("disable")																
							}else{
								btnPrev.removeClass("disable")							
							}
						}  // end function checkEnd
					
					
                });	
		});
		
		
$(document).ready(function(){
	 iconScroll()
	
	if ($(window).width() < 980) {
	
	 $(".terms").click(function(e) {
        $("html, body").animate({
            scrollTop:2600
        }, "slow");
    });
	
	}else{
		$(".terms").click(function(e) {
        $("html, body").animate({
            scrollTop: 1900
        }, "slow");
    });
		
}

$(".arrow-click").click(function(e) {
        $("html, body").animate({
            scrollTop: 590
        }, "slow");
    });
	
	
	function iconScroll() {
	$(".iconScroll").animate({bottom: '+=8px'}, 700);
	$(".iconScroll").animate({bottom: '0px'}, 700, iconScroll);
	};
	$(".iconScroll").mouseenter(function() {
	$(".iconScroll").stop(true, false);
	}); 
	$(".iconScroll").mouseleave(function() {
	iconScroll();
	});

	
	
});
