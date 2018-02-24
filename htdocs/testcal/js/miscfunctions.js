var newWindow
function openPic (url,name,wide,high) {
	var window_top = (screen.height-high)/2;
	var window_left = (screen.width-wide)/2;
	newWindow = window.open('' + url + '',''+ name +'','height=' + high +',width=' + wide + ',top=' + window_top + ',left=' + window_left + ', scrollbars');
	return newWindow;
}

function closeWindow() {
  if (window.newWindow && window.newWindow.open && !window.newWindow.closed)
    window.newWindow.close();
}
function pickColor(color) {
	if (ColorPicker_targetInput==null) {
		alert("Target Input is null, which means you either didn't use the 'select' function or you have no defined your own 'pickColor' function to handle the picked color!");
		return;
		}
	ColorPicker_targetInput.value = color;

	document.cate.name.style.color = document.cate.color.value;
	document.cate.name.style.background = document.cate.background.value;


	}
var topcal = new CalendarPopup("testdiv2");
var cp = new ColorPicker();