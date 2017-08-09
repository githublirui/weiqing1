var indexPage = (function () {
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

	return {
		init: function () {
			this.swiperInit();
//			this.lazyImgInit();
//			this.eventHandler();
		},
		eventHandler: function () {
			//滚动条到底部追加数据
			$(window).scroll(function () {
				var scroll_top = document.body.scrollTop || document.documentElement.scrollTop;
				var scroll_height = document.body.scrollHeight || document.documentElement.scrollHeight;
				var client_height = document.documentElement.clientHeight;
				if ((scroll_top + client_height) > (scroll_height - 20)) {
					indexPage.scrollAppendInit(scrollData);
				}
			});
		},
		swiperInit: function () {
			//轮播器初始化
			new Swiper ('#swiper1', {
			    loop: true,
			    pagination: '.swiper-pagination'
		  	});      
		},
		lazyImgInit: function () {
			$("img.lazy").lazyload({
				threshold:-20,
				effect : "fadeIn",
				placeholder:"http://img.ui.cn/data/file/6/3/9/220936.gif"
			});
		},
		//滚动条到底部追加数据
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
		}
	}
})();
$(function () {
	indexPage.init();
});