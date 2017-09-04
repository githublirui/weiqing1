var releaseGoods = (function () {

	return {
		init: function () {
			this.eventHandler();
		},
		eventHandler: function () {
			//更多展开收起
			$(".more-open").click(function () {
				var text = $(this).text();
				if (text === "收起") {
					$(this).text("更多选项+");
					$(".release-goods-more").hide();
				} else {
					$(this).text("收起");
					$(".release-goods-more").show();
				}
			});
			//图片上传
			$("#file").on("change", function() {
				var $parent = $(this).parent();
				if (!this.files.length) return;
				var files = Array.prototype.slice.call(this.files);
				files.forEach(function(file, i) {
				  if (!/\/(?:jpeg|png|gif)/i.test(file.type)) return;
				  var reader = new FileReader();
				  reader.onload = function() {
					var result = this.result;
					$("<li><img src='"+result+"' /></li>").insertBefore($parent);
				  };
				  reader.readAsDataURL(file);
				})
			})
		}
	}
})();
$(function () {
	releaseGoods.init();
})