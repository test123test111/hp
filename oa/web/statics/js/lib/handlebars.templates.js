this["aimeilive"] = this["aimeilive"] || {};
this["aimeilive"]["banner"] = Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "	<li class=\"swiper-slide\"><a href=\""
    + escapeExpression(((helper = (helper = helpers.url || (depth0 != null ? depth0.url : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"url","hash":{},"data":data}) : helper)))
    + "\"><img src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.banner || (depth0 != null ? depth0.banner : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"banner","hash":{},"data":data}) : helper)))
    + "\"></a></li>\n";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing;
  stack1 = ((helper = (helper = helpers.bannerList || (depth0 != null ? depth0.bannerList : depth0)) != null ? helper : helperMissing),(options={"name":"bannerList","hash":{},"fn":this.program(1, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.bannerList) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { return stack1; }
  else { return ''; }
  },"useData":true});
this["aimeilive"]["brandGroup"] = Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<li data-id=\""
    + escapeExpression(((helper = (helper = helpers.stock_id || (depth0 != null ? depth0.stock_id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"stock_id","hash":{},"data":data}) : helper)))
    + "\">\n	<img class=\"lazy\" data-original=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.imgs || (depth0 != null ? depth0.imgs : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"imgs","hash":{},"data":data}) : helper)))
    + "\" src=\"../images/stockbg.png\" alt=\""
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "\">\n	<div>\n		<h2>"
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "</h2>\n		<footer>\n			<span>￥"
    + escapeExpression(((helper = (helper = helpers.price || (depth0 != null ? depth0.price : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"price","hash":{},"data":data}) : helper)))
    + "</span><button class=\"btn-primary btn\">购买</button>\n		</footer>\n	</div>\n</li>";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing;
  stack1 = ((helper = (helper = helpers.rst || (depth0 != null ? depth0.rst : depth0)) != null ? helper : helperMissing),(options={"name":"rst","hash":{},"fn":this.program(1, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.rst) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { return stack1; }
  else { return ''; }
  },"useData":true});
