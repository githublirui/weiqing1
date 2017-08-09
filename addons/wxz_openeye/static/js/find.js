var findPage = (function () {
	//模仿滚动条到底部回调假数据
	var scrollData = [
		{
			bigImg: 'images/index_img.png',
			headImg: 'images/index_img.png',
			headTitle: '特条 | 雷神大战 SDCC雷神大战 SDCC雷神大战',
			tipTitle: '特条 | 雷神大战 SDCC雷神大战 SDCC雷神大战',
			date:'5天前'
		},
		{
			bigImg: 'images/index_img.png',
			headImg: 'images/index_img.png',
			headTitle: '特条 | 雷神大战 SDCC雷神大战 SDCC雷神大战',
			tipTitle: '特条 | 雷神大战 SDCC雷神大战 SDCC雷神大战',
			date:'5天前'
		},
		{
			bigImg: 'images/index_img.png',
			headImg: 'images/index_img.png',
			headTitle: '特条 | 雷神大战 SDCC雷神大战 SDCC雷神大战',
			tipTitle: '特条 | 雷神大战 SDCC雷神大战 SDCC雷神大战',
			date:'5天前'
		},
		{
			bigImg: 'images/index_img.png',
			headImg: 'images/index_img.png',
			headTitle: '特条 | 雷神大战 SDCC雷神大战 SDCC雷神大战',
			tipTitle: '特条 | 雷神大战 SDCC雷神大战 SDCC雷神大战',
			date:'5天前'
		},
		{
			bigImg: 'images/index_img.png',
			headImg: 'images/index_img.png',
			headTitle: '特条 | 雷神大战 SDCC雷神大战 SDCC雷神大战',
			tipTitle: '特条 | 雷神大战 SDCC雷神大战 SDCC雷神大战',
			date:'5天前'
		}
	];
	var Classifydata = [{},{},{}];
	return {
		scrollSelect: 0,
		init: function () {
			this.hotSwiperInit();
//			this.lazyImgInit();
			this.eventHandler();
		},
		eventHandler: function () {
			//滚动条到底部追加数据
//			$(window).scroll(function () {
//				var scroll_top = document.body.scrollTop || document.documentElement.scrollTop;
//				var scroll_height = document.body.scrollHeight || document.documentElement.scrollHeight;
//				var client_height = document.documentElement.clientHeight;
//				if ((scroll_top + client_height) > (scroll_height - 20)) {
//					/*热门加载数据*/
//					if (findPage.scrollSelect == 0) {
//						findPage.scrollAppendInit(scrollData);
//					} 
//					/*分类加载数据*/
//					else if (findPage.scrollSelect == 1) {
//						findPage.scrollClassifyInit(Classifydata)
//					}
//				}
//			});

			//导航点击事件
			$(".tabs a").on('touchstart mousedown',function(e){
			    e.preventDefault()
			    $(".tabs .active").removeClass('active')
			    $(this).addClass('active');
			    $('#hotTab, #classifyTab').hide();
			    $($(this).attr('href')).show();
			    //初始化轮播事件
			    var init = $(this).data('init');
			    var scrollSelect = $(this).data('scroll');
			    findPage.scrollSelect = scrollSelect;
			    if (init == 0) {
			    	$(this).data('init', '1');
			    	findPage.classifySwiperInit();
			    }
			})

		},
		classifySwiperInit: function () {
			new Swiper('#swiper2',{
				effect : 'coverflow',
				loop: true,
			    pagination: '#pagination2',
				slidesPerView: 1.1,
				centeredSlides: true,
				coverflow: {
				            rotate: 0,
				            stretch: 0,
				            depth: 0,
				            modifier: 0,
				            slideShadows : true
				        }
				})
			new Swiper('#swipers0',{
				effect : 'coverflow',
				loop: true,
			    pagination: '#paginations0',
				slidesPerView: 1.1,
				centeredSlides: true,
				coverflow: {
				            rotate: 0,
				            stretch: 0,
				            depth: 0,
				            modifier: 0,
				            slideShadows : true
				        }
				})
		},
		hotSwiperInit: function () {
			//轮播器初始化
			new Swiper ('#swiper1', {
			    loop: true,
			    pagination: '#pagination1'
		  	});      
		},
		lazyImgInit: function () {
			$("img.lazy").lazyload({
				threshold:-20,
				effect : "fadeIn",
				placeholder:"http://img.ui.cn/data/file/6/3/9/220936.gif"
			});
		},
		//滚动条到底部 热门板块追加数据
		scrollAppendInit: function (data) {
			var listsHtml = '';
			for (var i=0; i < data.length; i++) {
				listsHtml += '<li>'
				+'		<a href="javascript:;">'
				+'			<img class="bigImg" src="'+ data[i].bigImg +'" />'
				+'		</a>'
				+'		<div class="bigImg-bottom">'
				+'			<div class="image"><img src="'+ data[i].headImg +'" /></div>'
				+'			<div class="image-center">'
				+'				<h2 class="text-over">'+ data[i].headTitle +'</h2>'
				+'				<p class="text-over">'+ data[i].tipTitle +'</p>'
				+'			</div>'
				+'			<div class="image-right text-over">'+ data[i].date +'</div>'
				+'		</div>'
				+'	</li>';
			}
			$("#hotLists").append(listsHtml);
		},
		//滚动条到底部 分类板块追加数据
		scrollClassifyInit: function (data) {
			var scrollNumber = 0,
				swiperNumber = 0;
			this.scrollClassifyInit = function (data) {
				for (var i=0; i < data.length; i++) {
					scrollNumber++;
					var scrollHtml = '<li class="classifyList">'
						+'		<div class="classifyList-top">'
						+'			<h2>开 胃</h2>'
						+'			<p>眼球和味蕾，一个都不放过</p>'
						+'			<a href="#" class="concern">+关注</a>'
						+'		</div>'
						+'		<div class="swiper-container swipers" id="swipers'+ scrollNumber +'">'
						+'		    <div class="swiper-wrapper">'
						+'		        <div class="swiper-slide">'
						+'		        	<a href="javascript:;">'
						+'		        		<img src="images/swiper.png" />'
						+'		        		<h4>一分钟学烹饪：美味佳肴</h4>'
						+'		        		<p>#开胃/4.0</p>'
						+'		        	</a>'
						+'		        </div>'
						+'		        <div class="swiper-slide">'
						+'		        	<a href="javascript:;">'
						+'		        		<img src="images/swiper.png" />'
						+'		        		<h4>一分钟学烹饪：美味佳肴</h4>'
						+'		        		<p>#开胃/4.0</p>'
						+'		        	</a>'
						+'		        </div>'
						+'		        <div class="swiper-slide">'
						+'		        	<a href="javascript:;">'
						+'		        		<img src="images/swiper.png" />'
						+'		        		<h4>一分钟学烹饪：美味佳肴</h4>'
						+'		        		<p>#开胃/4.0</p>'
						+'		        	</a>'
						+'		        </div>'
						+'		    </div>'
						+'		    <div class="swiper-pagination paginations" id="paginations'+ scrollNumber +'"></div>'
						+'		</div>'
						+'	</li>';
				}
				$('#classifyLists').append(scrollHtml);
				//初始化滑动事件
				for (var i=0; i < data.length; i++) {
					swiperNumber++;
					new Swiper('#swipers'+swiperNumber,{
						effect : 'coverflow',
						loop: true,
					    pagination: '#paginations'+swiperNumber,
						slidesPerView: 1.1,
						centeredSlides: true,
						coverflow: {
					            rotate: 0,
					            stretch: 0,
					            depth: 0,
					            modifier: 0,
					            slideShadows : true
					        }
					})
				}
			}
			findPage.scrollClassifyInit(data);
		}
	}
})();
$(function () {
	findPage.init();
});
