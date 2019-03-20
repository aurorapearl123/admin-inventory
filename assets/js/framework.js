
// Open Popup window
function popUp(URL,w,h) 
{
	day = new Date();
	id = day.getTime();
	
	var left = (screen.width/2)-(w/2);
    var top  = (screen.height/2)-(h/2);
	
	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=" + w + ",height=" + h + ",left = " + left + ",top = " + top + "');");
	return false;
}

function sorting(fld)
{
	if ($('#sortby').val() == fld) {
		if ($('#sortorder').val()== 'ASC') {
			$('#sortorder').val('DESC');
		} else {
			$('#sortorder').val('ASC');
		}
	} else {
		$('#sortby').val(fld);
		$('#sortorder').val('ASC');
	}

	$('#frmFilter').submit();
}

// Submit Filter in List
function changeDisplay()
{
	$('#cmdFilter').val("Apply Filters");
	$('#frmFilter').submit();
}

function format_number(num)
{
	var rgx  = /(\d+)(\d{3})/;
	
	num += '';
	x 	 = num.split('.');
	x1 	 = x[0];
	x2   = (x.length > 1) ? '.' + x[1] : '.00';	
	
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	} 
	
	return (x1 + x2);
}

function removeCommas(class_name)
{
	$('input[class='+class_name+']').each(function () {
		$(this).val($(this).val().replace(/,/g,''));
	});
}

function removeComma(id)
{
	return $('#'+id).val().replace(/,/g,'');
}

// function checkDateRange(first,second)
// {
// 	if ($('#'+first).val() !="" && $('#'+second).val() !="") {
// 		var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
// 		var startDate 	= new Date($('#'+first).val());
// 		var endDate 	= new Date($('#'+second).val());

// 		if (endDate.getTime() < startDate.getTime()){
// 			alert('Invalid Date Range!');		
// 		} 	
// 	} 
// }