this["aimeilive"]["buyer"] = Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, lambda=this.lambda, buffer = "<header class=\"buyer\" style=\"background-image:url("
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.background_pic || (depth0 != null ? depth0.background_pic : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"background_pic","hash":{},"data":data}) : helper)))
    + ")\">\n	<img class=\"avatar\" src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.head || (depth0 != null ? depth0.head : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"head","hash":{},"data":data}) : helper)))
    + "\" alt=\""
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "\">\n	<strong>"
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "<i class=\"level level"
    + escapeExpression(lambda(((stack1 = (depth0 != null ? depth0.level : depth0)) != null ? stack1.type : stack1), depth0))
    + "\" style=\"width:"
    + escapeExpression(lambda(((stack1 = (depth0 != null ? depth0.level : depth0)) != null ? stack1.num : stack1), depth0))
    + "rem\"></i></strong>\n	<p class=\"signature\">"
    + escapeExpression(((helper = (helper = helpers.signature || (depth0 != null ? depth0.signature : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"signature","hash":{},"data":data}) : helper)))
    + "</p>\n	<p class=\"fans\">粉丝 <em>"
    + escapeExpression(((helper = (helper = helpers.fans || (depth0 != null ? depth0.fans : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"fans","hash":{},"data":data}) : helper)))
    + "</em>　/　<i class=\"icon-address\"></i><em>"
    + escapeExpression(((helper = (helper = helpers.country || (depth0 != null ? depth0.country : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"country","hash":{},"data":data}) : helper)))
    + " "
    + escapeExpression(((helper = (helper = helpers.city || (depth0 != null ? depth0.city : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"city","hash":{},"data":data}) : helper)))
    + "</em></p>\n</header>\n\n";
  stack1 = helpers['if'].call(depth0, (depth0 != null ? depth0.desc : depth0), {"name":"if","hash":{},"fn":this.program(2, data),"inverse":this.noop,"data":data});
  if (stack1 != null) { buffer += stack1; }
  return buffer;
},"2":function(depth0,helpers,partials,data) {
  var stack1, buffer = "<h2 class=\"line-title\"><strong>买家评价</strong></h2>\n<ul class=\"tag-list\">\n";
  stack1 = helpers.each.call(depth0, (depth0 != null ? depth0.desc : depth0), {"name":"each","hash":{},"fn":this.program(3, data),"inverse":this.noop,"data":data});
  if (stack1 != null) { buffer += stack1; }
  return buffer + "</ul>\n";
},"3":function(depth0,helpers,partials,data) {
  var lambda=this.lambda, escapeExpression=this.escapeExpression;
  return "	<li>"
    + escapeExpression(lambda((data && data.key), depth0))
    + " "
    + escapeExpression(lambda(depth0, depth0))
    + "</li>\n";
},"5":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing, buffer = "<h2 class=\"line-title\"><strong>正在直播</strong></h2>	\n	";
  stack1 = ((helper = (helper = helpers.living || (depth0 != null ? depth0.living : depth0)) != null ? helper : helperMissing),(options={"name":"living","hash":{},"fn":this.program(6, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.living) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  return buffer + "\n";
},"6":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<div id=\"living\" class=\"living\" data-id=\""
    + escapeExpression(((helper = (helper = helpers.id || (depth0 != null ? depth0.id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"id","hash":{},"data":data}) : helper)))
    + "\">\n		<div style=\"background-image:url("
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.livingImg || (depth0 != null ? depth0.livingImg : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"livingImg","hash":{},"data":data}) : helper)))
    + ");\">\n			<h2>"
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "</h2>\n			<p>"
    + escapeExpression(((helper = (helper = helpers.brands_label || (depth0 != null ? depth0.brands_label : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"brands_label","hash":{},"data":data}) : helper)))
    + "</p>\n		</div>\n		<footer>\n			<span><img class=\"flag\" src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.country_flag || (depth0 != null ? depth0.country_flag : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"country_flag","hash":{},"data":data}) : helper)))
    + "\" alt=\""
    + escapeExpression(((helper = (helper = helpers.country || (depth0 != null ? depth0.country : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"country","hash":{},"data":data}) : helper)))
    + "国旗\">"
    + escapeExpression(((helper = (helper = helpers.country || (depth0 != null ? depth0.country : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"country","hash":{},"data":data}) : helper)))
    + " "
    + escapeExpression(((helper = (helper = helpers.user_num || (depth0 != null ? depth0.user_num : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"user_num","hash":{},"data":data}) : helper)))
    + "人观看</span><em><i class=\"icon-clock\"></i>还剩<time class=\"countDown\">"
    + escapeExpression(((helper = (helper = helpers.displayTime || (depth0 != null ? depth0.displayTime : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"displayTime","hash":{},"data":data}) : helper)))
    + "</time></em>\n		</footer>\n		\n	</div>";
},"8":function(depth0,helpers,partials,data) {
  var stack1, helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, buffer = "<li data-id="
    + escapeExpression(((helper = (helper = helpers.id || (depth0 != null ? depth0.id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"id","hash":{},"data":data}) : helper)))
    + " data-type="
    + escapeExpression(((helper = (helper = helpers.type || (depth0 != null ? depth0.type : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"type","hash":{},"data":data}) : helper)))
    + ">\n		<img class=\"lazy\" data-original=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.img || (depth0 != null ? depth0.img : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"img","hash":{},"data":data}) : helper)))
    + "\" src=\"../images/stockbg.png\" alt=\"商品图片\">\n		";
  stack1 = helpers['if'].call(depth0, (depth0 != null ? depth0.price : depth0), {"name":"if","hash":{},"fn":this.program(9, data),"inverse":this.noop,"data":data});
  if (stack1 != null) { buffer += stack1; }
  return buffer + "\n	</li>";
},"9":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<span>￥"
    + escapeExpression(((helper = (helper = helpers.price || (depth0 != null ? depth0.price : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"price","hash":{},"data":data}) : helper)))
    + "</span>";
},"11":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<li data-id=\""
    + escapeExpression(((helper = (helper = helpers.id || (depth0 != null ? depth0.id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"id","hash":{},"data":data}) : helper)))
    + "\">\n		<div style=\"background-image:url("
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.livedImg || (depth0 != null ? depth0.livedImg : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"livedImg","hash":{},"data":data}) : helper)))
    + ");\">\n			<h2>"
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "</h2>\n			<p>"
    + escapeExpression(((helper = (helper = helpers.brands_label || (depth0 != null ? depth0.brands_label : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"brands_label","hash":{},"data":data}) : helper)))
    + "</p>\n		</div>\n		<footer>\n			<span><img class=\"flag\" src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.country_flag || (depth0 != null ? depth0.country_flag : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"country_flag","hash":{},"data":data}) : helper)))
    + "\" alt=\""
    + escapeExpression(((helper = (helper = helpers.country || (depth0 != null ? depth0.country : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"country","hash":{},"data":data}) : helper)))
    + "国旗\">"
    + escapeExpression(((helper = (helper = helpers.country || (depth0 != null ? depth0.country : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"country","hash":{},"data":data}) : helper)))
    + " "
    + escapeExpression(((helper = (helper = helpers.user_num || (depth0 != null ? depth0.user_num : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"user_num","hash":{},"data":data}) : helper)))
    + "人观看</span><time><i class=\"icon-clock\"></i>直播已结束</time>\n		</footer>\n	</li>";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing, escapeExpression=this.escapeExpression, buffer = "";
  stack1 = ((helper = (helper = helpers.buyerInfo || (depth0 != null ? depth0.buyerInfo : depth0)) != null ? helper : helperMissing),(options={"name":"buyerInfo","hash":{},"fn":this.program(1, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.buyerInfo) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  stack1 = helpers['if'].call(depth0, (depth0 != null ? depth0.living : depth0), {"name":"if","hash":{},"fn":this.program(5, data),"inverse":this.noop,"data":data});
  if (stack1 != null) { buffer += stack1; }
  buffer += "<menu class=\"tabs\" id=\"tabs\"><span class=\"active\" id=\"buyerRecommend\">买手推荐("
    + escapeExpression(((helper = (helper = helpers.recommended_count || (depth0 != null ? depth0.recommended_count : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"recommended_count","hash":{},"data":data}) : helper)))
    + ")</span><span id=\"success\">成功直播("
    + escapeExpression(((helper = (helper = helpers.lived_count || (depth0 != null ? depth0.lived_count : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"lived_count","hash":{},"data":data}) : helper)))
    + ")</span></menu>\n<ul class=\"buyer-recommend\" id=\"recommend\">\n	";
  stack1 = ((helper = (helper = helpers.recommended || (depth0 != null ? depth0.recommended : depth0)) != null ? helper : helperMissing),(options={"name":"recommended","hash":{},"fn":this.program(8, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.recommended) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  buffer += "\n</ul>\n<ul class=\"success-live\" id=\"successLive\">\n	";
  stack1 = ((helper = (helper = helpers.lived || (depth0 != null ? depth0.lived : depth0)) != null ? helper : helperMissing),(options={"name":"lived","hash":{},"fn":this.program(11, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.lived) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  return buffer + "\n</ul>\n<div id=\"loadingBar\"><i class=\"loading-icon\"></i>加载中，请稍候...</div>";
},"useData":true});
this["aimeilive"]["buyerList"] = Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, helper, options, lambda=this.lambda, escapeExpression=this.escapeExpression, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing, buffer = "	<li data-id=\""
    + escapeExpression(lambda(((stack1 = (depth0 != null ? depth0.buyerInfo : depth0)) != null ? stack1.id : stack1), depth0))
    + "\">\n";
  stack1 = ((helper = (helper = helpers.buyerInfo || (depth0 != null ? depth0.buyerInfo : depth0)) != null ? helper : helperMissing),(options={"name":"buyerInfo","hash":{},"fn":this.program(2, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.buyerInfo) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  buffer += "		<ul class=\"buyer-recommend\">\n			";
  stack1 = ((helper = (helper = helpers.stateList || (depth0 != null ? depth0.stateList : depth0)) != null ? helper : helperMissing),(options={"name":"stateList","hash":{},"fn":this.program(5, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.stateList) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  return buffer + "\n		</ul>\n	</li>\n";
},"2":function(depth0,helpers,partials,data) {
  var stack1, helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, lambda=this.lambda, buffer = "		<header>\n			<img class=\"avatar\" src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.head || (depth0 != null ? depth0.head : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"head","hash":{},"data":data}) : helper)))
    + "\" alt=\""
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "\">\n			<div>\n				<strong>"
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "<i class=\"level level"
    + escapeExpression(lambda(((stack1 = (depth0 != null ? depth0.level : depth0)) != null ? stack1.type : stack1), depth0))
    + "\" style=\"width:"
    + escapeExpression(lambda(((stack1 = (depth0 != null ? depth0.level : depth0)) != null ? stack1.num : stack1), depth0))
    + "rem\"></i></strong>\n				<p><span>粉丝</span> "
    + escapeExpression(((helper = (helper = helpers.fans || (depth0 != null ? depth0.fans : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"fans","hash":{},"data":data}) : helper)))
    + "<i class=\"icon-address\"></i>"
    + escapeExpression(((helper = (helper = helpers.country || (depth0 != null ? depth0.country : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"country","hash":{},"data":data}) : helper)))
    + " "
    + escapeExpression(((helper = (helper = helpers.city || (depth0 != null ? depth0.city : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"city","hash":{},"data":data}) : helper)))
    + "</p>\n			</div>\n		</header>\n		";
  stack1 = helpers['if'].call(depth0, (depth0 != null ? depth0.signature : depth0), {"name":"if","hash":{},"fn":this.program(3, data),"inverse":this.noop,"data":data});
  if (stack1 != null) { buffer += stack1; }
  return buffer + "\n";
},"3":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<p><sup>“</sup>"
    + escapeExpression(((helper = (helper = helpers.signature || (depth0 != null ? depth0.signature : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"signature","hash":{},"data":data}) : helper)))
    + "<sub>”</sub></p>";
},"5":function(depth0,helpers,partials,data) {
  var stack1, helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, buffer = "<li><img class=\"lazy\" data-original=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.img || (depth0 != null ? depth0.img : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"img","hash":{},"data":data}) : helper)))
    + "\" src=\"../images/stockbg.png\" alt=\"商品图片\">";
  stack1 = helpers['if'].call(depth0, (depth0 != null ? depth0.price : depth0), {"name":"if","hash":{},"fn":this.program(6, data),"inverse":this.noop,"data":data});
  if (stack1 != null) { buffer += stack1; }
  return buffer + "</li>";
},"6":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<span>￥"
    + escapeExpression(((helper = (helper = helpers.price || (depth0 != null ? depth0.price : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"price","hash":{},"data":data}) : helper)))
    + "</span>";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing;
  stack1 = ((helper = (helper = helpers.list || (depth0 != null ? depth0.list : depth0)) != null ? helper : helperMissing),(options={"name":"list","hash":{},"fn":this.program(1, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.list) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { return stack1; }
  else { return ''; }
  },"useData":true});
this["aimeilive"]["categoryPrimary"] = Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "	<li data-id=\""
    + escapeExpression(((helper = (helper = helpers.id || (depth0 != null ? depth0.id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"id","hash":{},"data":data}) : helper)))
    + "\" class=\"clearfix\">\n		<p>"
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "</p>	\n		<i class=\"icon-right\"></i>\n		<em class=\"touch-style\"></em>	\n	</li>\n";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing, buffer = "<ul id=\"primaryList\" class=\"list\">\n";
  stack1 = ((helper = (helper = helpers.pcate || (depth0 != null ? depth0.pcate : depth0)) != null ? helper : helperMissing),(options={"name":"pcate","hash":{},"fn":this.program(1, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.pcate) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  return buffer + "</ul>\n<footer>\n	<button id=\"changeBtn\" class=\"change-btn\">不知道！换一个</button>\n</footer>\n\n";
},"useData":true});
this["aimeilive"]["categorySecond"] = Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "	<li data-id=\""
    + escapeExpression(((helper = (helper = helpers.id || (depth0 != null ? depth0.id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"id","hash":{},"data":data}) : helper)))
    + "\" class=\"clearfix\">\n		<p>"
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "</p>	\n		<i class=\"icon-right\"></i>\n		<em class=\"touch-style\"></em>	\n	</li>\n";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing, buffer = "<ul id=\"secondList\" class=\"list\">\n";
  stack1 = ((helper = (helper = helpers.scate || (depth0 != null ? depth0.scate : depth0)) != null ? helper : helperMissing),(options={"name":"scate","hash":{},"fn":this.program(1, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.scate) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  return buffer + "</ul>\n";
},"useData":true});
this["aimeilive"]["deals"] = Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing, buffer = "<div class=\"device1\">\n	<div class=\"swiper-container\" id=\"swiper-container1\">\n		<ul id=\"banner\" class=\"swiper-wrapper\">\n";
  stack1 = ((helper = (helper = helpers.banner || (depth0 != null ? depth0.banner : depth0)) != null ? helper : helperMissing),(options={"name":"banner","hash":{},"fn":this.program(2, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.banner) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  buffer += "		</ul>\n	</div>\n	<div class=\"paginationWrap\"><div class=\"pagination\" id=\"pagination1\"></div></div>\n</div>\n\n<figure class=\"operations\">\n";
  stack1 = ((helper = (helper = helpers.secondBanner || (depth0 != null ? depth0.secondBanner : depth0)) != null ? helper : helperMissing),(options={"name":"secondBanner","hash":{},"fn":this.program(4, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.secondBanner) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  buffer += "</figure>\n\n<h2 class=\"title\">每日特价</h2>\n<div class=\"device2\">\n	<div class=\"swiper-container\" id=\"swiper-container2\">\n		<ul id=\"dealsStock\" class=\"stock-list swiper-wrapper\">\n			";
  stack1 = ((helper = (helper = helpers.sale || (depth0 != null ? depth0.sale : depth0)) != null ? helper : helperMissing),(options={"name":"sale","hash":{},"fn":this.program(6, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.sale) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  buffer += "\n		</ul>\n	</div>\n	<div class=\"paginationWrap\"><div class=\"pagination\" id=\"pagination2\"></div></div>\n</div>\n\n<h2>TOP10热卖榜</h2>\n<ul id=\"hotStock\" class=\"stock-wall\">\n	";
  stack1 = ((helper = (helper = helpers.top10 || (depth0 != null ? depth0.top10 : depth0)) != null ? helper : helperMissing),(options={"name":"top10","hash":{},"fn":this.program(8, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.top10) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  return buffer + "\n</ul>\n";
},"2":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "			<li class=\"swiper-slide\"><a href=\""
    + escapeExpression(((helper = (helper = helpers.url || (depth0 != null ? depth0.url : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"url","hash":{},"data":data}) : helper)))
    + "\"><img src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.image_path || (depth0 != null ? depth0.image_path : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"image_path","hash":{},"data":data}) : helper)))
    + "\"></a></li>\n";
},"4":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "	<a href=\""
    + escapeExpression(((helper = (helper = helpers.url || (depth0 != null ? depth0.url : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"url","hash":{},"data":data}) : helper)))
    + "\"><img src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.image_path || (depth0 != null ? depth0.image_path : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"image_path","hash":{},"data":data}) : helper)))
    + "\"></a>\n";
},"6":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<li class=\"swiper-slide\" data-id=\""
    + escapeExpression(((helper = (helper = helpers.id || (depth0 != null ? depth0.id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"id","hash":{},"data":data}) : helper)))
    + "\">\n				<figure>\n					<img src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.stockImg || (depth0 != null ? depth0.stockImg : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"stockImg","hash":{},"data":data}) : helper)))
    + "\" alt=\"../images/stockbg.png\">\n					<span class=\"tag\">"
    + escapeExpression(((helper = (helper = helpers.discount || (depth0 != null ? depth0.discount : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"discount","hash":{},"data":data}) : helper)))
    + "折</span>\n				</figure>\n				<div>\n					<h3>"
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "</h3>\n					<del>￥"
    + escapeExpression(((helper = (helper = helpers.original_price || (depth0 != null ? depth0.original_price : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"original_price","hash":{},"data":data}) : helper)))
    + "</del>\n					<footer>\n						<strong><sub>￥</sub>"
    + escapeExpression(((helper = (helper = helpers.priceout || (depth0 != null ? depth0.priceout : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"priceout","hash":{},"data":data}) : helper)))
    + "</strong>\n						<button class=\"btn-primary btn\">抢购</button>\n					</footer>\n				</div>\n			</li>";
},"8":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<li data-id=\""
    + escapeExpression(((helper = (helper = helpers.id || (depth0 != null ? depth0.id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"id","hash":{},"data":data}) : helper)))
    + "\">\n		<img class=\"lazy\" data-original=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.stockImg || (depth0 != null ? depth0.stockImg : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"stockImg","hash":{},"data":data}) : helper)))
    + "\" src=\"../images/stockbg.png\" alt=\""
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "\">\n		<footer>\n			<h3>"
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "</h3>\n			<span>￥"
    + escapeExpression(((helper = (helper = helpers.priceout || (depth0 != null ? depth0.priceout : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"priceout","hash":{},"data":data}) : helper)))
    + "</span>\n		</footer>\n	</li>";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing;
  stack1 = ((helper = (helper = helpers.rst || (depth0 != null ? depth0.rst : depth0)) != null ? helper : helperMissing),(options={"name":"rst","hash":{},"fn":this.program(1, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.rst) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { return stack1; }
  else { return ''; }
  },"useData":true});
this["aimeilive"]["flashSale"] = Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<li data-id=\""
    + escapeExpression(((helper = (helper = helpers.stock_id || (depth0 != null ? depth0.stock_id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"stock_id","hash":{},"data":data}) : helper)))
    + "\">\n	<img class=\"lazy\" data-original=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.img || (depth0 != null ? depth0.img : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"img","hash":{},"data":data}) : helper)))
    + "\" src=\"../images/stockbg.png\" alt=\""
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "\">\n	<div>\n		<strong><sub>￥</sub>"
    + escapeExpression(((helper = (helper = helpers.priceout || (depth0 != null ? depth0.priceout : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"priceout","hash":{},"data":data}) : helper)))
    + "</strong><del>￥"
    + escapeExpression(((helper = (helper = helpers.original_price || (depth0 != null ? depth0.original_price : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"original_price","hash":{},"data":data}) : helper)))
    + "</del>\n		<h2>"
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "</h2>\n		<footer>\n			<span><i class=\"icon-address\"></i> "
    + escapeExpression(((helper = (helper = helpers.area || (depth0 != null ? depth0.area : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"area","hash":{},"data":data}) : helper)))
    + "</span><i class=\"icon-clock\"></i>\n			<time data-start=\""
    + escapeExpression(((helper = (helper = helpers.begin_time || (depth0 != null ? depth0.begin_time : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"begin_time","hash":{},"data":data}) : helper)))
    + "\" data-end=\""
    + escapeExpression(((helper = (helper = helpers.end_time || (depth0 != null ? depth0.end_time : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"end_time","hash":{},"data":data}) : helper)))
    + "\" class=\"countDown\"></time>\n		</footer>\n	</div>\n	<span class=\"tag\">"
    + escapeExpression(((helper = (helper = helpers.discount || (depth0 != null ? depth0.discount : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"discount","hash":{},"data":data}) : helper)))
    + "折</span>\n</li>\n";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing;
  stack1 = ((helper = (helper = helpers.goods || (depth0 != null ? depth0.goods : depth0)) != null ? helper : helperMissing),(options={"name":"goods","hash":{},"fn":this.program(1, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.goods) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { return stack1; }
  else { return ''; }
  },"useData":true});
this["aimeilive"]["index"] = Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<li><a href=\"stock-wall.html?category_id="
    + escapeExpression(((helper = (helper = helpers.categoryId || (depth0 != null ? depth0.categoryId : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"categoryId","hash":{},"data":data}) : helper)))
    + "\"><i class=\"icon-"
    + escapeExpression(((helper = (helper = helpers.categoryIconName || (depth0 != null ? depth0.categoryIconName : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"categoryIconName","hash":{},"data":data}) : helper)))
    + "\"></i><span>"
    + escapeExpression(((helper = (helper = helpers.category || (depth0 != null ? depth0.category : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"category","hash":{},"data":data}) : helper)))
    + "</span></a></li>";
},"3":function(depth0,helpers,partials,data) {
  var stack1, helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, lambda=this.lambda;
  return "		<li data-id=\""
    + escapeExpression(((helper = (helper = helpers.id || (depth0 != null ? depth0.id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"id","hash":{},"data":data}) : helper)))
    + "\">\n			<div class=\"lazy\" data-original=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.liveImg || (depth0 != null ? depth0.liveImg : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"liveImg","hash":{},"data":data}) : helper)))
    + "\" style=\"background-image:url(../images/livebg.png);\">\n				<h2>"
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "</h2>\n				<p>"
    + escapeExpression(((helper = (helper = helpers.brands_label || (depth0 != null ? depth0.brands_label : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"brands_label","hash":{},"data":data}) : helper)))
    + "</p>\n			</div>\n			<footer>\n				<img data-buyerid=\""
    + escapeExpression(((helper = (helper = helpers.buyer_id || (depth0 != null ? depth0.buyer_id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"buyer_id","hash":{},"data":data}) : helper)))
    + "\" class=\"avatar\" src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.buyer_head || (depth0 != null ? depth0.buyer_head : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"buyer_head","hash":{},"data":data}) : helper)))
    + "\" alt=\""
    + escapeExpression(((helper = (helper = helpers.buyer_name || (depth0 != null ? depth0.buyer_name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"buyer_name","hash":{},"data":data}) : helper)))
    + "\">\n				<strong>"
    + escapeExpression(((helper = (helper = helpers.buyer_name || (depth0 != null ? depth0.buyer_name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"buyer_name","hash":{},"data":data}) : helper)))
    + "<i class=\"level level"
    + escapeExpression(lambda(((stack1 = (depth0 != null ? depth0.buyer_level : depth0)) != null ? stack1.type : stack1), depth0))
    + "\" style=\"width:"
    + escapeExpression(lambda(((stack1 = (depth0 != null ? depth0.buyer_level : depth0)) != null ? stack1.num : stack1), depth0))
    + "rem\"></i></strong>\n				<p class=\"clearfix\"><span><img class=\"flag\" src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.buyer_country_flag || (depth0 != null ? depth0.buyer_country_flag : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"buyer_country_flag","hash":{},"data":data}) : helper)))
    + "\" alt=\""
    + escapeExpression(((helper = (helper = helpers.buyer_country || (depth0 != null ? depth0.buyer_country : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"buyer_country","hash":{},"data":data}) : helper)))
    + "国旗\">"
    + escapeExpression(((helper = (helper = helpers.buyer_country || (depth0 != null ? depth0.buyer_country : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"buyer_country","hash":{},"data":data}) : helper)))
    + " "
    + escapeExpression(((helper = (helper = helpers.user_num || (depth0 != null ? depth0.user_num : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"user_num","hash":{},"data":data}) : helper)))
    + "人观看</span><em><i class=\"icon-clock\"></i>还剩<time class=\"countDown\">"
    + escapeExpression(((helper = (helper = helpers.end_time || (depth0 != null ? depth0.end_time : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"end_time","hash":{},"data":data}) : helper)))
    + "</time></em></p>\n			</footer>\n		</li>\n";
},"5":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "		<li class=\"lazy\" data-id=\""
    + escapeExpression(((helper = (helper = helpers.id || (depth0 != null ? depth0.id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"id","hash":{},"data":data}) : helper)))
    + "\" data-original=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.liveImg || (depth0 != null ? depth0.liveImg : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"liveImg","hash":{},"data":data}) : helper)))
    + "\" style=\"background-image:url(../images/livebg.png);\">\n			<div>\n				<img data-buyerid=\""
    + escapeExpression(((helper = (helper = helpers.buyer_id || (depth0 != null ? depth0.buyer_id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"buyer_id","hash":{},"data":data}) : helper)))
    + "\" class=\"avatar\" src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.buyer_head || (depth0 != null ? depth0.buyer_head : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"buyer_head","hash":{},"data":data}) : helper)))
    + "\" alt=\""
    + escapeExpression(((helper = (helper = helpers.buyer_name || (depth0 != null ? depth0.buyer_name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"buyer_name","hash":{},"data":data}) : helper)))
    + "\">\n				<strong><img class=\"flag\" src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.buyer_country_flag || (depth0 != null ? depth0.buyer_country_flag : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"buyer_country_flag","hash":{},"data":data}) : helper)))
    + "\" alt=\""
    + escapeExpression(((helper = (helper = helpers.buyer_country || (depth0 != null ? depth0.buyer_country : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"buyer_country","hash":{},"data":data}) : helper)))
    + "国旗\">"
    + escapeExpression(((helper = (helper = helpers.buyer_name || (depth0 != null ? depth0.buyer_name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"buyer_name","hash":{},"data":data}) : helper)))
    + "</strong>\n				<h2>"
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "</h2>\n				<p>精彩开播倒计时:<time class=\"countDown\">"
    + escapeExpression(((helper = (helper = helpers.start_time || (depth0 != null ? depth0.start_time : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"start_time","hash":{},"data":data}) : helper)))
    + "</time></p>\n			</div>\n		</li>\n";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing, buffer = "<ul id=\"nav\" class=\"nav\">\n";
  stack1 = ((helper = (helper = helpers.categoryList || (depth0 != null ? depth0.categoryList : depth0)) != null ? helper : helperMissing),(options={"name":"categoryList","hash":{},"fn":this.program(1, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.categoryList) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  buffer += "\n</ul>\n<menu id=\"tabs\" class=\"tabs\"><span class=\"active\" id=\"living\">正在直播</span><span id=\"preview\">直播预告</span></menu>\n<div id=\"liveContent\">\n	<ul id=\"liveList\" class=\"liveList\">\n";
  stack1 = ((helper = (helper = helpers.livingList || (depth0 != null ? depth0.livingList : depth0)) != null ? helper : helperMissing),(options={"name":"livingList","hash":{},"fn":this.program(3, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.livingList) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  buffer += "	</ul>	\n	<ul id=\"previewList\" class=\"previewList\" style=\"display:none;\">\n";
  stack1 = ((helper = (helper = helpers.noticeLiveList || (depth0 != null ? depth0.noticeLiveList : depth0)) != null ? helper : helperMissing),(options={"name":"noticeLiveList","hash":{},"fn":this.program(5, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.noticeLiveList) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  return buffer + "	</ul>\n</div>";
},"useData":true});
this["aimeilive"]["liveDetail"] = Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, lambda=this.lambda;
  return "<header id=\"buyer\" class=\"buyer\" data-id=\""
    + escapeExpression(((helper = (helper = helpers.id || (depth0 != null ? depth0.id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"id","hash":{},"data":data}) : helper)))
    + "\" style=\"background-image:url("
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.background_pic || (depth0 != null ? depth0.background_pic : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"background_pic","hash":{},"data":data}) : helper)))
    + ");\">\n	<img class=\"avatar\" src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.head || (depth0 != null ? depth0.head : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"head","hash":{},"data":data}) : helper)))
    + "\" alt=\""
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "\">\n	<div>\n		<strong>"
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "<i class=\"level level"
    + escapeExpression(lambda(((stack1 = (depth0 != null ? depth0.level : depth0)) != null ? stack1.type : stack1), depth0))
    + "\" style=\"width:"
    + escapeExpression(lambda(((stack1 = (depth0 != null ? depth0.level : depth0)) != null ? stack1.num : stack1), depth0))
    + "rem\"></i></strong>\n		<p><span>粉丝</span> "
    + escapeExpression(((helper = (helper = helpers.fans || (depth0 != null ? depth0.fans : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"fans","hash":{},"data":data}) : helper)))
    + "<i class=\"icon-address\"></i>"
    + escapeExpression(((helper = (helper = helpers.country || (depth0 != null ? depth0.country : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"country","hash":{},"data":data}) : helper)))
    + " "
    + escapeExpression(((helper = (helper = helpers.city || (depth0 != null ? depth0.city : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"city","hash":{},"data":data}) : helper)))
    + "</p>\n	</div>\n</header>\n";
},"3":function(depth0,helpers,partials,data) {
  var stack1, helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, buffer = "<div id=\"live\" class=\"live\" data-id=\""
    + escapeExpression(((helper = (helper = helpers.id || (depth0 != null ? depth0.id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"id","hash":{},"data":data}) : helper)))
    + "\">\n	<figure>\n		<img src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.liveImg || (depth0 != null ? depth0.liveImg : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"liveImg","hash":{},"data":data}) : helper)))
    + "\" alt=\"直播图\">\n		<h2>";
  stack1 = helpers['if'].call(depth0, (depth0 != null ? depth0.is_close : depth0), {"name":"if","hash":{},"fn":this.program(4, data),"inverse":this.program(6, data),"data":data});
  if (stack1 != null) { buffer += stack1; }
  return buffer + "		</h2>\n	</figure>\n	<div>\n		<p class=\"info\">"
    + escapeExpression(((helper = (helper = helpers.intro || (depth0 != null ? depth0.intro : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"intro","hash":{},"data":data}) : helper)))
    + "</p>\n		<p class=\"brand-title\"><strong>直播品牌</strong></p>\n		<p class=\"brand\">"
    + escapeExpression(((helper = (helper = helpers.brands_label || (depth0 != null ? depth0.brands_label : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"brands_label","hash":{},"data":data}) : helper)))
    + "</p>\n	</div>\n</div>\n";
},"4":function(depth0,helpers,partials,data) {
  return "\n			<span>直播已结束</span>\n";
  },"6":function(depth0,helpers,partials,data) {
  var stack1, buffer = "";
  stack1 = helpers['if'].call(depth0, (depth0 != null ? depth0.is_open : depth0), {"name":"if","hash":{},"fn":this.program(7, data),"inverse":this.program(9, data),"data":data});
  if (stack1 != null) { buffer += stack1; }
  return buffer;
},"7":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "					<span>距离结束还剩 <time id=\"countDown\">"
    + escapeExpression(((helper = (helper = helpers.end_time || (depth0 != null ? depth0.end_time : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"end_time","hash":{},"data":data}) : helper)))
    + "</time></span>\n";
},"9":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "					<span>精彩开播倒计时 <time id=\"countDown\">"
    + escapeExpression(((helper = (helper = helpers.start_time || (depth0 != null ? depth0.start_time : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"start_time","hash":{},"data":data}) : helper)))
    + "</time></span>\n";
},"11":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing, buffer = "<ul id=\"stockList\" class=\"stock-list\">\n";
  stack1 = ((helper = (helper = helpers.stateDetail || (depth0 != null ? depth0.stateDetail : depth0)) != null ? helper : helperMissing),(options={"name":"stateDetail","hash":{},"fn":this.program(12, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.stateDetail) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  return buffer + "</ul>\n";
},"12":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, blockHelperMissing=helpers.blockHelperMissing, buffer = "	<li data-id=\""
    + escapeExpression(((helper = (helper = helpers.id || (depth0 != null ? depth0.id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"id","hash":{},"data":data}) : helper)))
    + "\" ";
  stack1 = ((helper = (helper = helpers.ifClassify || (depth0 != null ? depth0.ifClassify : depth0)) != null ? helper : helperMissing),(options={"name":"ifClassify","hash":{},"fn":this.program(13, data),"inverse":this.program(15, data),"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.ifClassify) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  buffer += ">\n		<em><i class=\"icon-timeline\"></i><time>"
    + escapeExpression(((helper = (helper = helpers.lastTime || (depth0 != null ? depth0.lastTime : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"lastTime","hash":{},"data":data}) : helper)))
    + "</time></em>\n		<figure>\n			<img class=\"stock-img\" src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.stockImg || (depth0 != null ? depth0.stockImg : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"stockImg","hash":{},"data":data}) : helper)))
    + "\" alt=\""
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "\">\n			<span class=\"comment\"><i class=\"icon-comment\"></i>"
    + escapeExpression(((helper = (helper = helpers.commented || (depth0 != null ? depth0.commented : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"commented","hash":{},"data":data}) : helper)))
    + "</span>\n		</figure>\n		<div>\n";
  stack1 = ((helper = (helper = helpers.ifClassify || (depth0 != null ? depth0.ifClassify : depth0)) != null ? helper : helperMissing),(options={"name":"ifClassify","hash":{},"fn":this.program(17, data),"inverse":this.program(20, data),"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.ifClassify) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  buffer += "		</div>\n		<footer>\n			<span><i class=\"icon-like\"></i>"
    + escapeExpression(((helper = (helper = helpers.liked || (depth0 != null ? depth0.liked : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"liked","hash":{},"data":data}) : helper)))
    + "</span>\n";
  stack1 = ((helper = (helper = helpers.likeList || (depth0 != null ? depth0.likeList : depth0)) != null ? helper : helperMissing),(options={"name":"likeList","hash":{},"fn":this.program(22, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.likeList) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  return buffer + "		</footer>\n	</li>\n";
},"13":function(depth0,helpers,partials,data) {
  return "";
},"15":function(depth0,helpers,partials,data) {
  return "data-type=\"2\"";
  },"17":function(depth0,helpers,partials,data) {
  var stack1, helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, buffer = "				<h2>"
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "</h2>\n				";
  stack1 = helpers['if'].call(depth0, (depth0 != null ? depth0.original_price : depth0), {"name":"if","hash":{},"fn":this.program(18, data),"inverse":this.noop,"data":data});
  if (stack1 != null) { buffer += stack1; }
  return buffer + "\n				<strong>￥"
    + escapeExpression(((helper = (helper = helpers.priceout || (depth0 != null ? depth0.priceout : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"priceout","hash":{},"data":data}) : helper)))
    + "</strong>\n				<button class=\"btn-primary btn\">去购买</button>\n";
},"18":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<p>国内售价:<del>￥"
    + escapeExpression(((helper = (helper = helpers.original_price || (depth0 != null ? depth0.original_price : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"original_price","hash":{},"data":data}) : helper)))
    + "</del></p>";
},"20":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "				<p class=\"note\">"
    + escapeExpression(((helper = (helper = helpers.note || (depth0 != null ? depth0.note : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"note","hash":{},"data":data}) : helper)))
    + "</p>\n";
},"22":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "			<img src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.avatar_url || (depth0 != null ? depth0.avatar_url : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"avatar_url","hash":{},"data":data}) : helper)))
    + "\" alt=\""
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "\">\n";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing, buffer = "";
  stack1 = ((helper = (helper = helpers.buyerInfo || (depth0 != null ? depth0.buyerInfo : depth0)) != null ? helper : helperMissing),(options={"name":"buyerInfo","hash":{},"fn":this.program(1, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.buyerInfo) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  stack1 = ((helper = (helper = helpers.liveInfo || (depth0 != null ? depth0.liveInfo : depth0)) != null ? helper : helperMissing),(options={"name":"liveInfo","hash":{},"fn":this.program(3, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.liveInfo) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  stack1 = helpers['if'].call(depth0, (depth0 != null ? depth0.stateDetail : depth0), {"name":"if","hash":{},"fn":this.program(11, data),"inverse":this.noop,"data":data});
  if (stack1 != null) { buffer += stack1; }
  return buffer;
},"useData":true});
this["aimeilive"]["liveDetailMore"] = Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, blockHelperMissing=helpers.blockHelperMissing, buffer = "	<li data-id=\""
    + escapeExpression(((helper = (helper = helpers.id || (depth0 != null ? depth0.id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"id","hash":{},"data":data}) : helper)))
    + "\" ";
  stack1 = ((helper = (helper = helpers.ifClassify || (depth0 != null ? depth0.ifClassify : depth0)) != null ? helper : helperMissing),(options={"name":"ifClassify","hash":{},"fn":this.program(2, data),"inverse":this.program(4, data),"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.ifClassify) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  buffer += ">\n		<em><i class=\"icon-timeline\"></i><time>"
    + escapeExpression(((helper = (helper = helpers.lastTime || (depth0 != null ? depth0.lastTime : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"lastTime","hash":{},"data":data}) : helper)))
    + "</time></em>\n		<figure>\n			<img class=\"stock-img\" src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.stockImg || (depth0 != null ? depth0.stockImg : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"stockImg","hash":{},"data":data}) : helper)))
    + "\" alt=\""
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "\">\n			<span class=\"comment\"><i class=\"icon-comment\"></i>"
    + escapeExpression(((helper = (helper = helpers.commented || (depth0 != null ? depth0.commented : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"commented","hash":{},"data":data}) : helper)))
    + "</span>\n		</figure>\n		<div>\n";
  stack1 = ((helper = (helper = helpers.ifClassify || (depth0 != null ? depth0.ifClassify : depth0)) != null ? helper : helperMissing),(options={"name":"ifClassify","hash":{},"fn":this.program(6, data),"inverse":this.program(9, data),"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.ifClassify) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  buffer += "		</div>\n		<footer>\n			<span><i class=\"icon-like\"></i>"
    + escapeExpression(((helper = (helper = helpers.liked || (depth0 != null ? depth0.liked : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"liked","hash":{},"data":data}) : helper)))
    + "</span>\n";
  stack1 = ((helper = (helper = helpers.likeList || (depth0 != null ? depth0.likeList : depth0)) != null ? helper : helperMissing),(options={"name":"likeList","hash":{},"fn":this.program(11, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.likeList) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  return buffer + "		</footer>\n	</li>\n";
},"2":function(depth0,helpers,partials,data) {
  return "";
},"4":function(depth0,helpers,partials,data) {
  return "data-type=\"2\"";
  },"6":function(depth0,helpers,partials,data) {
  var stack1, helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, buffer = "				<h2>"
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "</h2>\n				";
  stack1 = helpers['if'].call(depth0, (depth0 != null ? depth0.original_price : depth0), {"name":"if","hash":{},"fn":this.program(7, data),"inverse":this.noop,"data":data});
  if (stack1 != null) { buffer += stack1; }
  return buffer + "\n				<strong>￥"
    + escapeExpression(((helper = (helper = helpers.priceout || (depth0 != null ? depth0.priceout : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"priceout","hash":{},"data":data}) : helper)))
    + "</strong>\n				<button class=\"btn-primary btn\">去购买</button>\n";
},"7":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<p>国内售价:<del>￥"
    + escapeExpression(((helper = (helper = helpers.original_price || (depth0 != null ? depth0.original_price : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"original_price","hash":{},"data":data}) : helper)))
    + "</del></p>";
},"9":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "				<p class=\"note\">"
    + escapeExpression(((helper = (helper = helpers.note || (depth0 != null ? depth0.note : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"note","hash":{},"data":data}) : helper)))
    + "</p>\n";
},"11":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "			<img src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.avatar_url || (depth0 != null ? depth0.avatar_url : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"avatar_url","hash":{},"data":data}) : helper)))
    + "\" alt=\""
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "\">\n";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing;
  stack1 = ((helper = (helper = helpers.stateDetail || (depth0 != null ? depth0.stateDetail : depth0)) != null ? helper : helperMissing),(options={"name":"stateDetail","hash":{},"fn":this.program(1, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.stateDetail) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { return stack1; }
  else { return ''; }
  },"useData":true});
this["aimeilive"]["newStock"] = Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<li data-id=\""
    + escapeExpression(((helper = (helper = helpers.id || (depth0 != null ? depth0.id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"id","hash":{},"data":data}) : helper)))
    + "\">\n	<img class=\"lazy\" data-original=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.stockImg || (depth0 != null ? depth0.stockImg : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"stockImg","hash":{},"data":data}) : helper)))
    + "\" src=\"../images/stockbg.png\" alt=\""
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "\">\n	<footer>\n		<h3>"
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "</h3>\n		<span>￥"
    + escapeExpression(((helper = (helper = helpers.priceout || (depth0 != null ? depth0.priceout : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"priceout","hash":{},"data":data}) : helper)))
    + "</span>\n	</footer>\n</li>";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing;
  stack1 = ((helper = (helper = helpers.stockList || (depth0 != null ? depth0.stockList : depth0)) != null ? helper : helperMissing),(options={"name":"stockList","hash":{},"fn":this.program(1, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.stockList) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { return stack1; }
  else { return ''; }
  },"useData":true});
this["aimeilive"]["ourBuyers"] = Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, lambda=this.lambda, buffer = "	<li data-id=\""
    + escapeExpression(((helper = (helper = helpers.buyer_id || (depth0 != null ? depth0.buyer_id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"buyer_id","hash":{},"data":data}) : helper)))
    + "\">\n		<header>\n			<img class=\"avatar\" src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.head || (depth0 != null ? depth0.head : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"head","hash":{},"data":data}) : helper)))
    + "\" alt=\""
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "\">\n			<div>\n				<strong>"
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "<i class=\"level level"
    + escapeExpression(lambda(((stack1 = (depth0 != null ? depth0.level : depth0)) != null ? stack1.type : stack1), depth0))
    + "\" style=\"width:"
    + escapeExpression(lambda(((stack1 = (depth0 != null ? depth0.level : depth0)) != null ? stack1.num : stack1), depth0))
    + "rem\"></i></strong>\n				<p><i class=\"icon-address\"></i>"
    + escapeExpression(((helper = (helper = helpers.country || (depth0 != null ? depth0.country : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"country","hash":{},"data":data}) : helper)))
    + " "
    + escapeExpression(((helper = (helper = helpers.city || (depth0 != null ? depth0.city : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"city","hash":{},"data":data}) : helper)))
    + "</p>\n			</div>\n			<button class=\"btn-primary btn\">进入店铺</button>\n		</header>\n		<figure>\n			";
  stack1 = helpers['if'].call(depth0, (depth0 != null ? depth0.buyerphoto : depth0), {"name":"if","hash":{},"fn":this.program(2, data),"inverse":this.noop,"data":data});
  if (stack1 != null) { buffer += stack1; }
  return buffer + "\n		</figure>\n		<p><sup>“</sup>"
    + escapeExpression(((helper = (helper = helpers.signature || (depth0 != null ? depth0.signature : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"signature","hash":{},"data":data}) : helper)))
    + "<sub>”</sub></p>\n	</li>\n";
},"2":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<img src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.buyerphoto || (depth0 != null ? depth0.buyerphoto : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"buyerphoto","hash":{},"data":data}) : helper)))
    + "\" alt=\"买手照片\">";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing;
  stack1 = ((helper = (helper = helpers.rst || (depth0 != null ? depth0.rst : depth0)) != null ? helper : helperMissing),(options={"name":"rst","hash":{},"fn":this.program(1, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.rst) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { return stack1; }
  else { return ''; }
  },"useData":true});
this["aimeilive"]["recommend"] = Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, buffer = "<li data-id="
    + escapeExpression(((helper = (helper = helpers.id || (depth0 != null ? depth0.id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"id","hash":{},"data":data}) : helper)))
    + " data-type="
    + escapeExpression(((helper = (helper = helpers.type || (depth0 != null ? depth0.type : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"type","hash":{},"data":data}) : helper)))
    + ">\n	<img class=\"lazy\" data-original=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.img || (depth0 != null ? depth0.img : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"img","hash":{},"data":data}) : helper)))
    + "\" src=\"../images/stockbg.png\" alt=\"商品图片\">\n	";
  stack1 = helpers['if'].call(depth0, (depth0 != null ? depth0.price : depth0), {"name":"if","hash":{},"fn":this.program(2, data),"inverse":this.noop,"data":data});
  if (stack1 != null) { buffer += stack1; }
  return buffer + "\n</li>";
},"2":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<span>￥"
    + escapeExpression(((helper = (helper = helpers.price || (depth0 != null ? depth0.price : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"price","hash":{},"data":data}) : helper)))
    + "</span>";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing, buffer = "";
  stack1 = ((helper = (helper = helpers.recommended || (depth0 != null ? depth0.recommended : depth0)) != null ? helper : helperMissing),(options={"name":"recommended","hash":{},"fn":this.program(1, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.recommended) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  return buffer + "\n\n";
},"useData":true});
this["aimeilive"]["stateDetail"] = Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing, buffer = "		<ul id=\"stockImgs\" class=\"swiper-wrapper\">\n";
  stack1 = ((helper = (helper = helpers.imgs || (depth0 != null ? depth0.imgs : depth0)) != null ? helper : helperMissing),(options={"name":"imgs","hash":{},"fn":this.program(2, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.imgs) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  return buffer + "		</ul>\n";
},"2":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, lambda=this.lambda;
  return "			<li class=\"swiper-slide\"><img src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(lambda(depth0, depth0))
    + "\"></li>\n";
},"4":function(depth0,helpers,partials,data) {
  var stack1, helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, lambda=this.lambda;
  return "	<header>\n		<img class=\"avatar\" src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.head || (depth0 != null ? depth0.head : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"head","hash":{},"data":data}) : helper)))
    + "\" alt=\""
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "\">\n		<div>\n			<strong>"
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "<i class=\"level level"
    + escapeExpression(lambda(((stack1 = (depth0 != null ? depth0.level : depth0)) != null ? stack1.type : stack1), depth0))
    + "\" style=\"width:"
    + escapeExpression(lambda(((stack1 = (depth0 != null ? depth0.level : depth0)) != null ? stack1.num : stack1), depth0))
    + "rem\"></i></strong>\n			<p class=\"fans\"><span>粉丝</span> "
    + escapeExpression(((helper = (helper = helpers.fans || (depth0 != null ? depth0.fans : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"fans","hash":{},"data":data}) : helper)))
    + "</p>\n			<p class=\"state\">"
    + escapeExpression(((helper = (helper = helpers.signature || (depth0 != null ? depth0.signature : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"signature","hash":{},"data":data}) : helper)))
    + "</p>\n		</div>\n	</header>\n";
},"6":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, blockHelperMissing=helpers.blockHelperMissing, buffer = "	<footer>\n		<span><i class=\"icon-like\"></i>"
    + escapeExpression(((helper = (helper = helpers.liked || (depth0 != null ? depth0.liked : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"liked","hash":{},"data":data}) : helper)))
    + "</span>\n";
  stack1 = ((helper = (helper = helpers.likeList || (depth0 != null ? depth0.likeList : depth0)) != null ? helper : helperMissing),(options={"name":"likeList","hash":{},"fn":this.program(7, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.likeList) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  return buffer + "	</footer>\n";
},"7":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "			<img src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.avatar_url || (depth0 != null ? depth0.avatar_url : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"avatar_url","hash":{},"data":data}) : helper)))
    + "\" alt=\""
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "\">\n";
},"9":function(depth0,helpers,partials,data) {
  var stack1;
  stack1 = helpers['if'].call(depth0, (depth0 != null ? depth0.commentList : depth0), {"name":"if","hash":{},"fn":this.program(10, data),"inverse":this.noop,"data":data});
  if (stack1 != null) { return stack1; }
  else { return ''; }
  },"10":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, blockHelperMissing=helpers.blockHelperMissing, buffer = "\n<dl class=\"comment_list\" id=\"commentList\">\n	<dt><i class=\"icon-comment-line\"></i>评论("
    + escapeExpression(((helper = (helper = helpers.commented || (depth0 != null ? depth0.commented : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"commented","hash":{},"data":data}) : helper)))
    + ")</dt>\n";
  stack1 = ((helper = (helper = helpers.commentList || (depth0 != null ? depth0.commentList : depth0)) != null ? helper : helperMissing),(options={"name":"commentList","hash":{},"fn":this.program(11, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.commentList) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  return buffer + "</dl>";
},"11":function(depth0,helpers,partials,data) {
  var stack1, helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, lambda=this.lambda;
  return "			<dd>\n				<img class=\"avatar\" src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.ci_user_avatar_url || (depth0 != null ? depth0.ci_user_avatar_url : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"ci_user_avatar_url","hash":{},"data":data}) : helper)))
    + "\" alt=\""
    + escapeExpression(((helper = (helper = helpers.ci_user_name || (depth0 != null ? depth0.ci_user_name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"ci_user_name","hash":{},"data":data}) : helper)))
    + "\">\n				<div>\n					<strong>"
    + escapeExpression(((helper = (helper = helpers.ci_user_name || (depth0 != null ? depth0.ci_user_name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"ci_user_name","hash":{},"data":data}) : helper)))
    + "<i class=\"level level"
    + escapeExpression(lambda(((stack1 = (depth0 != null ? depth0.ci_user_level : depth0)) != null ? stack1.type : stack1), depth0))
    + "\" style=\"width:"
    + escapeExpression(lambda(((stack1 = (depth0 != null ? depth0.ci_user_level : depth0)) != null ? stack1.num : stack1), depth0))
    + "rem\"></i></strong>\n					<p class=\"state\"><em></em>"
    + escapeExpression(((helper = (helper = helpers.comment || (depth0 != null ? depth0.comment : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"comment","hash":{},"data":data}) : helper)))
    + "</p>\n				</div>\n				<time>"
    + escapeExpression(((helper = (helper = helpers.displayTime || (depth0 != null ? depth0.displayTime : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"displayTime","hash":{},"data":data}) : helper)))
    + "</time>\n			</dd>\n";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing, buffer = "<div class=\"device\">\n	<div class=\"swiper-container\">\n";
  stack1 = ((helper = (helper = helpers.stateDetail || (depth0 != null ? depth0.stateDetail : depth0)) != null ? helper : helperMissing),(options={"name":"stateDetail","hash":{},"fn":this.program(1, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.stateDetail) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  buffer += "	</div>\n	<div class=\"paginationWrap\"><div class=\"pagination\"></div></div>\n</div>\n<section>\n";
  stack1 = ((helper = (helper = helpers.buyerInfo || (depth0 != null ? depth0.buyerInfo : depth0)) != null ? helper : helperMissing),(options={"name":"buyerInfo","hash":{},"fn":this.program(4, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.buyerInfo) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  stack1 = ((helper = (helper = helpers.like || (depth0 != null ? depth0.like : depth0)) != null ? helper : helperMissing),(options={"name":"like","hash":{},"fn":this.program(6, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.like) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  buffer += "</section>\n";
  stack1 = ((helper = (helper = helpers.commentList || (depth0 != null ? depth0.commentList : depth0)) != null ? helper : helperMissing),(options={"name":"commentList","hash":{},"fn":this.program(9, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.commentList) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  return buffer;
},"useData":true});
this["aimeilive"]["stateDetailMore"] = Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing, buffer = "	";
  stack1 = ((helper = (helper = helpers.commentList || (depth0 != null ? depth0.commentList : depth0)) != null ? helper : helperMissing),(options={"name":"commentList","hash":{},"fn":this.program(2, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.commentList) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  return buffer + "</dd>\n";
},"2":function(depth0,helpers,partials,data) {
  var stack1, helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, lambda=this.lambda;
  return "<dd>\n		<img class=\"avatar\" src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.ci_user_avatar_url || (depth0 != null ? depth0.ci_user_avatar_url : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"ci_user_avatar_url","hash":{},"data":data}) : helper)))
    + "\" alt=\""
    + escapeExpression(((helper = (helper = helpers.ci_user_name || (depth0 != null ? depth0.ci_user_name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"ci_user_name","hash":{},"data":data}) : helper)))
    + "\">\n		<div>\n			<strong>"
    + escapeExpression(((helper = (helper = helpers.ci_user_name || (depth0 != null ? depth0.ci_user_name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"ci_user_name","hash":{},"data":data}) : helper)))
    + "<i class=\"level level"
    + escapeExpression(lambda(((stack1 = (depth0 != null ? depth0.ci_user_level : depth0)) != null ? stack1.type : stack1), depth0))
    + "\" style=\"width:"
    + escapeExpression(lambda(((stack1 = (depth0 != null ? depth0.ci_user_level : depth0)) != null ? stack1.num : stack1), depth0))
    + "rem\"></i></strong>\n			<p class=\"state\"><em></em>"
    + escapeExpression(((helper = (helper = helpers.comment || (depth0 != null ? depth0.comment : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"comment","hash":{},"data":data}) : helper)))
    + "</p>\n		</div>\n		<time>"
    + escapeExpression(((helper = (helper = helpers.displayTime || (depth0 != null ? depth0.displayTime : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"displayTime","hash":{},"data":data}) : helper)))
    + "</time>\n	";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing;
  stack1 = ((helper = (helper = helpers.commentList || (depth0 != null ? depth0.commentList : depth0)) != null ? helper : helperMissing),(options={"name":"commentList","hash":{},"fn":this.program(1, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.commentList) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { return stack1; }
  else { return ''; }
  },"useData":true});
this["aimeilive"]["stockDetail"] = Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing, escapeExpression=this.escapeExpression, buffer = "<div class=\"device\">\n	<div class=\"swiper-container\">\n		<ul id=\"stockImgs\" class=\"swiper-wrapper\">\n";
  stack1 = ((helper = (helper = helpers.imgs || (depth0 != null ? depth0.imgs : depth0)) != null ? helper : helperMissing),(options={"name":"imgs","hash":{},"fn":this.program(2, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.imgs) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  buffer += "		</ul>\n	</div>	\n<div class=\"paginationWrap\"><div class=\"pagination\"></div></div>\n</div>\n<div class=\"stock-info\">\n	<h1>"
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "</h1>\n	";
  stack1 = helpers['if'].call(depth0, (depth0 != null ? depth0.original_price : depth0), {"name":"if","hash":{},"fn":this.program(4, data),"inverse":this.noop,"data":data});
  if (stack1 != null) { buffer += stack1; }
  return buffer + "\n	<strong>￥"
    + escapeExpression(((helper = (helper = helpers.priceout || (depth0 != null ? depth0.priceout : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"priceout","hash":{},"data":data}) : helper)))
    + "</strong>\n	<button id=\"buyNow\">立即购买</button>\n	<p class=\"intro\">"
    + escapeExpression(((helper = (helper = helpers.note || (depth0 != null ? depth0.note : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"note","hash":{},"data":data}) : helper)))
    + "</p>		\n</div>\n";
},"2":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, lambda=this.lambda;
  return "			<li class=\"swiper-slide\"><img src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(lambda(depth0, depth0))
    + "\"></li>\n";
},"4":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<p class=\"price\">国内售价:<del>￥"
    + escapeExpression(((helper = (helper = helpers.original_price || (depth0 != null ? depth0.original_price : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"original_price","hash":{},"data":data}) : helper)))
    + "</del></p>";
},"6":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, blockHelperMissing=helpers.blockHelperMissing, buffer = "<div class=\"like\">\n	<footer>\n		<span><i class=\"icon-like\"></i>"
    + escapeExpression(((helper = (helper = helpers.liked || (depth0 != null ? depth0.liked : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"liked","hash":{},"data":data}) : helper)))
    + "</span>\n";
  stack1 = ((helper = (helper = helpers.likeList || (depth0 != null ? depth0.likeList : depth0)) != null ? helper : helperMissing),(options={"name":"likeList","hash":{},"fn":this.program(7, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.likeList) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  return buffer + "	</footer>\n</div>\n";
},"7":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "		<img src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.avatar_url || (depth0 != null ? depth0.avatar_url : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"avatar_url","hash":{},"data":data}) : helper)))
    + "\" alt=\""
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "\">\n";
},"9":function(depth0,helpers,partials,data) {
  var stack1, helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, buffer = "\n<div class=\"score\">\n	<header><h2><i class=\"icon-score\"></i>综合评分</h2><div id=\"star\" class=\"star\"><p style=\"width:"
    + escapeExpression(((helper = (helper = helpers.starWidth || (depth0 != null ? depth0.starWidth : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"starWidth","hash":{},"data":data}) : helper)))
    + "%;\"></p></div>"
    + escapeExpression(((helper = (helper = helpers.score || (depth0 != null ? depth0.score : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"score","hash":{},"data":data}) : helper)))
    + "分</header>\n	";
  stack1 = helpers['if'].call(depth0, (depth0 != null ? depth0.score : depth0), {"name":"if","hash":{},"fn":this.program(10, data),"inverse":this.noop,"data":data});
  if (stack1 != null) { buffer += stack1; }
  return buffer + "\n</div>\n";
},"10":function(depth0,helpers,partials,data) {
  var stack1, buffer = "<ul class=\"tag-list\">\n";
  stack1 = helpers.each.call(depth0, (depth0 != null ? depth0.rate_tags : depth0), {"name":"each","hash":{},"fn":this.program(11, data),"inverse":this.noop,"data":data});
  if (stack1 != null) { buffer += stack1; }
  return buffer + "	</ul>";
},"11":function(depth0,helpers,partials,data) {
  var lambda=this.lambda, escapeExpression=this.escapeExpression;
  return "		<li>"
    + escapeExpression(lambda((data && data.key), depth0))
    + " "
    + escapeExpression(lambda(depth0, depth0))
    + "</li>\n";
},"13":function(depth0,helpers,partials,data) {
  var stack1, buffer = "";
  stack1 = helpers['if'].call(depth0, (depth0 != null ? depth0.commentList : depth0), {"name":"if","hash":{},"fn":this.program(14, data),"inverse":this.noop,"data":data});
  if (stack1 != null) { buffer += stack1; }
  return buffer + "\n";
},"14":function(depth0,helpers,partials,data) {
  var stack1, helper, options, lambda=this.lambda, escapeExpression=this.escapeExpression, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing, buffer = "\n<div class=\"comment\">\n	<h2><i class=\"icon-comment-line\"></i>评论("
    + escapeExpression(lambda(((stack1 = (depth0 != null ? depth0.commentList : depth0)) != null ? stack1.length : stack1), depth0))
    + ")</h2>\n	<ul>\n		";
  stack1 = ((helper = (helper = helpers.commentList || (depth0 != null ? depth0.commentList : depth0)) != null ? helper : helperMissing),(options={"name":"commentList","hash":{},"fn":this.program(15, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.commentList) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  return buffer + "</li>\n	</ul>\n</div>";
},"15":function(depth0,helpers,partials,data) {
  var stack1, helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, lambda=this.lambda;
  return "<li>\n			<img src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.ci_user_avatar_url || (depth0 != null ? depth0.ci_user_avatar_url : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"ci_user_avatar_url","hash":{},"data":data}) : helper)))
    + "\" alt=\""
    + escapeExpression(((helper = (helper = helpers.ci_user_id || (depth0 != null ? depth0.ci_user_id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"ci_user_id","hash":{},"data":data}) : helper)))
    + "\">\n			<div>\n				<strong>"
    + escapeExpression(((helper = (helper = helpers.ci_user_name || (depth0 != null ? depth0.ci_user_name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"ci_user_name","hash":{},"data":data}) : helper)))
    + "<i class=\"level level"
    + escapeExpression(lambda(((stack1 = (depth0 != null ? depth0.level : depth0)) != null ? stack1.type : stack1), depth0))
    + "\" style=\"width:"
    + escapeExpression(lambda(((stack1 = (depth0 != null ? depth0.level : depth0)) != null ? stack1.num : stack1), depth0))
    + "rem\"></i></strong>\n				<p>"
    + escapeExpression(((helper = (helper = helpers.comment || (depth0 != null ? depth0.comment : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"comment","hash":{},"data":data}) : helper)))
    + "</p>\n			</div>\n			<time>"
    + escapeExpression(((helper = (helper = helpers.displayTime || (depth0 != null ? depth0.displayTime : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"displayTime","hash":{},"data":data}) : helper)))
    + "</time>\n		";
},"17":function(depth0,helpers,partials,data) {
  var stack1, helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, lambda=this.lambda;
  return "	<header class=\"buyer-info\" data-id=\""
    + escapeExpression(((helper = (helper = helpers.id || (depth0 != null ? depth0.id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"id","hash":{},"data":data}) : helper)))
    + "\" id=\"buyer\" style=\"background-image:url("
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.background_pic || (depth0 != null ? depth0.background_pic : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"background_pic","hash":{},"data":data}) : helper)))
    + ");\">\n		<img class=\"avatar\" src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.head || (depth0 != null ? depth0.head : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"head","hash":{},"data":data}) : helper)))
    + "\" alt=\""
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "\">\n		<div>\n			<strong>"
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "<i class=\"level level"
    + escapeExpression(lambda(((stack1 = (depth0 != null ? depth0.level : depth0)) != null ? stack1.type : stack1), depth0))
    + "\" style=\"width:"
    + escapeExpression(lambda(((stack1 = (depth0 != null ? depth0.level : depth0)) != null ? stack1.num : stack1), depth0))
    + "rem\"></i></strong>\n			<p><span>粉丝</span> "
    + escapeExpression(((helper = (helper = helpers.fans || (depth0 != null ? depth0.fans : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"fans","hash":{},"data":data}) : helper)))
    + "<i class=\"icon-address\"></i>"
    + escapeExpression(((helper = (helper = helpers.country || (depth0 != null ? depth0.country : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"country","hash":{},"data":data}) : helper)))
    + " "
    + escapeExpression(((helper = (helper = helpers.city || (depth0 != null ? depth0.city : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"city","hash":{},"data":data}) : helper)))
    + "</p>\n		</div>\n	</header>\n";
},"19":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<li data-id=\""
    + escapeExpression(((helper = (helper = helpers.id || (depth0 != null ? depth0.id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"id","hash":{},"data":data}) : helper)))
    + "\">\n			<img src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.recommendImg || (depth0 != null ? depth0.recommendImg : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"recommendImg","hash":{},"data":data}) : helper)))
    + "\" alt=\""
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "\">\n			<span>￥"
    + escapeExpression(((helper = (helper = helpers.priceout || (depth0 != null ? depth0.priceout : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"priceout","hash":{},"data":data}) : helper)))
    + "</span>\n		</li>";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing, buffer = "";
  stack1 = ((helper = (helper = helpers.stockDetail || (depth0 != null ? depth0.stockDetail : depth0)) != null ? helper : helperMissing),(options={"name":"stockDetail","hash":{},"fn":this.program(1, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.stockDetail) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  stack1 = ((helper = (helper = helpers.like || (depth0 != null ? depth0.like : depth0)) != null ? helper : helperMissing),(options={"name":"like","hash":{},"fn":this.program(6, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.like) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  buffer += "\n<div class=\"serve-pay\">\n	<div class=\"serve\">\n		<h3>服务保证</h3>\n		<ul>\n			<li>\n				<i class=\"quality-goods\"></i><p>海外采购</p>\n			</li><li>\n				<i class=\"fifteen-days\"></i>\n				<p>售后保障</p>\n			</li><li>\n				<i class=\"duty-free\"></i>\n				<p>零关税</p>\n			</li><li>\n				<i class=\"compensates\"></i>\n				<p>超时赔付</p>\n			</li>\n		</ul>\n	</div>\n	<div class=\"pay\">\n		<h3>支付方式</h3>\n		<ul>\n			<li>\n				<i class=\"zhifubao\"></i>\n				<p>支付宝</p>\n			</li>\n			<li>\n				<i class=\"weixin\"></i>\n				<p>微信支付</p>\n			</li>\n			<li>\n				<i class=\"yinlian\"></i>\n				<p>支付宝(银联)</p>\n			</li>\n		</ul>\n	</div>\n</div>\n\n<!-- ";
  stack1 = ((helper = (helper = helpers.stockDetail || (depth0 != null ? depth0.stockDetail : depth0)) != null ? helper : helperMissing),(options={"name":"stockDetail","hash":{},"fn":this.program(9, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.stockDetail) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  stack1 = ((helper = (helper = helpers.commentList || (depth0 != null ? depth0.commentList : depth0)) != null ? helper : helperMissing),(options={"name":"commentList","hash":{},"fn":this.program(13, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.commentList) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  buffer += " -->\n\n<div class=\"buyer\">\n";
  stack1 = ((helper = (helper = helpers.buyerInfo || (depth0 != null ? depth0.buyerInfo : depth0)) != null ? helper : helperMissing),(options={"name":"buyerInfo","hash":{},"fn":this.program(17, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.buyerInfo) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  buffer += "	<ul class=\"buyer-recommend\" id=\"buyerRecommend\">\n		";
  stack1 = ((helper = (helper = helpers.recommendStockList || (depth0 != null ? depth0.recommendStockList : depth0)) != null ? helper : helperMissing),(options={"name":"recommendStockList","hash":{},"fn":this.program(19, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.recommendStockList) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { buffer += stack1; }
  return buffer + "\n	</ul>\n</div>\n";
},"useData":true});
this["aimeilive"]["stockInfo"] = Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<header id=\"stockHeader\" data-id="
    + escapeExpression(((helper = (helper = helpers.stock_id || (depth0 != null ? depth0.stock_id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"stock_id","hash":{},"data":data}) : helper)))
    + ">\n	<h1>"
    + escapeExpression(((helper = (helper = helpers.stock_ename || (depth0 != null ? depth0.stock_ename : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"stock_ename","hash":{},"data":data}) : helper)))
    + "</h1>\n	<img src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.imgs || (depth0 != null ? depth0.imgs : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"imgs","hash":{},"data":data}) : helper)))
    + "\" alt=\"stockimg\">\n</header>\n";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing;
  stack1 = ((helper = (helper = helpers.stock || (depth0 != null ? depth0.stock : depth0)) != null ? helper : helperMissing),(options={"name":"stock","hash":{},"fn":this.program(1, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.stock) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { return stack1; }
  else { return ''; }
  },"useData":true});
this["aimeilive"]["stockPraise"] = Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<li data-id=\""
    + escapeExpression(((helper = (helper = helpers.stock_id || (depth0 != null ? depth0.stock_id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"stock_id","hash":{},"data":data}) : helper)))
    + "\">\n	<img class=\"lazy\" data-original=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.imgs || (depth0 != null ? depth0.imgs : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"imgs","hash":{},"data":data}) : helper)))
    + "\" src=\"../images/stockbg.png\" alt=\""
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "\">\n	<div>\n		<h2>"
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "</h2>\n		<footer>\n			<span>￥"
    + escapeExpression(((helper = (helper = helpers.price || (depth0 != null ? depth0.price : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"price","hash":{},"data":data}) : helper)))
    + "</span><button><i class=\"icon-praise\"></i><em>"
    + escapeExpression(((helper = (helper = helpers.fav_count || (depth0 != null ? depth0.fav_count : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"fav_count","hash":{},"data":data}) : helper)))
    + "</em></button>\n		</footer>\n	</div>\n</li>\n";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing;
  stack1 = ((helper = (helper = helpers.rst || (depth0 != null ? depth0.rst : depth0)) != null ? helper : helperMissing),(options={"name":"rst","hash":{},"fn":this.program(1, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.rst) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { return stack1; }
  else { return ''; }
  },"useData":true});
this["aimeilive"]["stockWall"] = Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<li data-id=\""
    + escapeExpression(((helper = (helper = helpers.id || (depth0 != null ? depth0.id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"id","hash":{},"data":data}) : helper)))
    + "\">\n	<img class=\"lazy\" data-original=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.stockImg || (depth0 != null ? depth0.stockImg : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"stockImg","hash":{},"data":data}) : helper)))
    + "\" src=\"../images/stockbg.png\" alt=\""
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "\">\n	<footer>\n		<h2>"
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "</h2>\n		<span>￥"
    + escapeExpression(((helper = (helper = helpers.priceout || (depth0 != null ? depth0.priceout : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"priceout","hash":{},"data":data}) : helper)))
    + "</span><strong><i class=\"icon-like\"></i>"
    + escapeExpression(((helper = (helper = helpers.liked || (depth0 != null ? depth0.liked : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"liked","hash":{},"data":data}) : helper)))
    + "</strong>\n	</footer>\n</li>";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing;
  stack1 = ((helper = (helper = helpers.stockList || (depth0 != null ? depth0.stockList : depth0)) != null ? helper : helperMissing),(options={"name":"stockList","hash":{},"fn":this.program(1, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.stockList) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { return stack1; }
  else { return ''; }
  },"useData":true});
this["aimeilive"]["successLive"] = Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<li data-id=\""
    + escapeExpression(((helper = (helper = helpers.id || (depth0 != null ? depth0.id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"id","hash":{},"data":data}) : helper)))
    + "\">\n	<div class=\"lazy\" data-original=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.livedImg || (depth0 != null ? depth0.livedImg : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"livedImg","hash":{},"data":data}) : helper)))
    + "\" style=\"background-image:url(../images/livebg.png);\">\n		<h2>"
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "</h2>\n		<p>"
    + escapeExpression(((helper = (helper = helpers.brandList || (depth0 != null ? depth0.brandList : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"brandList","hash":{},"data":data}) : helper)))
    + "</p>\n	</div>\n	<footer>\n		<span><img class=\"flag\" src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.country_flag || (depth0 != null ? depth0.country_flag : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"country_flag","hash":{},"data":data}) : helper)))
    + "\" alt=\""
    + escapeExpression(((helper = (helper = helpers.country || (depth0 != null ? depth0.country : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"country","hash":{},"data":data}) : helper)))
    + "国旗\">"
    + escapeExpression(((helper = (helper = helpers.country || (depth0 != null ? depth0.country : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"country","hash":{},"data":data}) : helper)))
    + " "
    + escapeExpression(((helper = (helper = helpers.user_num || (depth0 != null ? depth0.user_num : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"user_num","hash":{},"data":data}) : helper)))
    + "人观看</span><time><i class=\"icon-clock\"></i>直播已结束</time>\n	</footer>\n</li>";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing;
  stack1 = ((helper = (helper = helpers.lived || (depth0 != null ? depth0.lived : depth0)) != null ? helper : helperMissing),(options={"name":"lived","hash":{},"fn":this.program(1, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.lived) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { return stack1; }
  else { return ''; }
  },"useData":true});
this["aimeilive"]["weekBuy"] = Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<li data-id=\""
    + escapeExpression(((helper = (helper = helpers.stock_id || (depth0 != null ? depth0.stock_id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"stock_id","hash":{},"data":data}) : helper)))
    + "\">\n	<img src=\""
    + escapeExpression(((helper = (helper = helpers.staticPath || (depth0 != null ? depth0.staticPath : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"staticPath","hash":{},"data":data}) : helper)))
    + escapeExpression(((helper = (helper = helpers.imgs || (depth0 != null ? depth0.imgs : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"imgs","hash":{},"data":data}) : helper)))
    + "\" alt=\"商品展示图\">\n	<span class=\"tag\">包邮包税</span>\n	<footer>\n		<strong>"
    + escapeExpression(((helper = (helper = helpers.name || (depth0 != null ? depth0.name : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"name","hash":{},"data":data}) : helper)))
    + "</strong>\n		<div class=\"price\"><em>￥"
    + escapeExpression(((helper = (helper = helpers.price || (depth0 != null ? depth0.price : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"price","hash":{},"data":data}) : helper)))
    + "</em><del>￥"
    + escapeExpression(((helper = (helper = helpers.group_price || (depth0 != null ? depth0.group_price : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"group_price","hash":{},"data":data}) : helper)))
    + "</del></div>\n	</footer>\n</li>\n";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, helper, options, functionType="function", helperMissing=helpers.helperMissing, blockHelperMissing=helpers.blockHelperMissing;
  stack1 = ((helper = (helper = helpers.rst || (depth0 != null ? depth0.rst : depth0)) != null ? helper : helperMissing),(options={"name":"rst","hash":{},"fn":this.program(1, data),"inverse":this.noop,"data":data}),(typeof helper === functionType ? helper.call(depth0, options) : helper));
  if (!helpers.rst) { stack1 = blockHelperMissing.call(depth0, stack1, options); }
  if (stack1 != null) { return stack1; }
  else { return ''; }
  },"useData":true});