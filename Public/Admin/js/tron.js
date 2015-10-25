/**tr鼠标悬浮高亮显示**/
$("tr.tron").mouseover(function() {
	$(this).find("td").css('backgroundColor','#f1fafc');
}).mouseout(function(){
	$(this).find('td').css('backgroundColor','#fff');
});